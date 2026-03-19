<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Hash;
use App\Models\Transport;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء المستخدم المسؤول (Admin) - منع التكرار باستخدام updateOrCreate
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'phone' => '0790000000',
                'role' => 'admin',
            ]
        );

        // 2. إنشاء 5 سائقين نقل وهميين (بناءً على الهيكلية الجديدة في جدول users)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "driver{$i}@mazraa.com"],
                [
                    'name' => "Driver {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'transport_driver',
                    'phone' => '079100000' . $i,
                ]
            );
        }

        // 3. إنشاء 10 مزارع وهمية
        $farms = Farm::factory(10)->create();

        // 4. إنشاء 20 حجز مرتبط بالمستخدم الرئيسي
        FarmBooking::factory(20)->create([
            'user_id' => $user->id,
            'farm_id' => function () use ($farms) {
                return $farms->random()->id;
            },
        ]);

        // 5. استدعاء Seeders الملحقات
        $this->call(SupplySeeder::class);

        // 6. إنشاء طلبات نقل وهمية
        Transport::factory()->count(10)->create();

        // جرب إلغاء التعليق إذا كان ملف TransportSeeder موجوداً فعلياً
        // $this->call(TransportSeeder::class);
    }
}
