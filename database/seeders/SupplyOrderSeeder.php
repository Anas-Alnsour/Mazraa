<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\User;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplyOrderSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SupplyOrder::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. جلب البيانات مرة واحدة فقط للذاكرة (لتحسين الأداء بشكل خرافي)
        $bookings = FarmBooking::with('farm')->get();
        $supplies = Supply::all();

        // جلب جميع سائقي التوريد وتجميعهم حسب المحافظة لتسريع البحث
        $allDrivers = User::where('role', 'supply_driver')->get();
        $driversByGov = $allDrivers->groupBy('governorate');

        if ($bookings->isEmpty() || $supplies->isEmpty() || $allDrivers->isEmpty()) {
            $this->command->warn('تأكد من وجود حجوزات، مستلزمات، وسائقين أولاً.');
            return;
        }

        $ordersData = [];

        // خريطة حالات الطلب لتتناسب مع حالة حجز المزرعة بشكل منطقي وواقعي
        $statusMap = [
            'completed' => ['delivered'],
            'confirmed' => ['processing', 'out_for_delivery', 'delivered'],
            'pending'   => ['pending', 'processing'],
            'cancelled' => ['cancelled'],
            'pending_verification' => ['pending']
        ];

        // 2. الدوران على جميع الحجوزات لتوليد آلاف الطلبات
        foreach ($bookings as $booking) {

            // 70% من الحجوزات بطلبوا مستلزمات (لجعل الداتا ضخمة وواقعية)
            if (rand(1, 100) <= 70) {

                // كل حجز ممكن يطلب من 2 إلى 6 منتجات مختلفة
                $numItems = rand(2, 6);

                for ($j = 0; $j < $numItems; $j++) {
                    $supply = $supplies->random();
                    $farmGov = $booking->farm->governorate;

                    // --- اختيار سائق من نفس المحافظة بسرعة صاروخية ---
                    $localDrivers = $driversByGov->get($farmGov);
                    $driver = $localDrivers ? $localDrivers->random() : $allDrivers->random();

                    $quantity = rand(1, 5);
                    $subtotal = ($supply->price ?? 10) * $quantity;

                    // --- حساب رسوم التوصيل ---
                    $driverGov = $driver->governorate;
                    $deliveryFee = ($farmGov === $driverGov) ? 3.00 : 5.00;

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

                    // تحديد الحالة بناءً على حالة الحجز
                    $possibleStatuses = $statusMap[$booking->status] ?? ['pending'];
                    $orderStatus = $possibleStatuses[array_rand($possibleStatuses)];

                    // إضافة الطلب لمصفوفة البيانات
                    $ordersData[] = [
                        'order_id'                => 'ORD-' . strtoupper(Str::random(8)),
                        'user_id'                 => $booking->user_id,
                        'supply_id'               => $supply->id,
                        'booking_id'              => $booking->id,
                        'quantity'                => $quantity,
                        'total_price'             => $totalPrice,
                        'commission_amount'       => $commissionAmount,
                        'net_company_amount'      => $netCompanyAmount,
                        'status'                  => $orderStatus,
                        'destination_governorate' => $farmGov,
                        'driver_id'               => $driver->id,
                        // جعل تواريخ الطلب متطابقة مع تاريخ الحجز لمزيد من الواقعية
                        'created_at'              => clone $booking->created_at,
                        'updated_at'              => clone $booking->updated_at,
                    ];

                    // عمل Insert كل 500 طلب لتجنب استهلاك الذاكرة (Memory Exhaustion)
                    if (count($ordersData) >= 500) {
                        SupplyOrder::insert($ordersData);

                        // تحديث عدادات السائقين والشركات للمجموعة المدخلة
                        $this->updateCounters($ordersData, $driver->id);

                        $ordersData = []; // تصفير المصفوفة للدفعة القادمة
                    }
                }
            }
        }

        // إدخال ما تبقى من الطلبات
        if (count($ordersData) > 0) {
            SupplyOrder::insert($ordersData);
            $this->updateCounters($ordersData, $driver->id);
        }

        $this->command->info('SupplyOrderSeeder: Successfully generated ' . SupplyOrder::count() . ' supply orders with dynamic geofenced routing!');
    }

    /**
     * دالة مساعدة لزيادة عدادات الطلبات للسائقين الذين قاموا بتوصيل طلبات مكتملة
     */
    private function updateCounters($ordersData, $lastDriverId)
    {
        // هذه الدالة وهمية بسيطة، لضمان أن السائقين لديهم عدادات واقعية
        // قمنا بزيادة العدادات مسبقاً في UserSeeder، لكن هذا يعطي واقعية إضافية
        DB::table('users')->where('id', $lastDriverId)->increment('orders_count', rand(1, 3));
    }
}
