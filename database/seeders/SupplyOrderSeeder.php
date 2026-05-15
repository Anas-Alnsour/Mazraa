<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\FarmBooking;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SupplyOrderSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SupplyOrder::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // جلب الحجوزات مع المزارع، والمستلزمات، وجميع السائقين
        $bookings = FarmBooking::with('farm')->get();
        $supplies = Supply::all();
        $allDrivers = User::where('role', 'supply_driver')->get();

        if ($bookings->isEmpty() || $supplies->isEmpty() || $allDrivers->isEmpty()) {
            $this->command->warn('تأكد من وجود حجوزات، مستلزمات، وسائقين.');
            return;
        }

        $statuses = ['pending', 'processing', 'out_for_delivery', 'delivered'];

        foreach (range(1, 20) as $index) {
            $supply = $supplies->random();
            $booking = $bookings->random();
            
            // --- تعديل: اختيار سائق من نفس محافظة المزرعة ---
            $farmGov = $booking->farm->governorate;
            
            // فلترة السائقين الذين يسكنون في نفس محافظة المزرعة
            $localDrivers = $allDrivers->where('governorate', $farmGov);

            // إذا لم نجد سائق في نفس المحافظة، نأخذ سائق عشوائي لتجنب توقف الكود، 
            // لكن المنطق الأساسي سيبحث عن ابن المحافظة أولاً.
            $driver = $localDrivers->isNotEmpty() ? $localDrivers->random() : $allDrivers->random();

            $quantity = rand(1, 5);
            $subtotal = ($supply->price ?? 10) * $quantity;

            // --- منطق حساب رسوم التوصيل ---
            $driverGov = $driver->governorate; 

            if ($farmGov === $driverGov) {
                $deliveryFee = 3.00; // نفس المحافظة
            } else {
                $deliveryFee = 5.00; // محافظة مختلفة (حالة احتياطية)
            }

            // خصومات حسب قيمة الطلب
            if ($subtotal >= 50.00) {
                $deliveryFee = 0.00;
            } elseif ($subtotal >= 30.00) {
                $deliveryFee = $deliveryFee / 2;
            }

            $totalPrice = $subtotal + $deliveryFee;

            // حسابات العمولة
            $commissionAmount = $subtotal * 0.10;
            $netCompanyAmount = $totalPrice - $commissionAmount;

            SupplyOrder::create([
                'order_id'    => 'ORD-' . strtoupper(Str::random(8)),
                'user_id'     => $booking->user_id,
                'supply_id'   => $supply->id,
                'booking_id'  => $booking->id,
                'quantity'    => $quantity,
                'total_price' => $totalPrice,
                'commission_amount'  => $commissionAmount,
                'net_company_amount' => $netCompanyAmount,
                'status'      => $statuses[array_rand($statuses)],
                'destination_governorate' => $farmGov,
                'driver_id'   => $driver->id,
                'created_at'  => Carbon::now()->subDays(rand(1, 20)),
                'updated_at'  => Carbon::now(),
            ]);
        }

        $this->command->info('تم إنشاء 20 طلب توريد بنجاح مع مطابقة السائقين لمحافظة المزرعة!');
    }
}