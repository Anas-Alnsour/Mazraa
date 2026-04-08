<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function index()
    {
        $adminId = Auth::id();

        $farmProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->whereIn('reference_type', ['farm_booking', 'booking'])
            ->sum('amount');

        $transportProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'transport')
            ->sum('amount');

        $supplyProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'supply_order')
            ->sum('amount');

        $totalCombinedProfit = $farmProfit + $transportProfit + $supplyProfit;

        $recentTransactions = FinancialTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.financials', compact(
            'farmProfit', 
            'transportProfit', 
            'supplyProfit', 
            'totalCombinedProfit', 
            'recentTransactions'
        ));
    }
}
