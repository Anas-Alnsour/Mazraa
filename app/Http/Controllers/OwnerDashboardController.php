<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\FarmBlockedDate;
use App\Models\FinancialTransaction;
use App\Models\User;
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

        $farms = Farm::where('owner_id', $user->id)->with(['images', 'owner'])->get();
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
     * Display a specific booking detail.
     */
    public function show($id)
    {
        // Removed undefined 'payments' and 'reviews' relationships
        // Added 'farm.images' for optimized eager loading
        $booking = FarmBooking::with(['farm.images', 'user'])->findOrFail($id);

        if ($booking->farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('owner.bookings.show', compact('booking'));
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

    /**
     * Display the financial overview for the owner
     */
    public function financials(Request $request)
    {
        $userId = Auth::id();

        // Total Lifetime Earnings (Sum of all completed credits)
        $lifetimeEarnings = \App\Models\FinancialTransaction::where('user_id', $userId)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        // Total Payouts Received (Sum of all debits)
        $totalPayouts = \App\Models\FinancialTransaction::where('user_id', $userId)
            ->where('transaction_type', 'debit')
            ->sum('amount');

        // Available Balance for payout
        $availableBalance = round($lifetimeEarnings - $totalPayouts, 2);

        // Calculate pending revenue (future/pending bookings that haven't cleared)
        $farmIds = Farm::where('owner_id', $userId)->pluck('id');
        $pendingRevenue = FarmBooking::whereIn('farm_id', $farmIds)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>=', now())
            ->get()
            ->sum('net_owner_amount');

        // Recent Transactions History with Filters
        $query = \App\Models\FinancialTransaction::where('user_id', $userId);

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'day':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(15);
        $transactions->appends($request->all());

        return view('owner.financials', compact('availableBalance', 'pendingRevenue', 'lifetimeEarnings', 'transactions'));
    }

    /**
     * Export owner financials to CSV.
     */
    public function exportCsv()
    {
        $userId = Auth::id();
        $transactions = \App\Models\FinancialTransaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "financial_report_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility (optional but helpful for Arabic text)
            // fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Date', 'Description', 'Reference Type', 'Reference ID', 'Amount (JOD)', 'Type']);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->created_at->format('Y-m-d H:i'),
                    $tx->description,
                    ucwords(str_replace('_', ' ', $tx->reference_type)),
                    $tx->reference_id,
                    number_format($tx->amount, 2),
                    strtoupper($tx->transaction_type)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mark a booking as completed and trigger the financial escrow payout.
     */
    public function completeBooking($id)
    {
        $booking = FarmBooking::with('farm')->findOrFail($id);

        if ($booking->farm->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only confirmed bookings can be completed
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as completed.');
        }

        // Idempotency: skip if already credited
        $alreadyTransacted = FinancialTransaction::where('reference_type', 'booking')
            ->where('reference_id', $booking->id)
            ->exists();

        if ($alreadyTransacted) {
             $booking->update(['status' => 'completed']);
             return back()->with('success', 'Booking finalized.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $adminId = User::where('role', 'admin')->value('id');

            // 1. Admin Commission
            FinancialTransaction::create([
                'user_id'          => $adminId,
                'reference_type'   => 'farm_booking',
                'reference_id'     => $booking->id,
                'amount'           => $booking->commission_amount,
                'transaction_type' => 'credit',
                'description'      => "Platform commission — Completed Booking #{$booking->id}",
            ]);

            // 2. Owner Net Profit
            FinancialTransaction::create([
                'user_id'          => Auth::id(),
                'reference_type'   => 'farm_booking',
                'reference_id'     => $booking->id,
                'amount'           => $booking->net_owner_amount,
                'transaction_type' => 'credit',
                'description'      => "Net payout — Completed Booking #{$booking->id}",
            ]);

            $booking->update(['status' => 'completed']);

            \Illuminate\Support\Facades\DB::commit();
            return back()->with('success', 'Booking completed and funds cleared to your balance!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to finalize booking: ' . $e->getMessage());
        }
    }

    /**
     * Handle manual Payout Request
     */
    public function requestPayout(Request $request)
    {
        $user = Auth::user();

        $credits = \App\Models\FinancialTransaction::where('user_id', $user->id)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        $debits = \App\Models\FinancialTransaction::where('user_id', $user->id)
            ->where('transaction_type', 'debit')
            ->sum('amount');

        $availableBalance = round($credits - $debits, 2);

        if ($availableBalance <= 0) {
            return back()->with('error', 'You have no available funds to request.');
        }

        if (empty($user->bank_name) || empty($user->account_holder_name) || empty($user->iban)) {
            return back()->with('error', 'Bank details are incomplete. Please update your profile first.');
        }

        // Notify Admins
        $admins = \App\Models\User::where('role', 'super_admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\PayoutRequestedNotification($user, $availableBalance));
        }

        return back()->with('success', 'Your payout request of ' . number_format($availableBalance, 2) . ' JOD has been sent to the administration team successfully!');
    }
}
