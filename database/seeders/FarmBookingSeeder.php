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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FarmBooking::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $farms = Farm::all();
        // جلب الزبائن فقط للقيام بالحجوزات
        $users = User::where('role', 'user')->get();

        if ($farms->isEmpty() || $users->isEmpty()) {
            $this->command->error("تأكد من وجود مزارع وزبائن (users) قبل تشغيل هذا الـ Seeder!");
            return;
        }

        $eventTypes = ['morning', 'evening', 'full_day'];
        
         // معلومات التوصيل (مواصلات)
                $requiresTransport = rand(0, 1) == 1;
                $transportCost = $requiresTransport ? rand(15, 40) : 0;
                $pickupLat = $requiresTransport ? '31.' . rand(8000, 9999) : null;
                $pickupLng = $requiresTransport ? '35.' . rand(8000, 9999) : null;

        foreach ($farms as $farm) {
            // سننشئ حجزين لكل مزرعة من الزبائن العشوائيين
            for ($i = 0; $i < 2; $i++) {
                $user = $users->random();
                $eventType = $eventTypes[array_rand($eventTypes)];
                
                // تحديد السعر بناءً على نوع الحجز
                $basePrice = 0;
                if ($eventType == 'morning') $basePrice = $farm->price_per_morning_shift;
                elseif ($eventType == 'evening') $basePrice = $farm->price_per_evening_shift;
                else $basePrice = $farm->price_per_full_day;

                // الحسابات المالية (مهمة جداً لتعكس الواقع)
                $taxAmount = $basePrice * 0.10; // ضريبة 10%
                $totalPrice = $basePrice + $transportCost;
                $commissionAmount = $basePrice * ($farm->commission_rate / 100);
                $netOwnerAmount = $basePrice - $commissionAmount;

                // التواريخ
                $startTime = Carbon::now()->addDays(rand(1, 30))->setHour(rand(8, 14))->setMinute(0);
                $endTime = (clone $startTime)->addHours(($eventType == 'full_day') ? 12 : 6);

               

                FarmBooking::create([
                    'farm_id' => $farm->id,
                    'user_id' => $user->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'event_type' => $eventType,
                    'total_price' => $totalPrice,
                    'tax_amount' => $taxAmount,
                    'commission_amount' => $commissionAmount,
                    'net_owner_amount' => $netOwnerAmount,
                    'payment_status' => 'paid',
                    'stripe_payment_intent_id' => 'pi_' . Str::random(24),
                    'stripe_session_id' => 'cs_test_' . Str::random(40),
                    'status' => 'confirmed',
                    'requires_transport' => $requiresTransport,
                    'transport_cost' => $transportCost,
                    'pickup_lat' => $pickupLat,
                    'pickup_lng' => $pickupLng,
                    'payment_method' => 'visa',
                    'payment_reference' => 'REF-' . strtoupper(Str::random(8)),
                    'created_at' => Carbon::now()->subDays(rand(1, 10)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('تم إنشاء 48 حجز (حجزين لكل مزرعة) ببيانات مالية وجغرافية كاملة!');
    }
}