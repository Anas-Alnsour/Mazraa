<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\User;
use App\Models\FarmBooking;
use App\Models\Transport;
use App\Models\SupplyOrder;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * عرض الإحصائيات والأرباح للمنصة (النسخة المدمجة)
     */
    public function index(Request $request)
    {
        // 1. إعداد فلتر الوقت
        $timeframe = $request->query('timeframe', 'month');
        $queryDate = Carbon::now();

        if ($timeframe === 'week') {
            $queryDate->subWeek();
        } elseif ($timeframe === 'month') {
            $queryDate->subMonth();
        } elseif ($timeframe === 'year') {
            $queryDate->subYear();
        } elseif ($timeframe === 'all') {
            $queryDate = Carbon::create(2000, 1, 1);
        }

        // 2. حساب عمولات المنصة (من كودك + الجديد)
        $farmCommissions = FarmBooking::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $transportCommissions = Transport::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $supplyCommissions = SupplyOrder::where('created_at', '>=', $queryDate)->sum('commission_amount');

        $totalCommissions = $farmCommissions + $transportCommissions + $supplyCommissions;

        // حساب الضرائب (من كودك الأصلي)
        $taxRate = 0.16; // 16% VAT
        $taxes = $totalCommissions * $taxRate;

        // 3. حساب إجمالي الأموال (Gross Volume)
        $totalGrossIncome = FarmBooking::where('created_at', '>=', $queryDate)->sum('total_price')
            + Transport::where('created_at', '>=', $queryDate)->sum('price')
            + SupplyOrder::where('created_at', '>=', $queryDate)->sum('total_price');

        // تجهيز المصفوفة المالية المدمجة
        $financials = [
            'gross_volume' => $totalGrossIncome, // اسم جديد من واجهة جولز
            'total_income' => $totalGrossIncome, // اسم قديم من كودك
            'net_revenue' => $totalCommissions,  // اسم جديد من واجهة جولز
            'total_commissions' => $totalCommissions, // اسم قديم من كودك
            'farm_rev' => $farmCommissions, // اسم جديد
            'farm_commissions' => $farmCommissions, // اسم قديم
            'transport_rev' => $transportCommissions, // اسم جديد
            'transport_commissions' => $transportCommissions, // اسم قديم
            'supply_rev' => $supplyCommissions, // اسم جديد
            'supply_commissions' => $supplyCommissions, // اسم قديم
            'taxes' => $taxes, // من كودك
        ];

        // 4. إحصائيات النظام (مدمجة)
        $totalUsers = User::count();
        $systemStats = [
            'total_users' => $totalUsers,
            'total_farms' => Farm::count(),
            'approved_farms' => Farm::where('is_approved', true)->count(),
            'pending_farms' => Farm::where('is_approved', false)->count(),
        ];

        // جلب المزارع المعتمدة للخريطة (من كودك الأصلي)
        $verifiedFarms = Farm::where('is_approved', true)->get(['id', 'name', 'latitude', 'longitude', 'location']);

        return view('admin.dashboard.index', compact('financials', 'timeframe', 'systemStats', 'totalUsers', 'verifiedFarms'));
    }

    /**
     * عرض المزارع التي تنتظر الموافقة
     */
    public function verifications()
    {
        $pendingFarms = Farm::where('is_approved', false)->with('owner')->latest()->get();
        return view('admin.dashboard.verifications', compact('pendingFarms'));
    }

    /**
     * معالجة الموافقة أو الرفض
     */
    public function handleVerification(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($validated['action'] === 'approve') {
            $farm->update(['is_approved' => true]);
            $message = 'Farm approved successfully and published to the marketplace!';
        } else {
            $farm->delete();
            $message = 'Farm rejected and completely removed from the system.';
        }

        return redirect()->route('admin.verifications')->with('success', $message);
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

        return view('admin.dashboard.payouts', compact('vendorsOwed', 'recentPayouts'));
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

        \App\Models\FinancialTransaction::create([
            'user_id' => $validated['user_id'],
            'reference_type' => 'manual_payout',
            'reference_id' => 0,
            'transaction_type' => 'debit',
            'amount' => $validated['amount'],
            'description' => 'Bank Transfer Ref: ' . $validated['reference_id'],
        ]);

        return redirect()->route('admin.payouts')->with('success', 'Payout of ' . number_format($validated['amount'], 2) . ' JOD recorded successfully.');
    }

    /**
     * عرض إعدادات النظام (من كودك الأصلي)
     */
    public function system()
    {
        $users = User::all();
        $defaultCommission = config('app.default_commission_rate', 10);

        return view('admin.dashboard.system', compact('users', 'defaultCommission'));
    }

    /**
     * تحديث إعدادات النظام (من كودك الأصلي)
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
