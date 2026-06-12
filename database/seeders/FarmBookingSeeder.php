<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\FarmBooking;
use App\Models\Farm;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class FarmBookingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تنظيف الجدول قبل البدء
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FarmBooking::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // جلب جميع المزارع والزبائن
        $farms = Farm::all();
        $users = User::where('role', 'user')->get();

        if ($farms->isEmpty() || $users->isEmpty()) {
            $this->command->error("تأكد من وجود مزارع وزبائن (users) قبل تشغيل هذا الـ Seeder!");
            return;
        }

        $eventTypes = ['morning', 'evening', 'full_day'];
        $paymentMethods = ['visa', 'cliq', 'cash'];

        $insertData = [];

        // 2. الدوران على جميع المزارع (الـ 270+ مزرعة)
        foreach ($farms as $farm) {

            // توليد عدد عشوائي من الحجوزات لكل مزرعة (بين 10 و 20 حجز للمزرعة الواحدة)
            $bookingsCount = rand(10, 20);

            for ($i = 0; $i < $bookingsCount; $i++) {
                $user = $users->random();
                $eventType = $eventTypes[array_rand($eventTypes)];

                // --- المنطق الزمني (توزيع الحجوزات بين الماضي والمستقبل) ---
                // توزيع عشوائي بين 180 يوم بالماضي (للهيستوري) و 60 يوم بالمستقبل
                $daysOffset = rand(-180, 60);
                $startTime = Carbon::now()->addDays($daysOffset)->setHour(rand(8, 14))->setMinute(0);
                $endTime = (clone $startTime)->addHours(($eventType == 'full_day') ? 12 : 6);

                // --- منطق حالة الحجز (الماضي منتهي، المستقبل مؤكد أو معلق) ---
                if ($daysOffset < 0) {
                    // حجوزات الماضي
                    $status = (rand(1, 100) <= 85) ? 'completed' : 'cancelled'; // 85% ناجح
                    $paymentStatus = ($status === 'completed') ? 'paid' : 'refunded';
                } else {
                    // حجوزات المستقبل
                    $futureStatuses = ['confirmed', 'confirmed', 'pending', 'pending_verification'];
                    $status = $futureStatuses[array_rand($futureStatuses)];
                    $paymentStatus = ($status === 'confirmed') ? 'paid' : 'pending';
                }

                // --- معلومات التوصيل ---
                $requiresTransport = (rand(1, 100) <= 40); // 40% من الحجوزات بتطلب مواصلات
                $transportCost = $requiresTransport ? rand(15, 40) : 0;
                $pickupLat = $requiresTransport ? '31.' . rand(8000, 9999) : null;
                $pickupLng = $requiresTransport ? '35.' . rand(8000, 9999) : null;

                // --- الحسابات المالية الدقيقة ---
                $basePrice = 0;
                if ($eventType == 'morning') $basePrice = $farm->price_per_morning_shift;
                elseif ($eventType == 'evening') $basePrice = $farm->price_per_evening_shift;
                else $basePrice = $farm->price_per_full_day;

                $taxAmount = $basePrice * 0.10; // ضريبة 10%
                $commissionAmount = $basePrice * ($farm->commission_rate / 100);

                // السعر الإجمالي يشمل الضريبة والمواصلات
                $totalPrice = $basePrice + $taxAmount + $transportCost;
                // صافي ربح المالك بعد خصم عمولة المنصة
                $netOwnerAmount = $basePrice - $commissionAmount;

                // تجهيز البيانات للإدخال السريع
                $insertData[] = [
                    'farm_id'                  => $farm->id,
                    'user_id'                  => $user->id,
                    'start_time'               => $startTime->format('Y-m-d H:i:s'),
                    'end_time'                 => $endTime->format('Y-m-d H:i:s'),
                    'event_type'               => $eventType,
                    'total_price'              => $totalPrice,
                    'tax_amount'               => $taxAmount,
                    'commission_amount'        => $commissionAmount,
                    'net_owner_amount'         => $netOwnerAmount,
                    'payment_status'           => $paymentStatus,
                    'stripe_payment_intent_id' => 'pi_' . Str::random(24),
                    'stripe_session_id'        => 'cs_test_' . Str::random(40),
                    'status'                   => $status,
                    'requires_transport'       => $requiresTransport,
                    'transport_cost'           => $transportCost,
                    'pickup_lat'               => $pickupLat,
                    'pickup_lng'               => $pickupLng,
                    'payment_method'           => $paymentMethods[array_rand($paymentMethods)],
                    'payment_reference'        => 'REF-' . strtoupper(Str::random(8)),
                    // تاريخ إنشاء الحجز قبل موعده بـ 2 لـ 15 يوم
                    'created_at'               => $startTime->copy()->subDays(rand(2, 15))->format('Y-m-d H:i:s'),
                    'updated_at'               => ($status === 'completed') ? $endTime->format('Y-m-d H:i:s') : Carbon::now()->format('Y-m-d H:i:s'),
                ];

                // تنفيذ الإدخال كل 500 حجز لتفادي الضغط على السيرفر
                if (count($insertData) >= 500) {
                    DB::table('farm_bookings')->insert($insertData);
                    $insertData = []; // تصفير المصفوفة للدفعة اللي بعدها
                }
            }
        }

        // إدخال ما تبقى من الحجوزات
        if (count($insertData) > 0) {
            DB::table('farm_bookings')->insert($insertData);
        }

        $totalBookings = DB::table('farm_bookings')->count();
        $this->command->info("FarmBookingSeeder: Successfully generated {$totalBookings} massive bookings (Past & Future) with accurate financial splits!");
    }
}
