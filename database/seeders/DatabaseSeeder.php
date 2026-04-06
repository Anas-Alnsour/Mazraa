<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. زرع الحسابات الـ 7 الأساسية
        $this->call(UserSeeder::class);

        // 2. سحب حساب المالك والزبون اللي تم إنشاؤهم للتو
        $owner = User::where('email', 'owner@mazraa.com')->first();
        $user = User::where('email', 'user@mazraa.com')->first();

        // 3. إنشاء 10 مزارع وهمية وربطها بصاحب المزرعة
        $farms = Farm::factory(10)->create([
            'owner_id' => $owner->id,
        ]);

        // 4. إنشاء 20 حجز مرتبط بالزبون ومزارع عشوائية
        FarmBooking::factory(20)->create([
            'user_id' => $user->id,
            'farm_id' => function () use ($farms) {
                return $farms->random()->id;
            },
        ]);

        // 5. شغلك الأصلي تبع التوريد والنقل
        $this->call(SupplySeeder::class);

        // إضافة 10 سائقين وهميين
        User::factory(10)->create(['role' => 'transport_driver']);

        // إضافة 10 طلبات نقل وهمية
        Transport::factory(10)->create();
        $this->call(TransportSeeder::class);

        // رسالة تأكيد في التيرمينال
        $this->command->info('Database seeded successfully with all roles, dummy farms, bookings, supplies, and transports!');
    }
}
