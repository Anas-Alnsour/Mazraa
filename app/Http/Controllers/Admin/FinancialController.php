<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Auth::id();
        $filter = $request->query('filter', 'all');
        $startDate = null;

        switch ($filter) {
            case 'daily':
                $startDate = now()->startOfDay();
                break;
            case 'weekly':
                $startDate = now()->startOfWeek();
                break;
            case 'monthly':
                $startDate = now()->startOfMonth();
                break;
            case 'yearly':
                $startDate = now()->startOfYear();
                break;
        }

        // 1. Mazraa Net Profit (Admin's Credits)
        $adminCreditsQuery = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit');
        if ($startDate) {
            $adminCreditsQuery->where('created_at', '>=', $startDate);
        }
        $netProfit = $adminCreditsQuery->sum('amount');

        // 2. Owner Share (Credits to Farm Owners)
        $ownerShareQuery = FinancialTransaction::whereHas('user', function($q) {
                $q->where('role', 'farm_owner');
            })
            ->where('transaction_type', 'credit');
        if ($startDate) {
            $ownerShareQuery->where('created_at', '>=', $startDate);
        }
        $ownerShare = $ownerShareQuery->sum('amount');

        // 3. Provider Share (Credits to Supply and Transport Companies)
        $providerShareQuery = FinancialTransaction::whereHas('user', function($q) {
                $q->whereIn('role', ['supply_company', 'transport_company']);
            })
            ->where('transaction_type', 'credit');
        if ($startDate) {
            $providerShareQuery->where('created_at', '>=', $startDate);
        }
        $providerShare = $providerShareQuery->sum('amount');

        // 4. Total Revenue (The sum of all payouts and platform fees)
        $totalRevenue = $netProfit + $ownerShare + $providerShare;

        // Breakdowns for the legacy variables (to avoid breaking the view)
        $farmProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->whereIn('reference_type', ['farm_booking', 'booking'])
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->sum('amount');

        $transportProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'transport')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->sum('amount');

        $supplyProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'supply_order')
            ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate))
            ->sum('amount');

        // Query for transaction stream
        $txQuery = FinancialTransaction::with('user');
        if ($startDate) {
            $txQuery->where('created_at', '>=', $startDate);
        }

        // Handle CSV Export
        if ($request->query('export') === 'csv') {
            return $this->exportToCsv($txQuery->orderBy('created_at', 'desc')->get());
        }

        $recentTransactions = $txQuery->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.financials', compact(
            'farmProfit', 
            'transportProfit', 
            'supplyProfit', 
            'totalRevenue',
            'netProfit',
            'ownerShare',
            'providerShare',
            'recentTransactions',
            'filter'
        ));
    }

    private function exportToCsv($transactions)
    {
        $fileName = 'financial_report_' . now()->format('Y-m-d_H-i') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, ['Timestamp', 'Entity', 'Role', 'Reference', 'Description', 'Amount (JOD)']);

            foreach ($transactions as $tx) {
                $amount = ($tx->transaction_type === 'credit' ? '+' : '-') . number_format($tx->amount, 2);
                $reference = str_replace('_', ' ', $tx->reference_type) . " #" . $tx->reference_id;
                
                fputcsv($file, [
                    $tx->created_at->format('Y-m-d H:i:s'),
                    $tx->user->name ?? 'System',
                    $tx->user->role ?? 'Core',
                    strtoupper($reference),
                    $tx->description,
                    $amount
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
