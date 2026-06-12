<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\FarmBooking;
use App\Models\SupplyOrder;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Support\Str;

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

        $transactions = [];

        // =========================================================
        // الجزء الأول: أرباح حجوزات المزارع (Farm Bookings)
        // =========================================================
        $bookings = FarmBooking::with('farm.owner')->whereIn('status', ['confirmed', 'completed'])->get();

        foreach ($bookings as $booking) {
            $owner = $booking->farm->owner;
            if (!$owner) continue;

            // إيداع عمولة المنصة (Admin Credit)
            $transactions[] = [
                'user_id'          => $admin->id,
                'reference_type'   => 'farm_booking',
                'reference_id'     => $booking->id,
                'transaction_type' => 'credit',
                'amount'           => $booking->commission_amount,
                'description'      => "عمولة المنصة من حجز مزرعة #{$booking->id}",
                'created_at'       => $booking->updated_at,
                'updated_at'       => $booking->updated_at,
            ];

            // إيداع صافي الربح لصاحب المزرعة (Owner Credit)
            $transactions[] = [
                'user_id'          => $owner->id,
                'reference_type'   => 'farm_booking',
                'reference_id'     => $booking->id,
                'transaction_type' => 'credit',
                'amount'           => $booking->net_owner_amount,
                'description'      => "أرباح حجز مزرعة #{$booking->id}",
                'created_at'       => $booking->updated_at,
                'updated_at'       => $booking->updated_at,
            ];
        }

        // =========================================================
        // الجزء الثاني: أرباح طلبات التوريد (Supply Orders)
        // =========================================================
        $orders = SupplyOrder::with('driver')->whereIn('status', ['processing', 'out_for_delivery', 'delivered'])->get();

        foreach ($orders as $order) {
            // استنتاج الشركة الموردة من خلال السائق الذي تم تعيينه للطلب
            $companyId = $order->driver->company_id ?? null;
            if (!$companyId) continue;

            // إيداع عمولة المنصة
            $transactions[] = [
                'user_id'          => $admin->id,
                'reference_type'   => 'supply_order',
                'reference_id'     => $order->id,
                'transaction_type' => 'credit',
                'amount'           => $order->commission_amount,
                'description'      => "عمولة توريد لطلب #{$order->order_id}",
                'created_at'       => $order->updated_at,
                'updated_at'       => $order->updated_at,
            ];

            // إيداع صافي الربح لشركة التوريد
            if ($order->net_company_amount > 0) {
                $transactions[] = [
                    'user_id'          => $companyId,
                    'reference_type'   => 'supply_order',
                    'reference_id'     => $order->id,
                    'transaction_type' => 'credit',
                    'amount'           => $order->net_company_amount,
                    'description'      => "أرباح طلب توريد #{$order->order_id}",
                    'created_at'       => $order->updated_at,
                    'updated_at'       => $order->updated_at,
                ];
            }
        }

        // =========================================================
        // الجزء الثالث: أرباح رحلات المواصلات (Transports)
        // =========================================================
        $transports = Transport::whereIn('status', ['assigned', 'in_progress', 'completed'])->get();

        foreach ($transports as $transport) {
            if (!$transport->company_id) continue;

            // حساب العمولة (مثلاً 10%) والصافي للشركة
            $adminComm = $transport->price * 0.10;
            $companyNet = $transport->price - $adminComm;

            $transactions[] = [
                'user_id'          => $admin->id,
                'reference_type'   => 'transport',
                'reference_id'     => $transport->id,
                'transaction_type' => 'credit',
                'amount'           => $adminComm,
                'description'      => "عمولة مواصلات لحجز #{$transport->farm_booking_id}",
                'created_at'       => $transport->updated_at,
                'updated_at'       => $transport->updated_at,
            ];

            $transactions[] = [
                'user_id'          => $transport->company_id,
                'reference_type'   => 'transport',
                'reference_id'     => $transport->id,
                'transaction_type' => 'credit',
                'amount'           => $companyNet,
                'description'      => "أرباح رحلة نقل لحجز #{$transport->farm_booking_id}",
                'created_at'       => $transport->updated_at,
                'updated_at'       => $transport->updated_at,
            ];
        }

        // =========================================================
        // الجزء الرابع: سحوبات الأرباح (Payouts/Debits) لجميع الشركاء
        // =========================================================
        // جلب جميع الشركاء (ملاك، شركات توريد، شركات نقل)
        $vendors = User::whereIn('role', ['farm_owner', 'supply_company', 'transport_company'])->get();

        foreach ($vendors as $vendor) {
            // توليد من 3 إلى 10 حركات سحب أرباح لكل شريك عبر الأشهر الماضية (عشان الـ Charts تتعبى)
            $payoutsCount = rand(3, 10);

            for ($i = 0; $i < $payoutsCount; $i++) {
                $randomDate = Carbon::now()->subDays(rand(1, 300));

                $transactions[] = [
                    'user_id'          => $vendor->id,
                    'reference_type'   => 'manual_payout',
                    'reference_id'     => rand(1000, 9999),
                    'transaction_type' => 'debit',
                    'amount'           => rand(50, 400),
                    'description'      => "تحويل بنكي للأرباح - مرجع: TRF-" . strtoupper(Str::random(6)),
                    'created_at'       => $randomDate,
                    'updated_at'       => $randomDate,
                ];
            }
        }

        // =========================================================
        // الإدخال النهائي على دفعات (Bulk Insert) لأداء صاروخي
        // =========================================================
        if (!empty($transactions)) {
            $chunks = array_chunk($transactions, 1000); // إدخال 1000 سجل في كل دفعة
            foreach ($chunks as $chunk) {
                DB::table('financial_transactions')->insert($chunk);
            }
            $this->command->info("Financial Ledger populated! Inserted " . count($transactions) . " massive transactions seamlessly.");
        } else {
            $this->command->warn('No financial transactions were generated. Check your bookings/orders seeders.');
        }
    }
}
