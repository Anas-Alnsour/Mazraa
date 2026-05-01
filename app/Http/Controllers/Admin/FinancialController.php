<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FinancialController extends Controller
{
    public function index(Request $request)
    {
        $adminId = Auth::id();
        $filter = $request->input('filter', 'all');

        // 1. دالة ذكية لتطبيق الفلتر بشكل صارم حسب نوعه
        $applyDateFilter = function ($query) use ($filter) {
    switch ($filter) {
        case 'daily':
            $query->whereDate('created_at', today());
            break;

        case 'weekly':
            // تعديل البداية لتكون السبت والنهاية الجمعة
            $start = now()->startOfWeek(Carbon::SATURDAY);
            $end = now()->endOfWeek(Carbon::FRIDAY);
            
            $query->whereBetween('created_at', [$start, $end]);
            break;

        case 'monthly':
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            break;

        case 'yearly':
            $query->whereYear('created_at', now()->year);
            break;
    }
};

        // 2. تطبيق الفلتر على الأرباح الكلية
        $netProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)->sum('amount');

        $ownerShare = FinancialTransaction::whereHas('user', fn($q) => $q->where('role', 'farm_owner'))
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)->sum('amount');

        $providerShare = FinancialTransaction::whereHas('user', fn($q) => $q->whereIn('role', ['supply_company', 'transport_company']))
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)->sum('amount');

        $totalRevenue = $netProfit + $ownerShare + $providerShare;

        // 3. تطبيق الفلتر على التفاصيل الجانبية
        $farmProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->whereIn('reference_type', ['farm_booking', 'booking'])
            ->tap($applyDateFilter)->sum('amount');

        $transportProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'transport')
            ->tap($applyDateFilter)->sum('amount');

        $supplyProfit = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->where('reference_type', 'supply_order')
            ->tap($applyDateFilter)->sum('amount');

        // 4. تطبيق الفلتر على جدول العمليات
        $txQuery = FinancialTransaction::with('user')->tap($applyDateFilter);

        // تصدير ملف الإكسل
        if ($request->input('export') === 'csv') {
            return $this->exportToCsv($txQuery->orderBy('created_at', 'desc')->get());
        }

        // جلب البيانات للجدول
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
