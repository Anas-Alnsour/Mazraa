<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\FarmBlockedDate;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\Transport;
use App\Models\FinancialTransaction;
use App\Http\Requests\StoreFarmBookingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    /**
     * 1. إنشاء حجز جديد (Store)
     */
    public function store(StoreFarmBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $farm = Farm::findOrFail($validated['farm_id']);
        $user = Auth::user();

        // ---------------------------------------------------------------
        // Calculate prices and transport costs BEFORE the transaction
        // ---------------------------------------------------------------
        $bookingDate = Carbon::parse($validated['booking_date']);
        if ($validated['shift'] === 'morning') {
            $startTime = $bookingDate->copy()->setTime(8, 0, 0);
            $endTime   = $bookingDate->copy()->setTime(17, 0, 0);
            $farmPrice = $farm->price_per_morning_shift;
        } elseif ($validated['shift'] === 'evening') {
            $startTime = $bookingDate->copy()->setTime(19, 0, 0);
            $endTime   = $bookingDate->copy()->addDay()->setTime(6, 0, 0);
            $farmPrice = $farm->price_per_evening_shift;
        } else {
            $startTime = $bookingDate->copy()->setTime(10, 0, 0);
            $endTime   = $bookingDate->copy()->addDay()->setTime(8, 0, 0);
            $farmPrice = $farm->price_per_full_day;
        }

        $transportCost   = 0;
        $pickupLocation  = 'Custom User Location';
        $destGovernorate = $user->governorate ?? 'Amman';
        $requiresTransport = filter_var($request->requires_transport, FILTER_VALIDATE_BOOLEAN);

        // تأمين قيم اللوكيشن في حال كانت فاضية
        $pickupLat = $request->pickup_lat ?? 31.9522; // عمان الافتراضي
        $pickupLng = $request->pickup_lng ?? 35.9334;

        if ($requiresTransport) {
            $hoursUntilCheckIn = Carbon::now()->diffInHours($startTime, false);
            if ($hoursUntilCheckIn < 24) {
                return back()->with('error', 'Transport must be booked at least 24 hours prior to check-in.');
            }

            // SECURITY FIX: Calculate transport cost server-side
            $distance = (float) $request->input('distance', 0);
            $serverCalculatedCost = 5 + ($distance * 0.5);
            $transportCost   = max(15.00, $serverCalculatedCost);

            $pickupLocation  = $request->input('pickup_location', 'Custom User Location');
            $destGovernorate = $request->input('destination_governorate', $user->governorate ?? 'Amman');
        }

        $suppliesTotal = 0;
        $fetchedSupplies = collect(); // ✅ متغير لحفظ المستلزمات ومنع N+1

        if (!empty($validated['supplies'])) {
            // 🚀 تفكيك قنبلة الـ N+1: جلب كل المستلزمات المطلوبة باستعلام واحد فقط!
            $supplyIds = collect($validated['supplies'])->pluck('id');
            $fetchedSupplies = Supply::whereIn('id', $supplyIds)->get()->keyBy('id');

            foreach ($validated['supplies'] as $item) {
                $supply = $fetchedSupplies->get($item['id']);
                if ($supply) {
                    $suppliesTotal += ($supply->price * $item['quantity']);
                }
            }
        }

        $totalBeforeTax  = $farmPrice + $transportCost + $suppliesTotal;
        $taxAmount       = $totalBeforeTax * 0.10;
        $finalTotal      = $totalBeforeTax + $taxAmount;
        $commissionAmount = $farmPrice * ($farm->commission_rate / 100);
        $netOwnerAmount  = $farmPrice;

        // ---------------------------------------------------------------
        // 🔒 ATOMIC TRANSACTION — availability check + insert together
        // prevents double-booking race conditions
        // ---------------------------------------------------------------
        DB::beginTransaction();

        try {
            // Re-check blocked dates INSIDE the transaction (pessimistic lock)
            $isBlocked = FarmBlockedDate::where('farm_id', $farm->id)
                ->where('date', $validated['booking_date'])
                ->whereIn('shift', [$validated['shift'], 'full_day'])
                ->lockForUpdate()
                ->exists();

            if ($isBlocked) {
                DB::rollBack();
                return back()->with('error', 'Sorry, this farm is not available for the selected date and shift.');
            }

            // Re-check competitor bookings INSIDE the transaction (pessimistic lock)
            $existingBookingQuery = FarmBooking::where('farm_id', $farm->id)
                ->whereDate('start_time', $validated['booking_date'])
                ->whereIn('status', ['pending_payment', 'pending_verification', 'pending', 'confirmed', 'completed'])
                ->lockForUpdate();

            if ($validated['shift'] === 'full_day') {
                $isBooked = $existingBookingQuery->exists();
            } else {
                $isBooked = $existingBookingQuery->whereIn('event_type', [$validated['shift'], 'full_day'])->exists();
            }

            if ($isBooked) {
                DB::rollBack();
                return back()->with('error', 'Sorry, this shift is already booked by another user.');
            }

            // Create the booking
            $booking = FarmBooking::create([
                'farm_id'           => $farm->id,
                'user_id'           => $user->id,
                'start_time'        => $startTime,
                'end_time'          => $endTime,
                'event_type'        => $validated['shift'],
                'total_price'       => $finalTotal,
                'tax_amount'        => $taxAmount,
                'commission_amount' => $commissionAmount,
                'net_owner_amount'  => $netOwnerAmount,
                'payment_status'    => 'pending',
                'status'            => 'pending_payment',
                'requires_transport' => $requiresTransport,
                'transport_cost'    => $transportCost,
                'pickup_lat'        => $requiresTransport ? $pickupLat : null,
                'pickup_lng'        => $requiresTransport ? $pickupLng : null,
            ]);

            // Create the transport request (if needed)
            if ($requiresTransport) {
                $transport = Transport::create([
                    'user_id'                => $user->id,
                    'farm_id'                => $farm->id,
                    'farm_booking_id'        => $booking->id,
                    'transport_type'         => 'Shuttle',
                    'passengers'             => $request->input('passengers', 1),
                    'start_and_return_point' => $pickupLocation,
                    'destination_governorate' => $destGovernorate,
                    'pickup_lat'             => $pickupLat,
                    'pickup_lng'             => $pickupLng,
                    'price'                  => $transportCost,
                    'distance'               => $distance ?? 0,
                    'Farm_Arrival_Time'      => $startTime,
                    'Farm_Departure_Time'    => $endTime,
                    'status'                 => 'pending',
                    'commission_amount'      => $transportCost * 0.10,
                    'net_company_amount'     => $transportCost,
                ]);

                if (class_exists('\App\Services\TransportDispatchAction')) {
                    \App\Services\TransportDispatchAction::dispatchDriver($transport);
                }

                // =========================================================
                // 🚀 تفكيك قنبلة الإشعارات (استخدام Chunk لتفادي الـ RAM Exhaustion)
                // =========================================================
                try {
                    \App\Models\User::where('role', 'transport_company')->chunk(100, function ($transportCompanies) use ($transport) {
                        \Illuminate\Support\Facades\Notification::send($transportCompanies, new \App\Notifications\NewTransportRequestNotification($transport));
                    });
                } catch (\Exception $e) {
                    \Log::error('Transport Notification Error: ' . $e->getMessage());
                }
                // =========================================================
            }

            // Create supply orders (if any)
            if (!empty($validated['supplies'])) {
                $orderId = 'INV-' . strtoupper(uniqid());
                foreach ($validated['supplies'] as $item) {
                    // ✅ استخدام المجموعة المحملة مسبقاً لمنع N+1
                    $supply = $fetchedSupplies->get($item['id']);
                    if ($supply) {
                        $itemTotal = $supply->price * $item['quantity'];
                        $order = SupplyOrder::create([
                            'user_id'            => $user->id,
                            'supply_id'          => $supply->id,
                            'booking_id'         => $booking->id,
                            'order_id'           => $orderId,
                            'quantity'           => $item['quantity'],
                            'total_price'        => $itemTotal,
                            'commission_amount'  => $itemTotal * 0.10,
                            'net_company_amount' => $itemTotal * 0.90,
                            'status'             => 'pending',
                        ]);

                        // Automatically dispatch
                        \App\Services\SupplyDispatchAction::dispatchDriver($order);
                    }
                }
            }

            DB::commit();

            // =========================================================
            // 🚀 إطلاق الإشعارات (الحجز الجديد) بعد نجاح الحفظ
            // =========================================================
            try {
                // تحميل صاحب المزرعة لتفادي N+1
                $farm->loadMissing('user');

                // إشعار لصاحب المزرعة
                if ($farm->user) {
                    $farm->user->notify(new \App\Notifications\NewBookingReceivedNotification($booking));
                }
                // إشعار للعميل
                $user->notify(new \App\Notifications\BookingConfirmedNotification($booking));
            } catch (\Exception $e) {
                \Log::error('Notification Error (Store Booking): ' . $e->getMessage());
            }
            // =========================================================

            return redirect()->route('payment.select', ['booking' => $booking->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            // 🚨 تغيير الرسالة هنا عشان يعطيك الخطأ البرمجي الحقيقي بدل الجملة المبهمة!
            return back()->with('error', 'Booking Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
        }
    }

    /**
     * 2. عرض الحجوزات المستقبلية للمستخدم
     */
    public function myBookings(Request $request)
    {
        $query = FarmBooking::where('user_id', Auth::id())
            ->where('end_time', '>=', now())
            ->with(['farm' => function ($q) {
                $q->withTrashed();
            }, 'transport']);

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

        // 🚀 تفكيك قنبلة الـ RAM: استخدام paginate بدلاً من get مع الاحتفاظ بالفلاتر
        $bookings = $query->paginate(10);
        $bookings->appends($request->all());

        return view('bookings.my_bookings', compact('bookings'));
    }

    /**
     * 3. عرض تفاصيل الحجز
     */
    public function show(FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure relations are loaded
        $booking->load(['farm', 'transport.driver', 'transport.vehicle', 'supplyOrders.supply']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * 4. صفحة تعديل الحجز
     */
    public function edit(FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        return view('bookings.edit', compact('booking'));
    }

    /**
     * 5. إلغاء الحجز (مع إرجاع المبلغ)
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

        if ($booking->requires_transport) {
            $transport = Transport::where('farm_id', $booking->farm_id)
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->latest()
                ->first();

            if ($transport) {
                $transport->update(['status' => 'cancelled']);
                $transport->delete();
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
        }

        $booking->update(['status' => 'cancelled']);
        $booking->delete();

        // =========================================================
        // 🚀 إطلاق الإشعارات (إلغاء الحجز)
        // =========================================================
        try {
            $booking->farm->loadMissing('user');
            if ($booking->farm->user) {
                $booking->farm->user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'BookingCancelled',
                    'data' => [
                        'title' => 'Booking Cancelled',
                        'message' => 'A booking for ' . ($booking->farm->name ?? 'your farm') . ' was cancelled by the user.',
                        'url' => route('owner.bookings.index')
                    ],
                    'read_at' => null,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Notification Error (Cancel Booking): ' . $e->getMessage());
        }
        // =========================================================

        $message = $booking->payment_status === 'paid' ? 'Booking cancelled and refund processed successfully.' : 'Booking cancelled successfully.';
        return redirect()->route('bookings.my_bookings')->with('success', $message);
    }

    /**
     * 6. تحديث الحجز (معالجة الشفتات والفروقات المالية ومنع الاحتيال في المواصلات)
     */
    public function update(Request $request, FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|string|in:morning,evening,full_day',
            'requires_transport' => 'nullable|boolean',
            'pickup_lat' => 'required_if:requires_transport,true|nullable|numeric',
            'pickup_lng' => 'required_if:requires_transport,true|nullable|numeric',
            'passengers' => 'required_if:requires_transport,true|nullable|integer|min:1',
        ]);

        $farm = $booking->farm;

        // فحص التعارض
        $isBlocked = FarmBlockedDate::where('farm_id', $farm->id)
            ->where('date', $request->booking_date)
            ->whereIn('shift', [$request->shift, 'full_day'])
            ->exists();

        if ($isBlocked) {
            return back()->with('error', 'Sorry, the owner blocked this date/shift.');
        }

        $existingBookingQuery = FarmBooking::where('farm_id', $farm->id)
            ->where('id', '!=', $booking->id)
            ->whereDate('start_time', $request->booking_date)
            ->whereIn('status', ['pending_payment', 'pending_verification', 'pending', 'confirmed', 'completed']);

        if ($request->shift === 'full_day') {
            $isBooked = $existingBookingQuery->exists();
        } else {
            $isBooked = $existingBookingQuery->whereIn('event_type', [$request->shift, 'full_day'])->exists();
        }

        if ($isBooked) {
            return back()->with('error', 'Sorry, this shift is booked by another user.');
        }

        $bookingDate = Carbon::parse($request->booking_date);
        if ($request->shift === 'morning') {
            $startTime = $bookingDate->copy()->setTime(8, 0, 0);
            $endTime = $bookingDate->copy()->setTime(17, 0, 0);
            $farmPrice = $farm->price_per_morning_shift;
        } elseif ($request->shift === 'evening') {
            $startTime = $bookingDate->copy()->setTime(19, 0, 0);
            $endTime = $bookingDate->copy()->addDay()->setTime(6, 0, 0);
            $farmPrice = $farm->price_per_evening_shift;
        } else {
            $startTime = $bookingDate->copy()->setTime(10, 0, 0);
            $endTime = $bookingDate->copy()->addDay()->setTime(8, 0, 0);
            $farmPrice = $farm->price_per_full_day;
        }

        $transportCost = 0;
        $pickupLocation = 'Custom User Location';
        $destGovernorate = Auth::user()->governorate ?? 'Amman';
        $requiresTransportFlag = filter_var($request->requires_transport, FILTER_VALIDATE_BOOLEAN);

        $pickupLat = $request->pickup_lat ?? 31.9522;
        $pickupLng = $request->pickup_lng ?? 35.9334;

        // =========================================================
        // 🔒 حماية المواصلات (يجب أن يكون الحجز بعد أكثر من 24 ساعة)
        // =========================================================
        if ($requiresTransportFlag) {
            $hoursUntilCheckIn = Carbon::now()->diffInHours($startTime, false);

            if ($hoursUntilCheckIn < 24 && !$booking->requires_transport) {
                // المستخدم يحاول إضافة مواصلات جديدة لحجز قريب جداً
                return back()->with('error', 'Cannot add transport. Transport must be booked at least 24 hours prior to check-in.');
            }

            // SECURITY FIX: Calculate transport cost server-side
            $distance = (float) $request->input('distance', 0);
            $serverCalculatedCost = 5 + ($distance * 0.5);
            $transportCost   = max(15.00, $serverCalculatedCost);

            $pickupLocation = $request->input('pickup_location', 'Custom User Location');
            $destGovernorate = $request->input('destination_governorate', Auth::user()->governorate ?? 'Amman');
        }

        $originalSuppliesCost = SupplyOrder::where('booking_id', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->sum('total_price'); // ✅ ممتاز لاستخدام Query Builder

        $totalBeforeTax = $farmPrice + $transportCost + $originalSuppliesCost;
        $taxAmount = $totalBeforeTax * 0.10;
        $newTotalPrice = $totalBeforeTax + $taxAmount;

        $commissionAmount = $farmPrice * ($farm->commission_rate / 100);
        $netOwnerAmount = $farmPrice - $commissionAmount;

        $difference = $newTotalPrice - $booking->total_price;

        // ✅ تم التعديل إلى USD
        if ($difference > 0 && $booking->payment_status === 'paid') {
            Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $exchangeRate = 1.41;
                $amountInCents = (int)(round($difference * $exchangeRate, 2) * 100);

                $checkoutSession = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'usd', // 👈 USD
                            'product_data' => [
                                'name' => 'Upgrade Booking - ' . $farm->name,
                                'description' => '(Converted to USD)'
                            ],
                            'unit_amount' => $amountInCents,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('bookings.upgrade.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('bookings.edit', $booking->id),
                    'metadata' => [
                        'booking_id' => $booking->id,
                        'new_start_time' => $startTime->toDateTimeString(),
                        'new_end_time' => $endTime->toDateTimeString(),
                        'new_event_type' => $request->shift,
                        'new_total_price' => $newTotalPrice,
                        'new_tax_amount' => $taxAmount,
                        'new_commission' => $commissionAmount,
                        'new_net_owner' => $netOwnerAmount,
                        'requires_transport' => $requiresTransportFlag ? 'true' : 'false',
                        'transport_cost' => $transportCost,
                        'pickup_lat' => $pickupLat,
                        'pickup_lng' => $pickupLng,
                        'pickup_location' => substr($pickupLocation, 0, 200), // Ensure it fits in Stripe metadata
                        'destination_governorate' => substr($destGovernorate, 0, 200),
                        'transport_passengers' => $request->passengers ?? 1,
                    ],
                ]);
                return redirect($checkoutSession->url);
            } catch (ApiErrorException $e) {
                return back()->with('error', 'Stripe Error: ' . $e->getMessage());
            }
        }

        // ✅ تم التعديل إلى USD بالنسبة للاسترجاع (Refund) ليتوافق مع الدفعة الأصلية
        if ($difference < 0 && $booking->payment_status === 'paid' && $booking->stripe_payment_intent_id) {
            $refundAmount = abs($difference);
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            try {
                $exchangeRate = 1.41;
                $amountInCents = (int)(round($refundAmount * $exchangeRate, 2) * 100);

                \Stripe\Refund::create([
                    'payment_intent' => $booking->stripe_payment_intent_id,
                    'amount' => $amountInCents, // Stripe uses Cents for USD
                ]);
            } catch (\Exception $e) {
                \Log::error("Booking Update Refund Failed: " . $e->getMessage());
            }
        }

        $existingTransport = \App\Models\Transport::where('farm_booking_id', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->first();

        if ($booking->requires_transport && !$requiresTransportFlag && $existingTransport) {
            $existingTransport->update(['status' => 'cancelled']);
            $existingTransport->delete();
        } elseif ($requiresTransportFlag) {
            if ($existingTransport) {
                $existingTransport->update([
                    'Farm_Arrival_Time' => $startTime,
                    'Farm_Departure_Time' => $endTime,
                    'price' => $transportCost,
                    'commission_amount' => $transportCost * 0.10,
                    'net_company_amount' => $transportCost * 0.90,
                    'pickup_lat' => $pickupLat,
                    'pickup_lng' => $pickupLng,
                ]);
                \App\Services\TransportDispatchAction::dispatchDriver($existingTransport);
            } elseif ($booking->payment_status !== 'paid') {
                $transport = \App\Models\Transport::create([
                    'user_id' => Auth::id(),
                    'farm_id' => $farm->id,
                    'farm_booking_id' => $booking->id,
                    'transport_type' => 'Shuttle',
                    'passengers' => $request->passengers ?? 1,
                    'start_and_return_point' => $pickupLocation,
                    'pickup_location' => $pickupLocation,
                    'destination_governorate' => $destGovernorate,
                    'pickup_lat' => $pickupLat,
                    'pickup_lng' => $pickupLng,
                    'price' => $transportCost,
                    'distance' => $distance ?? 0,
                    'Farm_Arrival_Time' => $startTime,
                    'Farm_Departure_Time' => $endTime,
                    'status' => 'pending',
                    'commission_amount' => $transportCost * 0.10,
                    'net_company_amount' => $transportCost * 0.90
                ]);
                \App\Services\TransportDispatchAction::dispatchDriver($transport);

                // 🚀 تفكيك قنبلة الإشعارات هنا أيضاً
                try {
                    \App\Models\User::where('role', 'transport_company')->chunk(100, function ($transportCompanies) use ($transport) {
                        \Illuminate\Support\Facades\Notification::send($transportCompanies, new \App\Notifications\NewTransportRequestNotification($transport));
                    });
                } catch (\Exception $e) {
                    \Log::error('Transport Notification Error: ' . $e->getMessage());
                }
            }
        }

        // =========================================================
        // 💡 تحديث الحجز مع ضمان أن يبقى confirmed إذا لم تكن هناك ترقية تنتظر الدفع
        // =========================================================

        $newStatus = $booking->status;
        if ($booking->payment_status === 'paid' && $difference <= 0) {
            $newStatus = 'confirmed'; // يحافظ على حالة التأكيد إذا لم يكن هناك فاتورة جديدة للدفع
        }

        $booking->update([
            'start_time' => $startTime,
            'end_time' => $endTime,
            'event_type' => $request->shift,
            'total_price' => $newTotalPrice,
            'tax_amount' => $taxAmount,
            'commission_amount' => $commissionAmount,
            'net_owner_amount' => $netOwnerAmount,
            'requires_transport' => $requiresTransportFlag,
            'transport_cost' => $transportCost,
            'pickup_lat' => $requiresTransportFlag ? $pickupLat : null,
            'pickup_lng' => $requiresTransportFlag ? $pickupLng : null,
            'status' => $newStatus, // الحفاظ على حالة الحجز
        ]);

        // إطلاق الإشعارات
        try {
            $booking->user->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'BookingUpdated',
                'data' => [
                    'title' => 'Booking Updated',
                    'message' => 'Your booking for ' . ($farm->name ?? 'the farm') . ' has been updated successfully.',
                    'url' => route('bookings.show', $booking->id)
                ],
                'read_at' => null,
            ]);

            $farm->loadMissing('user');
            if ($farm->user) {
                $farm->user->notifications()->create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'BookingUpdated',
                    'data' => [
                        'title' => 'Booking Modified',
                        'message' => 'Booking details for ' . ($farm->name ?? 'your farm') . ' have been modified by the customer.',
                        'url' => route('owner.bookings.show', $booking->id)
                    ],
                    'read_at' => null,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Notification Error (Update Booking): ' . $e->getMessage());
        }

        $message = $difference < 0
            ? 'Booking updated successfully! A partial refund of ' . abs(round($difference, 2)) . ' JOD has been issued.'
            : 'Booking updated successfully!';

        return redirect()->route('bookings.show', $booking->id)->with('success', $message);
    }

    /**
     * 7. نجاح عملية الترقية والدفع
     */
    public function upgradeSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) return redirect()->route('bookings.my_bookings')->with('error', 'Invalid session.');

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if ($session->payment_status !== 'paid') return redirect()->route('bookings.my_bookings')->with('error', 'Payment not completed.');

            $booking = FarmBooking::findOrFail($session->metadata->booking_id);
            $requiresTransport = filter_var($session->metadata->requires_transport, FILTER_VALIDATE_BOOLEAN);

            $booking->update([
                'start_time' => Carbon::parse($session->metadata->new_start_time),
                'end_time' => Carbon::parse($session->metadata->new_end_time),
                'event_type' => $session->metadata->new_event_type,
                'total_price' => $session->metadata->new_total_price,
                'tax_amount' => $session->metadata->new_tax_amount,
                'commission_amount' => $session->metadata->new_commission,
                'net_owner_amount' => $session->metadata->new_net_owner,
                'requires_transport' => $requiresTransport,
                'transport_cost' => $requiresTransport ? (float)$session->metadata->transport_cost : 0,
                'pickup_lat' => $requiresTransport ? $session->metadata->pickup_lat : null,
                'pickup_lng' => $requiresTransport ? $session->metadata->pickup_lng : null,
            ]);

            if ($requiresTransport) {
                $existingTransport = \App\Models\Transport::where('farm_booking_id', $booking->id)
                    ->where('status', '!=', 'cancelled')
                    ->latest()
                    ->first();

                if (!$existingTransport) {

                    // جلب قيمة موقع التوصيل من الميتاداتا، وفي حال كانت فارغة نضع قيمة افتراضية
                    $pickupLocationFromMeta = $session->metadata->pickup_location ?? 'Custom User Location';

                    $distance = (float) $request->input('distance', 0);

                    $transport = \App\Models\Transport::create([
                        'user_id' => Auth::id(),
                        'farm_id' => $booking->farm_id,
                        'farm_booking_id' => $booking->id,
                        'transport_type' => 'Shuttle',
                        'passengers' => $session->metadata->transport_passengers,
                        'start_and_return_point' => $pickupLocationFromMeta, // تعيين القيمة للحقل الصحيح
                        'pickup_location' => $session->metadata->pickup_location,
                        'destination_governorate' => $session->metadata->destination_governorate,
                        'pickup_lat' => $session->metadata->pickup_lat,
                        'pickup_lng' => $session->metadata->pickup_lng,
                        'price' => $session->metadata->transport_cost,
                        'distance' => $distance ?? 0,
                        'Farm_Arrival_Time' => Carbon::parse($session->metadata->new_start_time),
                        'Farm_Departure_Time' => Carbon::parse($session->metadata->new_end_time),
                        'status' => 'pending',
                        'commission_amount' => $session->metadata->transport_cost * 0.10,
                        'net_company_amount' => $session->metadata->transport_cost * 0.90
                    ]);
                    \App\Services\TransportDispatchAction::dispatchDriver($transport);

                    // =========================================================
                    // 🚀 إطلاق إشعار طلب التوصيل لشركات المواصلات عند الدفع والترقية
                    // =========================================================
                    try {
                        \App\Models\User::where('role', 'transport_company')->chunk(100, function ($transportCompanies) use ($transport) {
                            \Illuminate\Support\Facades\Notification::send($transportCompanies, new \App\Notifications\NewTransportRequestNotification($transport));
                        });
                    } catch (\Exception $e) {
                        \Log::error('Transport Notification Error: ' . $e->getMessage());
                    }
                    // =========================================================
                } else {
                    $existingTransport->update([
                        'Farm_Arrival_Time' => Carbon::parse($session->metadata->new_start_time),
                        'Farm_Departure_Time' => Carbon::parse($session->metadata->new_end_time),
                        'price' => $session->metadata->transport_cost,
                        'pickup_location' => $session->metadata->pickup_location,
                        'pickup_lat' => $session->metadata->pickup_lat,
                        'pickup_lng' => $session->metadata->pickup_lng,
                    ]);
                    \App\Services\TransportDispatchAction::dispatchDriver($existingTransport);
                }
            }

            return redirect()->route('bookings.show', $booking->id)->with('success', 'Booking upgraded successfully! Payment received.');
        } catch (\Exception $e) {
            return redirect()->route('bookings.my_bookings')->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }
}
