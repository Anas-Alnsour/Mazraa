<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    /**
     * Display the Farm Owner Dashboard with exact financials and interactive calendar data.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        $farms = Farm::where('owner_id', $user->id)->get();
        $farmIds = $farms->pluck('id');

        $totalFarms = $farms->count();

        $activeBookingsCount = FarmBooking::whereIn('farm_id', $farmIds)
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();

        $pendingApprovalCount = FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'pending')
            ->count();

        // حسابات مالية دقيقة مبنية على الحجوزات المؤكدة فقط
        $confirmedBookings = FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'confirmed')
            ->get();

        $grossRevenue = $confirmedBookings->sum('total_price');
        $platformCommission = $confirmedBookings->sum('commission_amount');
        $taxes = $confirmedBookings->sum('tax_amount');
        // تم تصحيح اسم العمود حسب التوثيق المعماري
        $netProfit = $confirmedBookings->sum('net_owner_amount');

        $financials = [
            'gross' => $grossRevenue,
            'commission' => $platformCommission,
            'taxes' => $taxes,
            'net' => $netProfit
        ];

        // جلب الحجوزات للتقويم التفاعلي
        $allBookings = FarmBooking::with(['user', 'farm'])
            ->whereIn('farm_id', $farmIds)
            ->get();

        $calendarEvents = $allBookings->map(function ($booking) {
            $color = match($booking->status) {
                'confirmed' => '#10b981', // أخضر
                'pending' => '#f59e0b',   // برتقالي
                'cancelled' => '#ef4444', // أحمر
                default => '#6b7280'      // رمادي
            };

            return [
                'id' => $booking->id,
                'title' => $booking->farm->name . ' - ' . ($booking->user->name ?? 'User'),
                'start' => $booking->check_in_date,
                'end' => \Carbon\Carbon::parse($booking->check_out_date)->addDay()->format('Y-m-d'),
                'color' => $color,
                'extendedProps' => [
                    'status' => $booking->status,
                    'customer' => $booking->user->name ?? 'User',
                    'farm' => $booking->farm->name,
                    // تم تصحيح اسم العمود هنا أيضاً
                    'net' => $booking->net_owner_amount
                ]
            ];
        });

        return view('owner.dashboard', compact(
            'totalFarms',
            'activeBookingsCount',
            'pendingApprovalCount',
            'financials',
            'calendarEvents'
        ));
    }

    public function bookings(Request $request)
    {
        $user = Auth::user();
        $farmIds = Farm::where('owner_id', $user->id)->pluck('id');

        $query = FarmBooking::with(['farm', 'user'])->whereIn('farm_id', $farmIds);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $bookings->appends($request->all());

        return view('owner.bookings.index', compact('bookings'));
    }

    public function approveBooking($id)
    {
        $booking = FarmBooking::findOrFail($id);
        if ($booking->farm->owner_id !== auth()->id()) abort(403);

        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Booking confirmed successfully!');
    }

    public function rejectBooking($id)
    {
        $booking = FarmBooking::findOrFail($id);
        if ($booking->farm->owner_id !== auth()->id()) abort(403);

        $booking->update(['status' => 'cancelled']);
        return back()->with('error', 'Booking has been rejected.');
    }
}
