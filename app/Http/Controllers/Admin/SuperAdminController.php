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
     * Display the main dashboard (Financials & Map)
     */
    public function index(Request $request)
    {
        // Time filter setup
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

        // Aggregate Financial Data based on timeframe
        $farmCommissions = FarmBooking::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $transportCommissions = Transport::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $supplyCommissions = SupplyOrder::where('created_at', '>=', $queryDate)->sum('commission_amount');

        // Summing up total platform income (commissions combined)
        $totalCommissions = $farmCommissions + $transportCommissions + $supplyCommissions;

        $taxRate = 0.16; // 16% VAT
        $taxes = $totalCommissions * $taxRate;

        // Total gross income processed through the platform (استخدام الأعمدة الصحيحة حسب توثيقك)
        $totalGrossIncome = FarmBooking::where('created_at', '>=', $queryDate)->sum('total_price')
+ Transport::where('created_at', '>=', $queryDate)->sum('price')
+ SupplyOrder::where('created_at', '>=', $queryDate)->sum('total_price') ;

        $financials = [
            'total_income' => $totalGrossIncome,
            'total_commissions' => $totalCommissions,
            'farm_commissions' => $farmCommissions,
            'transport_commissions' => $transportCommissions,
            'supply_commissions' => $supplyCommissions,
            'taxes' => $taxes,
        ];

        // Fetch verified farms for the map
        $verifiedFarms = Farm::where('is_approved', true)->get(['id', 'name', 'latitude', 'longitude', 'location']);

        $totalUsers = User::count();

        return view('admin.dashboard.index', compact('financials', 'verifiedFarms', 'timeframe', 'totalUsers'));
    }

    /**
     * Display pending farm verifications
     */
    public function verifications()
    {
        // Fetch farms that are NOT approved yet (استخدام علاقة owner الصحيحة)
        $pendingFarms = Farm::where('is_approved', false)->with('owner')->get();

        return view('admin.dashboard.verifications', compact('pendingFarms'));
    }

   /**
     * Handle farm verification actions (Approve/Reject)
     */
    public function handleVerification(Request $request, Farm $farm)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        if ($validated['action'] === 'approve') {
            $farm->update(['is_approved' => true]);
            $message = 'Farm approved successfully.';
        } else {
            $farm->delete();
            $message = 'Farm rejected and completely removed from the system.';
        }

        // التوجيه لصفحة الاعتمادات زي ما كانت عندك بالأساس
        return redirect()->route('admin.verifications')->with('success', $message);
    }

    /**
     * Display system management settings
     */
    public function system()
    {
        $users = User::all();
        $defaultCommission = config('app.default_commission_rate', 10);

        return view('admin.dashboard.system', compact('users', 'defaultCommission'));
    }

    /**
     * Update system settings
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
