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
        $adminId = auth()->id();

        // 🚀 1. تفكيك القنبلة النووية (Database Hammer)
        // دمج 15 استعلام في 3 استعلامات فقط باستخدام SQL Aggregate Functions لسرعة خيالية

        $farmStats = FarmBooking::select(
            DB::raw('SUM(CASE WHEN status IN ("confirmed", "completed") THEN total_price ELSE 0 END) as farmRevenue'),
            DB::raw('SUM(CASE WHEN status IN ("pending", "pending_verification", "pending_payment") THEN 1 ELSE 0 END) as pendingFarmBookings'),
            DB::raw('SUM(CASE WHEN status = "confirmed" AND start_time >= NOW() THEN 1 ELSE 0 END) as activeFarmBookings'),
            DB::raw('SUM(CASE WHEN status = "pending_verification" THEN 1 ELSE 0 END) as paymentsAwaitingVerificationFarms')
        )->first();

        $supplyStats = SupplyOrder::select(
            DB::raw('SUM(CASE WHEN status IN ("delivered", "completed") THEN total_price ELSE 0 END) as supplyRevenue'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pendingSupplyOrders'),
            DB::raw('SUM(CASE WHEN status IN ("waiting_driver", "in_way") THEN 1 ELSE 0 END) as inTransitSupplies'),
            DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as completedSupplies'),
            DB::raw('SUM(CASE WHEN status = "pending_verification" THEN 1 ELSE 0 END) as paymentsAwaitingVerificationSupplies')
        )->first();

        $transportStats = Transport::select(
            DB::raw('SUM(CASE WHEN status = "completed" THEN price ELSE 0 END) as transportRevenue'),
            DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pendingTransports'),
            DB::raw('SUM(CASE WHEN status IN ("in_progress", "in_way") THEN 1 ELSE 0 END) as inProgressTransports'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completedTransports')
        )->first();

        $userStats = User::select(
            DB::raw('COUNT(*) as totalUsers'),
            DB::raw('SUM(CASE WHEN role IN ("farm_owner", "supply_company", "transport_company") THEN 1 ELSE 0 END) as totalPartners')
        )->first();

        $farmCounts = Farm::select(
            DB::raw('COUNT(*) as totalFarms'),
            DB::raw('SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as activeFarmsCount'),
            DB::raw('SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as farmsAwaitingApproval')
        )->first();

        $totalCommissionEarned = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->sum('amount');

        // استخراج المتغيرات للواجهة
        $totalUsers = $userStats->totalUsers;
        $totalPartners = $userStats->totalPartners;

        $totalFarms = $farmCounts->totalFarms;
        $activeFarmsCount = $farmCounts->activeFarmsCount;
        $farmsAwaitingApproval = $farmCounts->farmsAwaitingApproval;

        $farmRevenue = $farmStats->farmRevenue;
        $supplyRevenue = $supplyStats->supplyRevenue;
        $transportRevenue = $transportStats->transportRevenue;
        $totalGrossVolume = $farmRevenue + $supplyRevenue + $transportRevenue;

        $pendingFarmBookings = $farmStats->pendingFarmBookings;
        $activeFarmBookings = $farmStats->activeFarmBookings;

        $pendingSupplyOrders = $supplyStats->pendingSupplyOrders;
        $inTransitSupplies = $supplyStats->inTransitSupplies;
        $completedSupplies = $supplyStats->completedSupplies;

        $pendingTransports = $transportStats->pendingTransports;
        $inProgressTransports = $transportStats->inProgressTransports;
        $completedTransports = $transportStats->completedTransports;

        $paymentsAwaitingVerification = $farmStats->paymentsAwaitingVerificationFarms + $supplyStats->paymentsAwaitingVerificationSupplies;
        $actionRequiredCount = $farmsAwaitingApproval + $paymentsAwaitingVerification;

        // جلب آخر 6 عمليات للواجهة مع منع الـ RAM Exhaustion
        $verifiedFarms = Farm::where('is_approved', true)->select('id', 'name', 'latitude', 'longitude')->get();
        $recentTransactions = FinancialTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(6)
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

        $pendingFarms = Farm::where('is_approved', false)
            ->with('owner')
            ->latest()
            ->paginate(5, ['*'], 'farms_page');

        $farmBookings = FarmBooking::with(['farm', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->paginate(5, ['*'], 'bookings_page');

        $supplyOrders = SupplyOrder::with(['supply', 'user'])
            ->where('status', 'pending_verification')
            ->orderBy('updated_at', 'asc')
            ->paginate(5, ['*'], 'supplies_page');

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
                if ($farm->owner) {
                    $farm->owner->notify(new FarmApprovedNotification($farm));
                }
                return redirect()->route('admin.verifications')->with('success', 'Farm approved and published!');
            }

            $farm->delete();
            return redirect()->route('admin.verifications')->with('success', 'Farm rejected and removed.');
        }

        // ── 2. CliQ for Booking ──────────────────────────────
        elseif ($type === 'farm_booking') {
            $booking = FarmBooking::with(['farm.owner', 'user'])->findOrFail($id);

            if ($action === 'approve') {
                DB::beginTransaction();
                try {
                    $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);

                    // 🚀 2. تفكيك قنبلة ضياع أموال CliQ (تسجيل المعاملات المالية للآدمن والمالك)
                    $adminId = auth()->id();
                    $adminCommission = $booking->commission_amount ?? ($booking->total_price * 0.10);
                    $ownerNetProfit = $booking->net_owner_amount ?? ($booking->total_price - $adminCommission);

                    // دفع عمولة المنصة
                    FinancialTransaction::create([
                        'user_id' => $adminId,
                        'amount' => $adminCommission,
                        'transaction_type' => 'credit',
                        'reference_type' => 'farm_booking',
                        'reference_id' => $booking->id,
                        'description' => "Platform Commission for CliQ Booking #{$booking->id}",
                    ]);

                    // دفع أرباح المالك
                    if ($booking->farm && $booking->farm->owner_id) {
                        FinancialTransaction::create([
                            'user_id' => $booking->farm->owner_id,
                            'amount' => $ownerNetProfit,
                            'transaction_type' => 'credit',
                            'reference_type' => 'farm_booking',
                            'reference_id' => $booking->id,
                            'description' => "Net Profit for CliQ Booking #{$booking->id}",
                        ]);
                    }

                    if ($booking->user) {
                        $booking->user->notify(new BookingConfirmedNotification($booking));
                    }

                    if ($booking->farm && $booking->farm->owner) {
                        $booking->farm->owner->notify(new NewBookingReceivedNotification($booking));
                    }

                    DB::commit();
                    return back()->with('success', 'Farm Booking CliQ payment verified and funds recorded!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->with('error', 'Error processing CliQ payment: ' . $e->getMessage());
                }
            }

            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            return back()->with('success', 'Farm Booking CliQ payment rejected and cancelled.');
        }

        // ── 3. CliQ for Supply Order ──────────────────────────────
        elseif ($type === 'supply_order') {
            $order = SupplyOrder::with(['supply', 'user'])->findOrFail($id);

            if ($action === 'approve') {
                // تحديث جميع المنتجات التابعة لنفس الفاتورة
                SupplyOrder::where('order_id', $order->order_id)->update(['status' => 'pending']);
                return back()->with('success', 'Supply Order CliQ payment verified!');
            }

            SupplyOrder::where('order_id', $order->order_id)->update(['status' => 'cancelled']);
            return back()->with('success', 'Supply Order CliQ payment rejected and cancelled.');
        }

        return back()->with('error', 'Invalid verification request type: ' . $type);
    }

    /**
     * Display the Financial Payouts Ledger.
     */
    public function payouts()
    {
        $vendorRoles = ['farm_owner', 'supply_company', 'transport_company'];

        $vendorsOwed = User::whereIn('role', $vendorRoles)
            ->select('users.*')
            ->selectRaw('(SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE user_id = users.id AND transaction_type = "credit") - (SELECT COALESCE(SUM(amount), 0) FROM financial_transactions WHERE user_id = users.id AND transaction_type = "debit") as balance')
            ->having('balance', '>', 0)
            ->orderByDesc('balance')
            ->paginate(10, ['*'], 'vendors_page');

        $recentPayouts = FinancialTransaction::with('user')
            ->where('reference_type', 'manual_payout')
            ->where('transaction_type', 'debit')
            ->latest()
            ->paginate(15, ['*'], 'payouts_page');

        return view('admin.payouts', compact('vendorsOwed', 'recentPayouts'));
    }

    /**
     * Record a manual payout to a vendor.
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
        $users = User::latest()->paginate(10);
        $defaultCommission = config('mazraa.commission_rate', 10);
        return view('admin.system', compact('users', 'defaultCommission'));
    }

    /**
     * Persist system settings to a local JSON config file.
     */
    public function updateSystem(Request $request)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settingsPath = storage_path('app/system_settings.json');

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
