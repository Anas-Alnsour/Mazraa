<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // 🚀 2. تحسين استعلامات الأرباح: دمج 4 استعلامات في استعلام واحد فقط باستخدام قاعدة البيانات
        $adminFinancials = FinancialTransaction::where('user_id', $adminId)
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)
            ->select(
                DB::raw('COALESCE(SUM(amount), 0) as netProfit'),
                DB::raw('COALESCE(SUM(CASE WHEN reference_type IN ("farm_booking", "booking") THEN amount ELSE 0 END), 0) as farmProfit'),
                DB::raw('COALESCE(SUM(CASE WHEN reference_type = "transport" THEN amount ELSE 0 END), 0) as transportProfit'),
                DB::raw('COALESCE(SUM(CASE WHEN reference_type = "supply_order" THEN amount ELSE 0 END), 0) as supplyProfit')
            )->first();

        $netProfit = $adminFinancials->netProfit;
        $farmProfit = $adminFinancials->farmProfit;
        $transportProfit = $adminFinancials->transportProfit;
        $supplyProfit = $adminFinancials->supplyProfit;

        // حساب حصص الشركاء (لا يمكن دمجها مع استعلام الآدمن لاختلاف الـ user_id)
        $ownerShare = FinancialTransaction::whereHas('user', fn($q) => $q->where('role', 'farm_owner'))
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)
            ->sum('amount');

        $providerShare = FinancialTransaction::whereHas('user', fn($q) => $q->whereIn('role', ['supply_company', 'transport_company']))
            ->where('transaction_type', 'credit')
            ->tap($applyDateFilter)
            ->sum('amount');

        $totalRevenue = $netProfit + $ownerShare + $providerShare;

        // 4. تطبيق الفلتر على جدول العمليات
        $txQuery = FinancialTransaction::with('user')->tap($applyDateFilter);

        // 🚀 3. تفكيك قنبلة التصدير: تمرير الـ Query Builder بدلاً من تمرير كل الداتا عبر get()
        if ($request->input('export') === 'csv') {
            return $this->exportToCsv($txQuery->orderBy('created_at', 'desc'));
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

    /**
     * 🚀 تم التعديل لتستقبل Query Builder وتستخدم Chunk لمنع الانهيار
     */
    private function exportToCsv($query)
    {
        $fileName = 'financial_report_' . now()->format('Y-m-d_H-i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility (دعم اللغة العربية)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['Timestamp', 'Entity', 'Role', 'Reference', 'Description', 'Amount (JOD)']);

            // 🚀 معالجة البيانات على دفعات (1000 سجل في كل دفعة)
            $query->chunk(1000, function ($transactions) use ($file) {
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
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
