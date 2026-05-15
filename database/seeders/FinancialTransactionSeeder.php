<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use Carbon\Carbon;
use App\Models\FinancialTransaction;

class FinancialTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تنظيف الجدول قبل البدء
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('financial_transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->error("لم يتم العثور على مدير (Admin)، يرجى تشغيل UserSeeder أولاً.");
            return;
        }

        $now = Carbon::now();
        $transactions = [];

        // --- الجزء الأول: أرباح حجوزات المزارع (بيانات حقيقية) ---
        $bookings = FarmBooking::with('farm.owner')->get();

        if ($bookings->isEmpty()) {
            $this->command->warn("لا يوجد حجوزات حقيقية، سيتم تخطي أرباح المزارع.");
        } else {
            foreach ($bookings as $booking) {
                $owner = $booking->farm->owner;
                if (!$owner) continue;

                $grossAmount = $booking->total_price;
                // حساب العمولة (من المزرعة أو 10% افتراضي)
                $commissionRate = ($booking->farm->commission_rate ?? 10) / 100;
                $commission = $grossAmount * $commissionRate;
                $netAmount = $grossAmount - $commission;

                // إيداع عمولة المنصة
                $transactions[] = [
                    'user_id' => $admin->id,
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'transaction_type' => 'credit',
                    'amount' => $commission,
                    'description' => "عمولة المنصة من حجز رقم #{$booking->id}",
                    'created_at' => $booking->created_at,
                    'updated_at' => $now,
                ];

                // إيداع صافي الربح لصاحب المزرعة
                $transactions[] = [
                    'user_id' => $owner->id,
                    'reference_type' => 'farm_booking',
                    'reference_id' => $booking->id,
                    'transaction_type' => 'credit',
                    'amount' => $netAmount,
                    'description' => "صافي أرباح الحجز رقم #{$booking->id}",
                    'created_at' => $booking->created_at,
                    'updated_at' => $now,
                ];
            }
        }

        // --- الجزء الثاني: أرباح طلبات التوريد (بيانات حقيقية) ---
        // $orders = SupplyOrder::all();

       // شحن العلاقات مع التأكد من جلب الحقول اللازمة
        $orders = SupplyOrder::with(['supply', 'user'])->get();

        if ($orders->isEmpty()) {
            $this->command->warn('لا توجد طلبات توريد لإنشاء عمليات مالية.');
            return;
        }

        foreach ($orders as $order) {
            // 1. عملية دفع من الزبون (المبلغ الكلي)
            FinancialTransaction::create([
                'user_id' => $order->user_id,
                'amount' => $order->total_price,
                'transaction_type' => 'debit',
                // 'status' => 'completed',
                'description' => "دفع قيمة طلب التوريد رقم: {$order->order_id}",
                'reference_id' => $order->id,
                'reference_type' => SupplyOrder::class,
            ]);

            // 2. عملية إيداع للمورد (الصافي)
            // ملاحظة: بما أن company_id حذف من جدول supply، سنفترض وجود مورد افتراضي 
            // أو يمكنك استبداله بـ $order->supply->supplier_id إذا قمت بتغيير الاسم
            $supplierId = 1; // معرّف مورد افتراضي (أو HQ) لتجنب توقف الكود

            if ($order->net_company_amount > 0) {
                FinancialTransaction::create([
                    'user_id' => $supplierId, 
                    'amount' => $order->net_company_amount,
                    'transaction_type' => 'credit',
                    // 'status' => 'completed',
                    'description' => "صافي أرباح المورد للطلب رقم: {$order->order_id}",
                    'reference_id' => $order->id,
                    'reference_type' => SupplyOrder::class,
                ]);
            }
        }

        // --- الجزء الثالث: سحب الأرباح (Debit) - من كودك الأول ---
        // سنأخذ عينة من المستخدمين ونحاكي عملية تحويل بنكي لهم (خصم من رصيدهم في التطبيق)
        $owners = User::where('role', 'farm_owner')->take(2)->get();
        foreach ($owners as $owner) {
            $transactions[] = [
                'user_id' => $owner->id,
                'reference_type' => 'farm_booking',
                'reference_id' => rand(100, 999), // رقم مرجعي للتحويل
                'transaction_type' => 'debit',
                'amount' => rand(50, 100),
                'description' => "تحويل بنكي للأرباح - مرجع: TR-" . rand(1000, 9999),
                'created_at' => $now->copy()->subDays(rand(1, 3)),
                'updated_at' => $now,
            ];
        }

        $companies = User::where('role', 'supply_company')->take(1)->get();
        foreach ($companies as $company) {
            $transactions[] = [
                'user_id' => $company->id,
                'reference_type' => 'supply_order',
                'reference_id' => rand(100, 999),
                'transaction_type' => 'debit',
                'amount' => rand(20, 50),
                'description' => "تحويل بنكي للأرباح - مرجع: TR-" . rand(1000, 9999),
                'created_at' => $now->copy()->subDays(rand(1, 3)),
                'updated_at' => $now,
            ];
        }

        // 2. إدخال كل المصفوفة مرة واحدة
        if (!empty($transactions)) {
            DB::table('financial_transactions')->insert($transactions);
            $this->command->info("تم بنجاح دمج البيانات الحقيقية وإنشاء " . count($transactions) . " عملية مالية.");
        }
    }
}
