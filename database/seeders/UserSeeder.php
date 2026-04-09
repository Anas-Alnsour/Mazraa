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

        // 3. Exactly 12 Supply Companies (One per Governorate)
        $governorates = [
            'Amman'   => ['lat' => 31.9454, 'lng' => 35.9284, 'ar' => 'عمان'],
            'Zarqa'   => ['lat' => 32.0608, 'lng' => 36.0942, 'ar' => 'الزرقاء'],
            'Irbid'   => ['lat' => 32.5514, 'lng' => 35.8515, 'ar' => 'إربد'],
            'Aqaba'   => ['lat' => 29.5319, 'lng' => 35.0061, 'ar' => 'العقبة'],
            'Mafraq'  => ['lat' => 32.3326, 'lng' => 36.2045, 'ar' => 'المفرق'],
            'Jerash'  => ['lat' => 32.2723, 'lng' => 35.8914, 'ar' => 'جرش'],
            'Ajloun'  => ['lat' => 32.3326, 'lng' => 35.7517, 'ar' => 'عجلون'],
            'Balqa'   => ['lat' => 32.0401, 'lng' => 35.7148, 'ar' => 'البلقاء'],
            'Madaba'  => ['lat' => 31.7176, 'lng' => 35.7939, 'ar' => 'مأدبا'],
            'Karak'   => ['lat' => 31.1853, 'lng' => 35.7048, 'ar' => 'الكرك'],
            'Tafilah' => ['lat' => 30.8358, 'lng' => 35.6122, 'ar' => 'الطفيلة'],
            'Maan'    => ['lat' => 30.1920, 'lng' => 35.7321, 'ar' => 'معان'],
        ];

        foreach ($governorates as $gov => $data) {
            // A. The Supply Company Branch
            User::updateOrCreate(['email' => strtolower($gov) . '.supply@mazraa.com'], [
                'name' => "شركة توريد " . $data['ar'],
                'phone' => '079' . rand(1000000, 9999999),
                'role' => 'supply_company',
                'password' => $password,
                'governorate' => $gov,
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
            ]);

            // B. Supply Drivers (Localized & Shift-Based)
            $shifts = ['morning', 'evening'];
            foreach ($shifts as $shift) {
                User::updateOrCreate(['email' => "driver.s." . strtolower($gov) . "." . $shift . "@mazraa.com"], [
                    'name' => "سائق توريد " . $data['ar'] . " (" . ucfirst($shift) . ")",
                    'phone' => '078' . rand(1000000, 9999999),
                    'role' => 'supply_driver',
                    'password' => $password,
                    'governorate' => $gov,
                    'shift' => $shift,
                    'latitude' => $data['lat'], // Matches branch coordinates (Standby Point)
                    'longitude' => $data['lng'],
                ]);
            }

            // C. Transport Drivers (Localized & Shift-Based)
            foreach ($shifts as $shift) {
                User::updateOrCreate(['email' => "driver.t." . strtolower($gov) . "." . $shift . "@mazraa.com"], [
                    'name' => "سائق مواصلات " . $data['ar'] . " (" . ucfirst($shift) . ")",
                    'phone' => '077' . rand(1000000, 9999999),
                    'role' => 'transport_driver',
                    'password' => $password,
                    'governorate' => $gov,
                    'shift' => $shift,
                    'latitude' => $data['lat'], // Matches regional base
                    'longitude' => $data['lng'],
                ]);
            }
        }

        User::updateOrCreate(['email' => 'transport@mazraa.com'], [
            'name' => 'النخبة لنقل الركاب',
            'phone' => '0799999904',
            'role' => 'transport_company',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'user@mazraa.com'], [
            'name' => 'عمر النسور',
            'phone' => '0799999907',
            'role' => 'user',
            'password' => $password,
        ]);

        // 8. Generate 50 additional random users to make the platform look busy
        User::factory(50)->create([
            'password' => $password,
        ]);
    }
}
