<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\FarmBooking;
use Illuminate\Support\Facades\Hash;
use App\Models\Driver;
use App\Models\Transport;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // مستخدم رئيسي
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);

        // 10 مزارع + 4 صور لكل مزرعة بشكل تلقائي من Factory
        $farms = Farm::factory(10)->create();

        // 20 حجز مرتبط بالمستخدم نفسه وبمزرعة عشوائية
        FarmBooking::factory(20)->create([
            'user_id' => $user->id,
            'farm_id' => function () use ($farms) {
                return $farms->random()->id;
            },
        ]);
        $this->call(SupplySeeder::class);

        // إضافة 10 سائقين وهميين
        Driver::factory(10)->create();

        // إضافة 20 طلب نقل وهمي
        Transport::factory()->count(10)->create();
        $this->call(TransportSeeder::class);



    }

}
