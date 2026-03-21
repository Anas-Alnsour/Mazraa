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
        }

        // Aggregate Financial Data based on timeframe
        $farmCommissions = FarmBooking::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $transportCommissions = Transport::where('created_at', '>=', $queryDate)->sum('commission_amount');
        $supplyCommissions = SupplyOrder::where('created_at', '>=', $queryDate)->sum('commission_amount');

        // Summing up total platform income (commissions combined)
        $totalCommissions = $farmCommissions + $transportCommissions + $supplyCommissions;

        // Assuming a fixed tax rate or calculation for demonstration; adjust to your exact schema if tax is stored per order
        $taxRate = 0.16; // e.g., 16% VAT
        $taxes = $totalCommissions * $taxRate;

        // Total gross income processed through the platform
        // 🛠️ تم التعديل هنا ليتوافق مع أسماء الأعمدة الفعلية في قاعدة البيانات
        $totalGrossIncome = FarmBooking::where('created_at', '>=', $queryDate)->sum('total_price')
            + Transport::where('created_at', '>=', $queryDate)->sum('price') // تم تعديل total_price إلى price
            + SupplyOrder::where('created_at', '>=', $queryDate)->sum('total_price');

        $financials = [
            'total_income' => $totalGrossIncome,
            'total_commissions' => $totalCommissions,
            'farm_commissions' => $farmCommissions,
            'transport_commissions' => $transportCommissions,
            'supply_commissions' => $supplyCommissions,
            'taxes' => $taxes,
        ];

        // Fetch verified farms for the map
        $verifiedFarms = Farm::where('is_approved', true)->get(['id', 'name', 'latitude', 'longitude']);

        return view('admin.dashboard.index', compact('financials', 'verifiedFarms', 'timeframe'));
    }

    /**
     * Display pending farm verifications
     */
    public function verifications()
    {
        // Fetch farms that are NOT approved yet
        // تم تغيير 'user' إلى 'owner' ليتوافق مع الأرجح في بناء الـ Farm model
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
            // الموافقة: تحويل الحالة إلى true
            $farm->update(['is_approved' => true]);
            $message = 'Farm approved successfully.';
        } else {
            // الرفض: حذف المزرعة بالكامل من النظام
            $farm->delete();
            $message = 'Farm rejected and completely removed from the system.';
        }

        return redirect()->route('admin.verifications')->with('success', $message);
    }
    /**
     * Display system management settings
     */
    public function system()
    {
        // Fetch users for system management (e.g., toggling account status)
        $users = User::all();
        // Placeholder for default commission setting
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
