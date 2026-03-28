<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\FarmBooking;
use App\Models\Transport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء المستخدمين اللي اقترحتهم عليك للتجربة (أدمن، مالك، وزبون)
        User::firstOrCreate(['email' => 'admin@mazraa.com'], [
            'name' => 'Admin Mazraa',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0790000000',
        ]);

        $owner = User::firstOrCreate(['email' => 'owner@test.com'], [
            'name' => 'Anas Owner',
            'password' => Hash::make('password'),
            'role' => 'farm_owner',
            'phone' => '0790000001',
        ]);

        $client = User::firstOrCreate(['email' => 'client@test.com'], [
            'name' => 'Anas Client',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '0790000002',
        ]);

        // 2. المستخدم تبعك القديم (عشان ما يخرب شغلك السابق)
        $user = User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'phone' => '0791234567',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 3. إنشاء 10 مزارع وهمية + 4 صور لكل مزرعة (ونربطها بصاحب المزرعة $owner)
        // ملاحظة: تأكد إنك ضفت 'is_approved' => true جوا الـ FarmFactory
        $farms = Farm::factory(10)->create([
            'owner_id' => $owner->id,
        ]);

        // 4. إنشاء 20 حجز مرتبط بالمستخدم تبعك ومزارع عشوائية
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
        $this->command->info('Database seeded successfully with all roles, farms, bookings, supplies, and transports!');
    }
}
