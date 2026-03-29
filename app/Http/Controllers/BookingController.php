<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class BookingController extends Controller
{
    /**
     * إنشاء حجز جديد
     */
    /**
     * إنشاء حجز جديد
     */
    public function store(Request $request, Farm $farm)
    {
        $request->validate([
            'start_time' => 'required|date|after_or_equal:today',
            'end_time'   => 'required|date|after:start_time',
            'event_type' => 'required|string|max:255',
            'requires_transport' => 'nullable|boolean',
            'transport_cost' => 'nullable|numeric|min:0',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'transport_passengers' => 'nullable|integer|min:1', // 👈 Validation added
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to book a farm.');
        }

        $start = Carbon::parse($request->start_time);
        $end   = Carbon::parse($request->end_time);

        // 1. Overlap Check (Existing Bookings)
        $conflict = $farm->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
            })->exists();

        if ($conflict) {
            return back()->with('error', 'This shift is already booked. Please choose another one.');
        }

        // 1.5. Blocked Dates Check (Owner Maintenance/Unavailable)
        $blockedConflict = $farm->blockedDates()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->exists();

        if ($blockedConflict) {
            return back()->with('error', 'The selected dates intersect with dates blocked by the farm owner. Please choose another date.');
        }

        // 2. Financials Calculation
        $hours = $start->diffInHours($end);
        $numberOfShifts = ceil($hours / 12);
        if ($numberOfShifts < 1) $numberOfShifts = 1;

        $farmPrice = $numberOfShifts * $farm->price_per_night;

        $transportCost = $request->requires_transport ? $request->transport_cost : 0;
        $totalBeforeTax = $farmPrice + $transportCost;
        $taxAmount = $totalBeforeTax * 0.16; // 16% VAT
        $finalTotal = $totalBeforeTax + $taxAmount;

        $commissionPercentage = $farm->commission_rate ? ($farm->commission_rate / 100) : 0.10;
        $commissionAmount = $farmPrice * $commissionPercentage;
        $netOwnerAmount = $farmPrice - $commissionAmount;

        // 3. Create Booking Record
        $booking = FarmBooking::create([
            'farm_id' => $farm->id,
            'user_id' => Auth::id(),
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $request->event_type,
            'total_price' => $finalTotal,
            'tax_amount' => $taxAmount,
            'commission_amount' => $commissionAmount,
            'net_owner_amount' => $netOwnerAmount,
            'status' => 'pending_payment',
            'requires_transport' => $request->requires_transport ? true : false,
            'transport_cost' => $transportCost,
            'pickup_lat' => $request->pickup_lat,
            'pickup_lng' => $request->pickup_lng,
        ]);

        // 4. Create Transport Request if toggled and Dispatch Driver
        if ($request->requires_transport && $request->pickup_lat && $request->pickup_lng) {
            $transport = Transport::create([
                'user_id' => Auth::id(),
                'farm_id' => $farm->id,
                'transport_type' => 'Shuttle',
                'passengers' => $request->transport_passengers ?? 1, // 👈 Uses passengers from form
                'start_and_return_point' => 'Custom User Location',
                'pickup_lat' => $request->pickup_lat,
                'pickup_lng' => $request->pickup_lng,
                'price' => $transportCost,
                'distance' => 0,
                'Farm_Arrival_Time' => $start,
                'Farm_Departure_Time' => $end,
                'status' => 'pending',
                'commission_amount' => $transportCost * 0.10,
                'net_company_amount' => $transportCost * 0.90
            ]);

            // 👈 Immediately trigger Auto-Dispatch Action
            \App\Services\TransportDispatchAction::dispatchDriver($transport);
        }

        // 5. Send to Payment Selection
        return redirect()->route('payment.select', ['booking' => $booking->id]);
    }

    /**
     * عرض الحجوزات المستقبلية للمستخدم مع دعم الفلترة
     */
    public function myBookings(Request $request)
    {
        $query = FarmBooking::where('user_id', Auth::id())
            ->where('end_time', '>=', now())
            ->with('farm');

        if ($request->filled('filter_date')) {
            $query->whereDate('start_time', $request->filter_date);
        }

        if ($request->filled('filter_event')) {
            $query->where('event_type', $request->filter_event);
        }

        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'date_asc':
                    $query->orderBy('start_time', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('start_time', 'desc');
                    break;
                case 'name_asc':
                    $query->join('farms', 'farm_bookings.farm_id', '=', 'farms.id')
                        ->orderBy('farms.name', 'asc')
                        ->select('farm_bookings.*');
                    break;
                case 'name_desc':
                    $query->join('farms', 'farm_bookings.farm_id', '=', 'farms.id')
                        ->orderBy('farms.name', 'desc')
                        ->select('farm_bookings.*');
                    break;
            }
        } else {
            $query->orderBy('start_time', 'asc');
        }

        $bookings = $query->get();

        return view('bookings.my_bookings', compact('bookings'));
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * صفحة تعديل الحجز
     */
    public function edit(FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.edit', compact('booking'));
    }

    /**
     * إلغاء حجز (استرجاع المبلغ من سترايب)
     */
    /**
     * إلغاء حجز (استرجاع المبلغ من سترايب)
     */
    public function destroy(FarmBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return redirect()->route('bookings.my_bookings')->with('error', 'This booking cannot be cancelled.');
        }

        $now = Carbon::now();
        $startTime = Carbon::parse($booking->start_time);
        $hoursDifference = $now->diffInHours($startTime, false);

        if ($hoursDifference < 48) {
            return redirect()->route('bookings.my_bookings')->with('error', 'Cancellations are not permitted within 48 hours of the check-in time. Please contact support.');
        }

        // إلغاء رحلة المواصلات المرتبطة بهذا الحجز في حال وجدت
        if ($booking->requires_transport) {
            $transport = Transport::where('farm_id', $booking->farm_id)
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            if ($transport) {
                $transport->update(['status' => 'cancelled']);
                $transport->delete(); // Soft Delete
            }
        }

        if ($booking->payment_status === 'paid') {
            if ($booking->stripe_payment_intent_id) {
                Stripe::setApiKey(config('services.stripe.secret'));
                try {
                    Refund::create([
                        'payment_intent' => $booking->stripe_payment_intent_id,
                    ]);
                } catch (ApiErrorException $e) {
                    return back()->with('error', 'Refund failed: ' . $e->getMessage());
                }
            }

            // لا نعدل payment_status لمنع إيرور الـ ENUM
            $booking->update([
                'status' => 'cancelled'
            ]);

            $booking->delete();

            return redirect()->route('bookings.my_bookings')->with('success', 'Booking cancelled and refund processed successfully.');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->delete();

        return redirect()->route('bookings.my_bookings')->with('success', 'Booking cancelled successfully.');
    }

     /**
     * تحديث الحجز (دفع الفرقية عبر سترايب إذا لزم الأمر، وإضافة/إزالة المواصلات)
     */
    public function update(Request $request, FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) { abort(403); }

        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'event_type' => 'required|string|max:255',
            'requires_transport' => 'nullable|boolean',
            'transport_cost' => 'nullable|numeric|min:0',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'transport_passengers' => 'nullable|integer|min:1',
        ]);

        $farm = $booking->farm;
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        // إعادة حساب السعر
        $isMultiDay = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) > 1;
        $isFullDay = (!$isMultiDay && $start->format('H') == '10' && $start->format('Y-m-d') != $end->format('Y-m-d'));

        $basePrice = 0;
        if ($isMultiDay) {
            $days = ceil($start->diffInHours($end) / 24);
            if($days == 0) $days = 1;
            $basePrice = ($farm->price_per_night * 2) * $days;
        } elseif ($isFullDay) {
            $basePrice = $farm->price_per_night * 2;
        } else {
            $basePrice = $farm->price_per_night;
        }

        $transportCost = $request->requires_transport ? $request->transport_cost : 0;
        $totalBeforeTax = $basePrice + $transportCost;
        $taxAmount = $totalBeforeTax * 0.16;
        $newTotalPrice = $totalBeforeTax + $taxAmount;

        $difference = $newTotalPrice - $booking->total_price;
        $requiresTransportFlag = $request->requires_transport ? true : false;

        // الحالة 1: السعر الجديد أعلى (UPGRADE - دفع فرقية) - يتضمن إضافة مواصلات جديدة
        if ($difference > 0 && $booking->payment_status === 'paid') {
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $checkoutSession = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'jod',
                            'product_data' => ['name' => 'Upgrade Booking - ' . $farm->name],
                            'unit_amount' => (int)(round($difference, 2) * 1000),
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('bookings.upgrade.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('bookings.edit', $booking->id),
                    'metadata' => [
                        'booking_id' => $booking->id,
                        'new_start_time' => $request->start_time,
                        'new_end_time' => $request->end_time,
                        'new_event_type' => $request->event_type,
                        'new_total_price' => $newTotalPrice,
                        // إرسال تفاصيل المواصلات الجديدة مع الدفع إذا تم تفعيلها
                        'requires_transport' => $requiresTransportFlag,
                        'transport_cost' => $transportCost,
                        'pickup_lat' => $request->pickup_lat,
                        'pickup_lng' => $request->pickup_lng,
                        'transport_passengers' => $request->transport_passengers ?? 1,
                    ],
                ]);
                return redirect($checkoutSession->url);
            } catch (ApiErrorException $e) {
                return back()->with('error', 'Stripe Error: ' . $e->getMessage());
            }
        }

        // الحالة 2: السعر الجديد أقل (DOWNGRADE - إرجاع فرقية) - يتضمن إزالة المواصلات
        if ($difference < 0 && $booking->payment_status === 'paid' && $booking->stripe_payment_intent_id) {
            $refundAmount = abs($difference);
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                Refund::create([
                    'payment_intent' => $booking->stripe_payment_intent_id,
                    'amount' => (int)(round($refundAmount, 2) * 1000), // إرجاع الفرقية فقط
                ]);
            } catch (ApiErrorException $e) {
                return back()->with('error', 'Partial Refund failed: ' . $e->getMessage());
            }
        }

        // معالجة حالة المواصلات إذا تم إلغاؤها (تغيير من true إلى false)
        if ($booking->requires_transport && !$requiresTransportFlag) {
            // البحث عن الرحلة المرتبطة وحذفها باستخدام SoftDelete
            $existingTransport = Transport::where('farm_id', $farm->id)
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            if ($existingTransport) {
                $existingTransport->update(['status' => 'cancelled']);
                $existingTransport->delete(); // Soft Delete
            }
        }

        // معالجة حالة مزامنة التواريخ في حال وجود مواصلات ولم تتغير قيمتها (تعديل وقت المزرعة فقط)
        if ($booking->requires_transport && $requiresTransportFlag) {
            $existingTransport = Transport::where('farm_id', $farm->id)
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            if ($existingTransport) {
                $existingTransport->update([
                    'Farm_Arrival_Time' => $start,
                    'Farm_Departure_Time' => $end,
                ]);
            }
        }

        // معالجة حالة إضافة مواصلات جديدة لكن (لا يوجد فرق مالي للسترايب) مثلاً كان الدفع لم يتم بعد
        if (!$booking->requires_transport && $requiresTransportFlag && $booking->payment_status !== 'paid') {
            $transport = Transport::create([
                'user_id' => Auth::id(),
                'farm_id' => $farm->id,
                'transport_type' => 'Shuttle',
                'passengers' => $request->transport_passengers ?? 1,
                'start_and_return_point' => 'Custom User Location',
                'pickup_lat' => $request->pickup_lat,
                'pickup_lng' => $request->pickup_lng,
                'price' => $transportCost,
                'distance' => 0,
                'Farm_Arrival_Time' => $start,
                'Farm_Departure_Time' => $end,
                'status' => 'pending',
                'commission_amount' => $transportCost * 0.10,
                'net_company_amount' => $transportCost * 0.90
            ]);

            // تشغيل محرك البحث الآلي عن سائق
            \App\Services\TransportDispatchAction::dispatchDriver($transport);
        }

        // تحديث مباشر بالداتابيز (في حال مافي تغيير بالسعر، أو تم إرجاع فرقية، أو لم يتم الدفع مسبقاً)
        $booking->update([
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $request->event_type,
            'total_price' => $newTotalPrice,
            'requires_transport' => $requiresTransportFlag,
            'transport_cost' => $transportCost,
            'pickup_lat' => $requiresTransportFlag ? $request->pickup_lat : null,
            'pickup_lng' => $requiresTransportFlag ? $request->pickup_lng : null,
        ]);

        $message = $difference < 0
            ? 'Booking updated successfully! A partial refund of ' . abs(round($difference, 2)) . ' JOD has been issued.'
            : 'Booking updated successfully!';

        return redirect()->route('bookings.show', $booking->id)->with('success', $message);
    }
    /**
     * تأكيد الدفع للتحديث (Upgrade Success)
     */
    public function upgradeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('bookings.my_bookings')->with('error', 'Invalid payment session.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('bookings.my_bookings')->with('error', 'Payment for upgrade was not completed.');
            }

            // استخراج البيانات اللي خزنّاها قبل الدفع
            $bookingId = $session->metadata->booking_id;
            $newStartTime = $session->metadata->new_start_time;
            $newEndTime = $session->metadata->new_end_time;
            $newEventType = $session->metadata->new_event_type;
            $newTotalPrice = $session->metadata->new_total_price;

            $requiresTransport = filter_var($session->metadata->requires_transport ?? false, FILTER_VALIDATE_BOOLEAN);
            $transportCost = $session->metadata->transport_cost ?? 0;
            $pickupLat = $session->metadata->pickup_lat ?? null;
            $pickupLng = $session->metadata->pickup_lng ?? null;
            $passengers = $session->metadata->transport_passengers ?? 1;

            $booking = FarmBooking::findOrFail($bookingId);

            // حفظ التحديث بالداتابيز وتحديث السعر الجديد وحالة المواصلات
            $booking->update([
                'start_time' => Carbon::parse($newStartTime),
                'end_time' => Carbon::parse($newEndTime),
                'event_type' => $newEventType,
                'total_price' => $newTotalPrice,
                'requires_transport' => $requiresTransport,
                'transport_cost' => $requiresTransport ? $transportCost : 0,
                'pickup_lat' => $requiresTransport ? $pickupLat : null,
                'pickup_lng' => $requiresTransport ? $pickupLng : null,
            ]);

            // التأكد إذا المواصلات تم طلبها حديثاً بعد الدفع ليتم إنشاؤها وإرسال السائق
            if ($requiresTransport && $pickupLat && $pickupLng) {
                // التأكد من عدم إنشاء رحلة مزدوجة بالغلط
                $existingTransport = Transport::where('farm_id', $booking->farm_id)
                    ->where('user_id', Auth::id())
                    ->where('status', '!=', 'cancelled')
                    ->latest()
                    ->first();

                if (!$existingTransport) {
                    $transport = Transport::create([
                        'user_id' => Auth::id(),
                        'farm_id' => $booking->farm_id,
                        'transport_type' => 'Shuttle',
                        'passengers' => $passengers,
                        'start_and_return_point' => 'Custom User Location',
                        'pickup_lat' => $pickupLat,
                        'pickup_lng' => $pickupLng,
                        'price' => $transportCost,
                        'distance' => 0,
                        'Farm_Arrival_Time' => Carbon::parse($newStartTime),
                        'Farm_Departure_Time' => Carbon::parse($newEndTime),
                        'status' => 'pending',
                        'commission_amount' => $transportCost * 0.10,
                        'net_company_amount' => $transportCost * 0.90
                    ]);

                    // تشغيل محرك البحث الآلي عن سائق
                    \App\Services\TransportDispatchAction::dispatchDriver($transport);
                }
            }

            return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking upgraded successfully! Payment received.');

        } catch (\Exception $e) {
            return redirect()->route('bookings.my_bookings')->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

}
