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
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\NewBookingReceivedNotification;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    /**
     * Display the Unified Super Admin Dashboard.
     */
    public function index()
    {
        // ---------------------------------------------------------
        // 1. GLOBAL METRICS (All Time)
        // ---------------------------------------------------------
        $totalUsers    = User::count();
        $totalPartners = User::whereIn('role', ['farm_owner', 'supply_company', 'transport_company'])->count();
        $totalFarms    = Farm::count();

        // ---------------------------------------------------------
        // 2. FINANCIAL OVERSIGHT — ONLY Admin's credit transactions
        // ---------------------------------------------------------
        $adminId = auth()->id();

        // Total platform commissions earned (only Admin credit entries)
        $totalCommissionEarned = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        // Revenue breakdown by sector (Completed/Confirmed only)
        $farmRevenue      = FarmBooking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $supplyRevenue    = SupplyOrder::whereIn('status', ['delivered', 'completed'])->sum('total_price');
        $transportRevenue = Transport::where('status', 'completed')->sum('price');

        $totalGrossVolume = $farmRevenue + $supplyRevenue + $transportRevenue;

        // ---------------------------------------------------------
        // 3. ECOSYSTEM ACTIVITY (Current Status)
        // ---------------------------------------------------------
        $activeFarmsCount    = Farm::where('is_approved', true)->count();
        $pendingFarmBookings = FarmBooking::whereIn('status', ['pending', 'pending_verification', 'pending_payment'])->count();
        $activeFarmBookings  = FarmBooking::where('status', 'confirmed')->whereDate('start_time', '>=', now())->count();

        $pendingSupplyOrders = SupplyOrder::where('status', 'pending')->count();
        $inTransitSupplies   = SupplyOrder::whereIn('status', ['waiting_driver', 'in_way'])->count();
        $completedSupplies   = SupplyOrder::where('status', 'delivered')->count();

        $pendingTransports    = Transport::where('status', 'pending')->count();
        $inProgressTransports = Transport::whereIn('status', ['in_progress', 'in_way'])->count();
        $completedTransports  = Transport::where('status', 'completed')->count();

        // ---------------------------------------------------------
        // 4. VERIFICATIONS & ACTION CENTER
        // ---------------------------------------------------------
        $farmsAwaitingApproval      = Farm::where('is_approved', false)->count();
        $paymentsAwaitingVerification = FarmBooking::where('status', 'pending_verification')->count()
                                      + SupplyOrder::where('status', 'pending_verification')->count();
        $actionRequiredCount = $farmsAwaitingApproval + $paymentsAwaitingVerification;

        // ---------------------------------------------------------
        // 5. RECENT TRANSACTIONS FEED
        // ---------------------------------------------------------
        $verifiedFarms = Farm::where('is_approved', true)->select('id', 'name', 'latitude', 'longitude')->get();

        // ✅ FIX: Eager loaded 'user' to prevent N+1 query issue in the dashboard recent transactions loop
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
     * Show pending farm approvals and CliQ payment verifications.
     */
    public function verifications()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        // Already efficiently loaded with relationships
        $pendingFarms = Farm::where('is_approved', false)->with('owner')->latest()->get();

        $farmBookings = FarmBooking::with(['farm', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        $supplyOrders = SupplyOrder::with(['supply', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->get();

        return view('admin.verifications', compact('pendingFarms', 'farmBookings', 'supplyOrders'));
    }

    /**
     * Handle approve/reject for farms and CliQ payment verifications.
     */
    public function handleVerification(Request $request, $id, $type = 'farm_approval')
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate(['action' => 'required|in:approve,reject']);
        $action = $request->action;

        // ── 1. Farm Approval ─────────────────────────────────────
        if ($type === 'farm_approval') {
            $farm = Farm::findOrFail($id);
            if ($action === 'approve') {
                $farm->update(['is_approved' => true]);
                $farm->owner->notify(new FarmApprovedNotification($farm));
                return redirect()->route('admin.verifications')->with('success', 'Farm approved and published!');
            }
            $farm->delete();
            return redirect()->route('admin.verifications')->with('success', 'Farm rejected and removed.');
        }

        // ── 2. CliQ for Booking ──────────────────────────────
        elseif ($type === 'booking') {
            // ✅ التعديل: جلبنا الـ user مع الـ farm لنستطيع إرسال الإشعار له
            $booking = FarmBooking::with(['farm', 'user'])->findOrFail($id);

            if ($action === 'approve') {
                $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);

                // ✅ التعديل الأهم: إرسال الإشعارات عند الموافقة اليدوية على CliQ
                if ($booking->user) {
                    $booking->user->notify(new BookingConfirmedNotification($booking));
                }

                if ($booking->farm && $booking->farm->owner_id) {
                    $owner = User::find($booking->farm->owner_id);
                    if ($owner) {
                        $owner->notify(new NewBookingReceivedNotification($booking));
                    }
                }

                return back()->with('success', 'Farm Booking CliQ payment verified and notifications sent!');
            }

            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return back()->with('error', 'Farm Booking CliQ payment rejected and cancelled.');
        }

        // ── 3. CliQ for Supply Order ──────────────────────────────
        elseif ($type === 'supply_order') {
            $order = SupplyOrder::with(['supply', 'user'])->findOrFail($id);

            if ($action === 'approve') {
                $order->update(['status' => 'pending']);

                // (اختياري) يمكنك إضافة إشعار هنا لشركة التوريد إذا كان لديك كلاس إشعار مخصص لهم

                return back()->with('success', 'Supply Order CliQ payment verified!');
            }

            $order->update(['status' => 'cancelled']);
            return back()->with('error', 'Supply Order CliQ payment rejected and cancelled.');
        }

        return back()->with('error', 'Invalid verification request.');
    }

    /**
     * Display the Financial Payouts Ledger.
     * Uses aggregate DB queries — no memory-heavy eager loading.
     */
    public function payouts()
    {
        $vendorRoles = ['farm_owner', 'supply_company', 'transport_company'];

        $vendors = User::whereIn('role', $vendorRoles)
            ->with(['financialTransactions' => function ($q) {
                $q->select('user_id', 'transaction_type', 'amount', 'created_at');
            }])
            ->get()
            ->map(function ($user) {
                $credits  = $user->financialTransactions->where('transaction_type', 'credit')->sum('amount');
                $debits   = $user->financialTransactions->where('transaction_type', 'debit')->sum('amount');
                $user->balance = round($credits - $debits, 2);
                return $user;
            })
            ->filter(fn($user) => $user->balance > 0)
            ->sortByDesc('balance')
            ->values();

        // ✅ FIX: Eager loaded 'user' to prevent N+1 query issue in recent payouts loop
        $recentPayouts = FinancialTransaction::with('user')
            ->where('reference_type', 'manual_payout')
            ->latest()
            ->take(20)
            ->get();

        // Alias for view compatibility
        $vendorsOwed = $vendors;

        return view('admin.payouts', compact('vendorsOwed', 'recentPayouts'));
    }

    /**
     * Record a manual payout to a vendor.
     * Enforces balance guard: cannot pay more than available balance.
     */
    public function recordPayout(Request $request)
    {
        $validated = $request->validate([
            'user_id'      => 'required|exists:users,id',
            'amount'       => 'required|numeric|min:0.01',
            'reference_id' => 'required|string|max:255',
        ]);

        $userId = $validated['user_id'];
        $amount = (float) $validated['amount'];

        // ── BALANCE GUARD: prevent overpayment ───────────────────
        $credits = FinancialTransaction::where('user_id', $userId)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        $debits = FinancialTransaction::where('user_id', $userId)
            ->where('transaction_type', 'debit')
            ->sum('amount');

        $availableBalance = round($credits - $debits, 2);

        if ($amount > $availableBalance) {
            return back()
                ->withInput()
                ->with('error', "Payout rejected: requested amount ({$amount} JOD) exceeds available balance ({$availableBalance} JOD).");
        }

        // Record the debit transaction
        $transaction = FinancialTransaction::create([
            'user_id'          => $userId,
            'reference_type'   => 'manual_payout',
            'reference_id'     => 0,
            'transaction_type' => 'debit',
            'amount'           => $amount,
            'description'      => 'Bank Transfer Ref: ' . $validated['reference_id'],
        ]);

        $vendorUser = User::find($userId);
        if ($vendorUser) {
            $vendorUser->notify(new PayoutProcessedNotification($transaction));
        }

        return redirect()->route('admin.payouts')
            ->with('success', 'Payout of ' . number_format($amount, 2) . ' JOD recorded successfully for ' . ($vendorUser->name ?? 'Vendor') . '.');
    }

    /**
     * Display System Settings page.
     */
    public function system()
    {
        // جلب المستخدمين مع التقسيم ليعمل الـ Pagination في الـ Blade
        $users = User::latest()->paginate(10);

        // تأكد من جلب قيمة العمولات من الإعدادات إذا كانت مخزنة في الداتابيز
        $defaultCommission = config('mazraa.commission_rate', 10);

        return view('admin.system', compact('users', 'defaultCommission'));
    }

    /**
     * Persist system settings to a local JSON config file.
     * (No settings DB table exists; JSON file is the safe fallback.)
     */
    public function updateSystem(Request $request)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settingsPath = storage_path('app/system_settings.json');

        // Load existing settings or start fresh
        $settings = file_exists($settingsPath)
            ? json_decode(file_get_contents($settingsPath), true) ?? []
            : [];

        $settings['default_commission_rate'] = (float) $request->commission_rate;
        $settings['updated_at']              = now()->toDateTimeString();
        $settings['updated_by']              = auth()->id();

        file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('admin.system')
            ->with('success', 'System settings updated. New commission rate: ' . $request->commission_rate . '%');
    }

    /**
     * Helper: Read the persisted commission rate (falls back to config/env).
     */
    private function getCommissionRate(): float
    {
        $settingsPath = storage_path('app/system_settings.json');
        if (file_exists($settingsPath)) {
            $settings = json_decode(file_get_contents($settingsPath), true);
            if (isset($settings['default_commission_rate'])) {
                return (float) $settings['default_commission_rate'];
            }
        }
        return (float) config('app.default_commission_rate', 10);
    }
}
