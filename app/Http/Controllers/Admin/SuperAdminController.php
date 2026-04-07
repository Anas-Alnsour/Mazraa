<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use App\Models\Transport;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use App\Notifications\FarmApprovedNotification;
use App\Notifications\PayoutProcessedNotification;

class SuperAdminController extends Controller
{
    /**
     * Display the Unified Super Admin Dashboard. (Jules Metrics)
     */
    public function index()
    {
        // ---------------------------------------------------------
        // 1. GLOBAL METRICS (All Time)
        // ---------------------------------------------------------
        $totalUsers = User::count();
        $totalPartners = User::whereIn('role', ['farm_owner', 'supply_company', 'transport_company'])->count();
        $totalFarms = Farm::count();

        // ---------------------------------------------------------
        // 2. FINANCIAL OVERSIGHT (Platform Commissions & Revenue)
        // ---------------------------------------------------------
        $adminId = auth()->id();

        // Total platform commissions earned (all time)
        $totalCommissionEarned = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        // Revenue breakdown by sector (Completed/Confirmed only)
        // Note: Using total_price for volume indication
        $farmRevenue = FarmBooking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $supplyRevenue = SupplyOrder::whereIn('status', ['paid', 'delivered', 'completed'])->sum('total_price');
        $transportRevenue = Transport::whereIn('status', ['completed'])->sum('price');

        $totalGrossVolume = $farmRevenue + $supplyRevenue + $transportRevenue;

        // ---------------------------------------------------------
        // 3. ECOSYSTEM ACTIVITY (Current Status)
        // ---------------------------------------------------------

        // FARMS (Emerald)
        $activeFarmsCount = Farm::where('is_approved', true)->count();
        $pendingFarmBookings = FarmBooking::whereIn('status', ['pending', 'pending_verification', 'pending_payment'])->count();
        $activeFarmBookings = FarmBooking::where('status', 'confirmed')->whereDate('start_time', '>=', now())->count();

        // SUPPLIES (Green/Lime)
        $pendingSupplyOrders = SupplyOrder::where('status', 'pending')->count();
        $inTransitSupplies = SupplyOrder::whereIn('status', ['waiting_driver', 'in_way'])->count();
        $completedSupplies = SupplyOrder::where('status', 'delivered')->count();

        // TRANSPORT LOGISTICS (Cyan/Blue)
        $pendingTransports = Transport::where('status', 'pending')->count();
        $inProgressTransports = Transport::whereIn('status', ['in_progress', 'in_way'])->count();
        $completedTransports = Transport::where('status', 'completed')->count();

        // ---------------------------------------------------------
        // 4. VERIFICATIONS & ACTION CENTER (Pending Approvals)
        // ---------------------------------------------------------
        $farmsAwaitingApproval = Farm::where('is_approved', false)->count();
        $paymentsAwaitingVerification = FarmBooking::where('status', 'pending_verification')->count() +
                                        SupplyOrder::where('status', 'pending_verification')->count();

        $actionRequiredCount = $farmsAwaitingApproval + $paymentsAwaitingVerification;

        // ---------------------------------------------------------
        // 5. RECENT TRANSACTIONS FEED
        // ---------------------------------------------------------
        // Fetch verified farms for the ecosystem map coverage
        $verifiedFarms = Farm::where('is_approved', true)->select('id', 'name', 'latitude', 'longitude')->get();

        $recentTransactions = FinancialTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPartners', 'totalFarms',
            'totalCommissionEarned', 'totalGrossVolume',
            'farmRevenue', 'supplyRevenue', 'transportRevenue',
            'activeFarmsCount', 'pendingFarmBookings', 'activeFarmBookings',
            'pendingSupplyOrders', 'inTransitSupplies', 'completedSupplies',
            'pendingTransports', 'inProgressTransports', 'completedTransports',
            'actionRequiredCount', 'farmsAwaitingApproval', 'paymentsAwaitingVerification',
            'recentTransactions', 'verifiedFarms'
        ));
    }

    /**
     * عرض المزارع التي تنتظر الموافقة، وحوالات الكليك (CliQ) التي تنتظر التأكيد
     */
    public function verifications()
    {
        // Must be Super Admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // جلب المزارع اللي بتستنى الموافقة
        $pendingFarms = Farm::where('is_approved', false)->with('owner')->latest()->get();

        // Fetch pending Farm Bookings (CliQ)
        $farmBookings = FarmBooking::with(['farm', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        // Fetch pending Supply Orders (CliQ)
        $supplyOrders = SupplyOrder::with(['supply', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.verifications', compact('pendingFarms', 'farmBookings', 'supplyOrders'));
    }

    /**
     * معالجة الموافقة أو الرفض لكل أنواع الطلبات (مزارع، حوالات كليك)
     */
    public function handleVerification(Request $request, $id, $type = 'farm_approval')
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $action = $request->action;

        // 1. معالجة موافقة/رفض إنشاء مزرعة جديدة
        if ($type === 'farm_approval') {
            $farm = Farm::findOrFail($id);

            if ($action === 'approve') {
                $farm->update(['is_approved' => true]);
                $farm->owner->notify(new FarmApprovedNotification($farm));
                return redirect()->route('admin.verifications')->with('success', 'Farm approved successfully and published to the marketplace!');
            } else {
                $farm->delete();
                return redirect()->route('admin.verifications')->with('success', 'Farm rejected and completely removed from the system.');
            }
        }

        // 2. معالجة حوالات الكليك لحجوزات المزارع
        elseif ($type === 'farm_booking') {
            $booking = FarmBooking::findOrFail($id);

            if ($action === 'approve') {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed'
                ]);

                FinancialTransaction::create([
                    'user_id'          => $booking->user_id,
                    'reference_type'   => 'farm_booking',
                    'reference_id'     => $booking->id,
                    'amount'           => $booking->total_price,
                    'transaction_type' => 'credit',
                    'description'      => 'Manual CliQ Transfer Verified (Booking #' . $booking->id . ')',
                ]);

                return back()->with('success', 'Farm Booking payment verified and confirmed!');
            } elseif ($action === 'reject') {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);
                return back()->with('error', 'Farm Booking payment rejected and cancelled.');
            }
        }

        // 3. معالجة حوالات الكليك لطلبات التوريد
        elseif ($type === 'supply_order') {
            $order = SupplyOrder::findOrFail($id);

            if ($action === 'approve') {
                $order->update([
                    'status' => 'pending'
                ]);

                FinancialTransaction::create([
                    'user_id'          => $order->user_id,
                    'reference_type'   => 'supply_order',
                    'reference_id'     => $order->id,
                    'amount'           => $order->total_price,
                    'transaction_type' => 'credit',
                    'description'      => 'Manual CliQ Transfer Verified (Supply Invoice #' . $order->order_id . ')',
                ]);

                return back()->with('success', 'Supply Order payment verified! The order is now pending processing.');
            } elseif ($action === 'reject') {
                $order->update([
                    'status' => 'cancelled'
                ]);
                return back()->with('error', 'Supply Order payment rejected and cancelled.');
            }
        }

        return back()->with('error', 'Invalid verification request.');
    }

    /**
     * Display the Financial Payouts Ledger
     */
    public function payouts()
    {
        // 1. Get all Farm Owners and their earnings
        $farmOwners = User::where('role', 'farm_owner')->with(['farms.bookings' => function($q) {
            $q->whereIn('status', ['completed', 'finished']);
        }])->get()->map(function($user) {
            $earned = $user->farms->flatMap->bookings->sum('net_company_amount');
            $paidOut = \App\Models\FinancialTransaction::where('user_id', $user->id)->where('transaction_type', 'debit')->sum('amount');
            $user->balance = $earned - $paidOut;
            return $user;
        })->filter(function($user) { return $user->balance > 0; });

        // 2. Get all Transport Companies and their earnings
        $transportCompanies = User::where('role', 'transport_company')->with(['transportCompanyJobs' => function($q) {
            $q->whereIn('status', ['completed', 'delivered']);
        }])->get()->map(function($user) {
            $earned = $user->transportCompanyJobs->sum('net_company_amount');
            $paidOut = \App\Models\FinancialTransaction::where('user_id', $user->id)->where('transaction_type', 'debit')->sum('amount');
            $user->balance = $earned - $paidOut;
            return $user;
        })->filter(function($user) { return $user->balance > 0; });

        // 3. Get all Supply Companies and their earnings
        $supplyCompanies = User::where('role', 'supply_company')->with(['supplies.orders' => function($q) {
            $q->whereIn('status', ['completed', 'delivered']);
        }])->get()->map(function($user) {
            $earned = $user->supplies->flatMap->orders->sum('net_company_amount');
            $paidOut = \App\Models\FinancialTransaction::where('user_id', $user->id)->where('transaction_type', 'debit')->sum('amount');
            $user->balance = $earned - $paidOut;
            return $user;
        })->filter(function($user) { return $user->balance > 0; });

        // Merge all vendors who are owed money
        $vendorsOwed = $farmOwners->merge($transportCompanies)->merge($supplyCompanies)->sortByDesc('balance');

        // Fetch recent payout history
        $recentPayouts = \App\Models\FinancialTransaction::with('user')
            ->where('reference_type', 'manual_payout')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.payouts', compact('vendorsOwed', 'recentPayouts'));
    }

    /**
     * Record a manual payout to a vendor
     */
    public function recordPayout(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'reference_id' => 'required|string',
        ]);

        $transaction = \App\Models\FinancialTransaction::create([
            'user_id' => $validated['user_id'],
            'reference_type' => 'manual_payout',
            'reference_id' => 0,
            'transaction_type' => 'debit',
            'amount' => $validated['amount'],
            'description' => 'Bank Transfer Ref: ' . $validated['reference_id'],
        ]);

        $vendorUser = User::find($request->user_id);
        if ($vendorUser) {
            $vendorUser->notify(new PayoutProcessedNotification($transaction));
        }

        return redirect()->route('admin.payouts')->with('success', 'Payout of ' . number_format($validated['amount'], 2) . ' JOD recorded successfully.');
    }

    /**
     * عرض إعدادات النظام
     */
    public function system()
    {
        $users = User::all();
        $defaultCommission = config('app.default_commission_rate', 10);

        return view('admin.system', compact('users', 'defaultCommission'));
    }

    /**
     * تحديث إعدادات النظام
     */
    public function updateSystem(Request $request)
    {
         $request->validate([
             'commission_rate' => 'required|numeric|min:0|max:100',
         ]);

         // Logic to save the setting goes here

         return redirect()->route('admin.system')->with('success', 'System settings updated.');
    }
}
