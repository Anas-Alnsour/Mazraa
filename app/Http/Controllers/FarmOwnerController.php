<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Auth;

class FarmOwnerController extends Controller
{
    public function dashboard()
    {
        $owner = Auth::user();

        // إحصائيات الداشبورد (استخدمنا owner_id الصحيح)
        $totalFarms = Farm::where('owner_id', $owner->id)->count();

        $totalBookings = FarmBooking::whereIn('farm_id', function($query) use ($owner) {
            $query->select('id')->from('farms')->where('owner_id', $owner->id);
        })->count();

        $pendingBookings = FarmBooking::whereIn('farm_id', function($query) use ($owner) {
            $query->select('id')->from('farms')->where('owner_id', $owner->id);
        })->where('status', 'pending')->count();

        return view('dashboards.farm_owner.index', compact('totalFarms', 'totalBookings', 'pendingBookings'));
    }

    public function myFarms()
    {
        $owner = Auth::user();

        // جلب المزارع مع الحجوزات ومعلومات العميل اللي حجز (user)
        $farms = Farm::where('owner_id', $owner->id)
                     ->with(['bookings' => function($query) {
                         $query->orderBy('created_at', 'desc')->with('user'); // جبنا اليوزر عشان نعرض اسمه
                     }])
                     ->get();

        return view('dashboards.farm_owner.farms', compact('farms'));
    }

    public function updateBookingStatus(Request $request, FarmBooking $booking)
    {
        // التحقق من أن المزرعة تابعة للمالك الحالي
        if ($booking->farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,rejected',
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Booking status updated successfully.');
    }
}
