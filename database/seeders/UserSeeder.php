<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        User::updateOrCreate(['email' => 'admin@mazraa.com'], [
            'name' => 'فريق إدارة مزرعة',
            'phone' => '0799999901',
            'role' => 'admin',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'owner@mazraa.com'], [
            'name' => 'أحمد القرالة',
            'phone' => '0799999902',
            'role' => 'farm_owner',
            'password' => $password,
            'bank_name' => 'Arab Bank',
            'account_holder_name' => 'Ahmad Al-Karala',
            'iban' => 'JO00 ARAB 0000 0000 0000 0000 0000 00',
        ]);

        User::updateOrCreate(['email' => 'supply@mazraa.com'], [
            'name' => 'الزاد للتوريد الغذائي',
            'phone' => '0799999903',
            'role' => 'supply_company',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'transport@mazraa.com'], [
            'name' => 'النخبة لنقل الركاب',
            'phone' => '0799999904',
            'role' => 'transport_company',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'driver.t@mazraa.com'], [
            'name' => 'محمد العبادي',
            'phone' => '0799999905',
            'role' => 'transport_driver',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'driver.s@mazraa.com'], [
            'name' => 'ياسين الجبالي',
            'phone' => '0799999906',
            'role' => 'supply_driver',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'user@mazraa.com'], [
            'name' => 'عمر النسور',
            'phone' => '0799999907',
            'role' => 'user',
            'password' => $password,
        ]);
    }
}
