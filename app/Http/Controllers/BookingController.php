<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * إنشاء حجز جديد
     */
    public function store(Request $request, Farm $farm)
    {
        $request->validate([
            'start_time' => 'required|date|after:now',
            'event_type' => 'nullable|string|max:255',
        ]);

        // تحديد مدة الحجز 12 ساعة
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = $start->copy()->addHours(12);

        // التحقق من عدم تعارض الحجز مع الحجوزات السابقة
        $conflict = $farm->bookings()
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                      ->orWhereBetween('end_time', [$start, $end]);
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'This time slot is already booked.');
        }

        // إنشاء الحجز
        $booking = FarmBooking::create([
            'farm_id' => $farm->id,
            'user_id' => Auth::id(),
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $request->event_type,
        ]);

        return back()->with('success', 'Your booking has been successfully created!');
    }

    /**
     * عرض الحجوزات المستقبلية للمستخدم مع دعم الفلترة
     */
    public function myBookings(Request $request)
    {
        $query = FarmBooking::where('user_id', Auth::id())
            ->where('start_time', '>=', now())
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
     * إلغاء حجز
     */
    public function destroy(FarmBooking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->delete();

        return back()->with('success', 'Booking cancelled successfully.');
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
