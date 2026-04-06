<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\FarmBlockedDate;
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
                'id' => 'booking_' . $booking->id,
                'title' => $booking->farm->name . ' - ' . ($booking->user->name ?? 'User'),
                'start' => $booking->start_time,
                'end' => \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d H:i:s'),
                'color' => $color,
                'extendedProps' => [
                    'type' => 'booking',
                    'status' => $booking->status,
                    'customer' => $booking->user->name ?? 'User',
                    'farm' => $booking->farm->name,
                    'net' => $booking->net_owner_amount // التصحيح هنا ليتوافق مع الداتا بيس
                ]
            ];
        });

        // جلب الأوقات المحجوبة للتقويم
        $blockedDates = FarmBlockedDate::whereIn('farm_id', $farmIds)->with('farm')->get();

        $blockedEvents = $blockedDates->map(function ($blocked) {
            $start = $blocked->date->format('Y-m-d');
            $end = $blocked->date->format('Y-m-d');

            if ($blocked->shift === 'morning') {
                $start .= ' 08:00:00';
                $end .= ' 16:00:00';
            } elseif ($blocked->shift === 'evening') {
                $start .= ' 18:00:00';
                $end .= ' 02:00:00';
                $end = \Carbon\Carbon::parse($end)->addDay()->format('Y-m-d H:i:s');
            } else { // full_day
                $start .= ' 00:00:00';
                $end .= ' 23:59:59';
            }

            return [
                'id' => 'blocked_' . $blocked->id,
                'title' => 'BLOCKED (' . ucfirst($blocked->shift) . ') - ' . $blocked->farm->name,
                'start' => $start,
                'end' => $end,
                'color' => '#111827', // أسود للأوقات المحجوبة
                'extendedProps' => [
                    'type' => 'blocked',
                    'shift' => $blocked->shift,
                    'farm' => $blocked->farm->name,
                    'farm_id' => $blocked->farm_id,
                    'reason' => $blocked->reason,
                    'date' => $blocked->date->format('Y-m-d'),
                ]
            ];
        });

        $calendarEvents = $calendarEvents->concat($blockedEvents);

        return view('owner.dashboard', compact(
            'totalFarms',
            'activeBookingsCount',
            'pendingApprovalCount',
            'financials',
            'calendarEvents',
            'farms'
        ));
    }

    /**
     * API Endpoint: Toggle Blocked Shift
     */
    public function toggleBlockShift(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'date' => 'required|date',
            'shift' => 'required|in:morning,evening,full_day',
            'reason' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();

        $farm = Farm::where('id', $request->farm_id)->where('owner_id', $user->id)->first();
        if (!$farm) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action or farm not found.'], 403);
        }

        $existingBlock = FarmBlockedDate::where('farm_id', $farm->id)
            ->where('date', $request->date)
            ->where('shift', $request->shift)
            ->first();

        if ($existingBlock) {
            $existingBlock->delete();
            return response()->json([
                'success' => true,
                'action' => 'unblocked',
                'message' => 'Shift unlocked successfully.'
            ]);
        } else {
            $block = FarmBlockedDate::create([
                'farm_id' => $farm->id,
                'date' => $request->date,
                'shift' => $request->shift,
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'action' => 'blocked',
                'block' => $block,
                'message' => 'Shift blocked successfully.'
            ]);
        }
    }

    /**
     * Display the list of bookings for the owner's farms.
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();

        // Ensure only the farm_owner can access
        if ($user->role !== 'farm_owner') {
            abort(403, 'Unauthorized access.');
        }

        $farmIds = Farm::where('owner_id', $user->id)->pluck('id');

        $query = FarmBooking::with(['farm', 'user'])
            ->whereIn('farm_id', $farmIds);

        // Filter by Status (Optional, useful for UI Tabs)
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Paginate results
        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $bookings->appends($request->all());

        return view('owner.bookings.index', compact('bookings'));
    }

    /**
     * Owner manually approves a booking.
     */
    public function approveBooking($id)
    {
        $booking = FarmBooking::findOrFail($id);

        if ($booking->farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
            return back()->with('success', 'Booking confirmed successfully!');
        }

        return back()->with('error', 'Booking cannot be confirmed at this stage.');
    }

    /**
     * Owner manually rejects/cancels a booking.
     */
    public function rejectBooking($id)
    {
        $booking = FarmBooking::findOrFail($id);

        if ($booking->farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking has been rejected/cancelled.');
    }
}
