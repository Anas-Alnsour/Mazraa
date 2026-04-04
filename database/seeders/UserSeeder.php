<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الباسوورد الموحد لكل حسابات التست
        $password = Hash::make('password123');

        // 1. Super Admin
        User::updateOrCreate(['email' => 'admin@mazraa.com'], [
            'name' => 'System Admin',
            'phone' => '0799999901', // أرقام مميزة عشان ما تضرب مع إيرور التكرار
            'role' => 'admin',
            'password' => $password,
        ]);

        // 2. Farm Owner
        User::updateOrCreate(['email' => 'owner@mazraa.com'], [
            'name' => 'Farm Owner',
            'phone' => '0799999902',
            'role' => 'farm_owner',
            'password' => $password,
            'bank_name' => 'Arab Bank',
            'account_holder_name' => 'Farm Owner Test',
            'iban' => 'JO00 ARAB 0000 0000 0000 0000 0000 00',
        ]);

        // 3. Supply Company
        User::updateOrCreate(['email' => 'supply@mazraa.com'], [
            'name' => 'Supply Company',
            'phone' => '0799999903',
            'role' => 'supply_company',
            'password' => $password,
        ]);

        // 4. Transport Company
        User::updateOrCreate(['email' => 'transport@mazraa.com'], [
            'name' => 'Transport Company',
            'phone' => '0799999904',
            'role' => 'transport_company',
            'password' => $password,
        ]);

        // 5. Transport Driver
        User::updateOrCreate(['email' => 'driver.t@mazraa.com'], [
            'name' => 'Transport Driver',
            'phone' => '0799999905',
            'role' => 'transport_driver',
            'password' => $password,
        ]);

        // 6. Supply Driver
        User::updateOrCreate(['email' => 'driver.s@mazraa.com'], [
            'name' => 'Supply Driver',
            'phone' => '0799999906',
            'role' => 'supply_driver',
            'password' => $password,
        ]);

        // 7. Normal User (Customer)
        User::updateOrCreate(['email' => 'user@mazraa.com'], [
            'name' => 'Normal User',
            'phone' => '0799999907',
            'role' => 'user',
            'password' => $password,
        ]);
    }
}
