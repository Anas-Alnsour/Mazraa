<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport; // 👈 تم إضافته لمنع الأخطاء
use App\Models\FinancialTransaction; // 👈 تم إضافته للتعامل مع المبالغ المستردة
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
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
        // Checks if any date within the booking range intersects with the farm's blocked dates.
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

        // Include dynamic transport cost if added
        $transportCost = $request->requires_transport ? $request->transport_cost : 0;

        $totalBeforeTax = $farmPrice + $transportCost;
        $taxAmount = $totalBeforeTax * 0.16; // 16% VAT
        $finalTotal = $totalBeforeTax + $taxAmount;

        $commissionPercentage = $farm->commission_rate ? ($farm->commission_rate / 100) : 0.10;

        // التصحيح المعماري: عمولة المنصة وحصة المالك
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
            'status' => 'pending_payment', // 👈 1. غيرنا الحالة من pending إلى pending_payment
        ]);

        // 4. Create Transport Request if toggled
        if ($request->requires_transport && $request->pickup_lat && $request->pickup_lng) {
            Transport::create([
                'user_id' => Auth::id(),
                'farm_id' => $farm->id,
                'transport_type' => 'Shuttle',
                'passengers' => $farm->capacity,
                'start_and_return_point' => 'Custom User Location',
                'pickup_lat' => $request->pickup_lat,
                'pickup_lng' => $request->pickup_lng,
                'price' => $transportCost,
                'distance' => 0,
                'Farm_Arrival_Time' => $start,
                'Farm_Departure_Time' => $end,
                'status' => 'pending',
                'commission_amount' => $transportCost * 0.10, // 10% Platform fee
                'net_company_amount' => $transportCost * 0.90
            ]);
        }

        // 5. 👈 التوجيه الجديد: بدلاً من إرجاعه لصفحة المزرعة، نرسله لصفحة الدفع
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
     * إلغاء حجز (تم تحديثها لتشمل قانون الـ 48 ساعة)
     */
public function destroy(FarmBooking $booking)
    {
        // 1. فحص الأمان
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. التأكد من حالة الحجز
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return redirect()->route('bookings.my_bookings')->with('error', 'This booking cannot be cancelled.');
        }

        // 3. قانون الـ 48 ساعة
        $now = \Carbon\Carbon::now();
        $startTime = \Carbon\Carbon::parse($booking->start_time);
        $hoursDifference = $now->diffInHours($startTime, false); // false تسمح بالقيم السالبة

        if ($hoursDifference < 48) {
            // منع الإلغاء
            return redirect()->route('bookings.my_bookings')->with('error', 'Cancellations are not permitted within 48 hours of the check-in time. Please contact support.');
        }

        // 4. تنفيذ الإلغاء
        if ($booking->payment_status === 'paid') {
            // بنغير حالة الحجز لملغي، وبنخلي الدفع مدفوع عشان نعرف نرجعله فلوسه
            $booking->update([
                'status' => 'cancelled'
            ]);

            // تطبيق الحذف الناعم (Soft Delete)
            $booking->delete();

            return redirect()->route('bookings.my_bookings')->with('success', 'Booking cancelled successfully. Your refund is pending processing.');
        }

        // 5. في حال لم يكن مدفوعاً (أو Pending)
        $booking->update([
            'status' => 'cancelled'
        ]);

        // تطبيق الحذف الناعم (Soft Delete)
        $booking->delete();

        // توجيه المستخدم لصفحة الحجوزات دائماً
        return redirect()->route('bookings.my_bookings')->with('success', 'Booking cancelled successfully.');
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
     * تحديث الحجز
     */
    public function update(Request $request, FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'event_type' => 'required|string|max:255',
        ]);

        $booking->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'event_type' => $request->event_type,
        ]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully!');
    }
}
