<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تنظيف الجدول قبل البدء لضمان عدم وجود بيانات قديمة
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Vehicle::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create('ar_SA'); // توليد أسماء عربية حقيقية
        $password = Hash::make('password123');

        // ==========================================
        // الحسابات الأساسية (كما طلبت تماماً)
        // ==========================================
        User::create([
            'email' => 'admin@mazraa.com',
            'name' => 'فريق إدارة مزرعة',
            'phone' => '0799999901',
            'role' => 'admin',
            'password' => $password,
        ]);

        $owners = [
            ['name' => 'Ahmad Al-Karala', 'email' => 'AhmadAl-Karala@gmail.com'],
            ['name' => 'Mohammad Awad', 'email' => 'MohammadAwad@gmail.com'],
            ['name' => 'Omar Awad', 'email' => 'omarAwad@gmail.com'],
            ['name' => 'Ali Abu Zaid', 'email' => 'AliAbuZaid@gmail.com'],
        ];

        foreach ($owners as $owner) {
            User::create([
                'name' => $owner['name'],
                'email' => $owner['email'],
                'phone' => '079' . rand(1111111, 9999999),
                'role' => 'farm_owner',
                'password' => $password,
                'bank_name' => 'Arab Bank',
                'account_holder_name' => $owner['name'],
                'iban' => 'JO00 ARAB ' . rand(1000, 9999) . ' 0000 0000 0000 0000 00',
            ]);
        }

        // إنشاء مُلّاك إضافيين عشوائيين لزيادة الداتا
        for($o=0; $o<15; $o++) {
            $name = $faker->name;
            User::create([
                'name' => $name,
                'email' => "owner.rand.{$o}@mazraa.com",
                'phone' => '079' . rand(1111111, 9999999),
                'role' => 'farm_owner',
                'password' => $password,
                'bank_name' => ['Bank of Jordan', 'Islamic Bank', 'Etihad Bank'][rand(0,2)],
                'account_holder_name' => $name,
                'iban' => 'JO00 BOJ ' . rand(1000, 9999) . ' 0000 0000 0000 0000 00',
            ]);
        }

        // ==========================================
        // المحافظات والشركات المركزية
        // ==========================================
        $governorates = [
            'Amman'   => ['lat' => 31.9454, 'lng' => 35.9284, 'ar' => 'عمان'],
            'Zarqa'   => ['lat' => 32.0608, 'lng' => 36.0942, 'ar' => 'الزرقاء'],
            'Irbid'   => ['lat' => 32.5514, 'lng' => 35.8515, 'ar' => 'إربد'],
            'Aqaba'   => ['lat' => 29.5319, 'lng' => 35.0061, 'ar' => 'العقبة'],
            'Mafraq'  => ['lat' => 32.3326, 'lng' => 36.2045, 'ar' => 'المفرق'],
            'Jerash'  => ['lat' => 32.2723, 'lng' => 35.8914, 'ar' => 'جرش'],
            'Ajloun'  => ['lat' => 32.3326, 'lng' => 35.7517, 'ar' => 'عجلون'],
            'Salt'    => ['lat' => 32.0401, 'lng' => 35.7148, 'ar' => 'السلط'],
            'Madaba'  => ['lat' => 31.7176, 'lng' => 35.7939, 'ar' => 'مأدبا'],
            'Karak'   => ['lat' => 31.1853, 'lng' => 35.7048, 'ar' => 'الكرك'],
            'Tafilah' => ['lat' => 30.8358, 'lng' => 35.6122, 'ar' => 'الطفيلة'],
            'Maan'    => ['lat' => 30.1920, 'lng' => 35.7321, 'ar' => 'معان'],
        ];

        $transportCompany = User::create([
            'name' => 'النخبة لنقل الركاب',
            'email' => 'transport@mazraa.com',
            'phone' => '0799999904',
            'role' => 'transport_company',
            'password' => $password,
            'bank_name' => 'Etihad Bank',
            'iban' => 'JO00 ETHI 1234 0000 0000 0000 0000 00',
        ]);

        User::create([
            'name'     => 'شركة التوريد الرئيسية ',
            'email'    => 'hq@mazraa.com',
            'password' => $password,
            'phone' => '0780000011',
            'role' => 'supply_company',
            'is_hq' => true,
            'bank_name' => 'Islamic Bank',
            'iban' => 'JO00 ISLM 5678 0000 0000 0000 0000 00',
        ]);

        // حساباتك الشخصية
        User::create(['email' => 'OmarAlnsour@gmail.com', 'name' => 'عمر النسور', 'phone' => '0799999907', 'role' => 'user', 'password' => $password]);
        User::create(['email' => 'AdnanAwad@gmail.com', 'name' => 'AdnanAwad', 'phone' => '0791979907', 'role' => 'user', 'password' => $password]);
        User::create(['email' => 'user@mazraa.com', 'name' => 'Main user', 'phone' => '0799797907', 'role' => 'user', 'password' => $password]);

        // ==========================================
        // الدوران الرئيسي: توليد داتا ضخمة جداً للشركات والسائقين
        // ==========================================
        foreach ($governorates as $gov => $data) {

            // أ. إنشاء فرع شركة التوريد لكل محافظة
            $supplyCompany = User::create([
                'email' => strtolower($gov) . '.supply@mazraa.com',
                'name' => "شركة توريد " . $data['ar'],
                'phone' => '079' . rand(1000000, 9999999),
                'role' => 'supply_company',
                'password' => $password,
                'governorate' => $gov,
                'latitude' => $data['lat'],
                'longitude' => $data['lng'],
                'bank_name' => 'Arab Bank',
                'iban' => 'JO00 ARAB ' . rand(1000, 9999) . ' 0000 0000 0000 0000 00',
            ]);

            $shifts = ['morning', 'evening'];

            foreach ($shifts as $shift) {

                // ----------------------------------------------------
                // أولاً: إنشاء سائقي التوريد (Supply) - [15 سائق لكل شفت = 360 سائق بالموقع]
                // ----------------------------------------------------
                $vehicleTypesS = [
                    ['type' => 'Toyota Hilux', 'capacity' => 5], ['type' => 'Hyundai H1', 'capacity' => 5],
                    ['type' => 'Ford Transit', 'capacity' => 5], ['type' => 'Kia Bongo', 'capacity' => 5],
                    ['type' => 'Nissan Urvan', 'capacity' => 5], ['type' => 'Chevrolet Express', 'capacity' => 5],
                ];

                for ($i = 1; $i <= 15; $i++) {
                    $driverName = $faker->name;
                    $driverS = User::create([
                        'email' => "driver.s." . strtolower($gov) . "." . $shift . "." . $i . "@mazraa.com",
                        'name' => "سائق توريد - " . $driverName,
                        'phone' => '078' . rand(1000000, 9999999),
                        'role' => 'supply_driver',
                        'password' => $password,
                        'governorate' => $gov,
                        'shift' => $shift,
                        'latitude' => $data['lat'] + (rand(-100, 100) / 10000), // توزيع عشوائي حول المحافظة
                        'longitude' => $data['lng'] + (rand(-100, 100) / 10000),
                        'company_id' => $supplyCompany->id,
                        'trips_count' => rand(50, 400), // سجل سابق
                        'orders_count' => rand(100, 800), // سجل سابق
                        'bank_name' => 'Bank of Jordan',
                        'iban' => 'JO00 BOJ ' . rand(1000, 9999) . ' 0000 0000 0000 0000 00',
                    ]);

                    $selectedVehicleS = $vehicleTypesS[array_rand($vehicleTypesS)];
                    $vehicleS = Vehicle::create([
                        'company_id' => $supplyCompany->id,
                        'driver_id' => $driverS->id,
                        'type' => $selectedVehicleS['type'],
                        'license_plate' => rand(10, 99) . "-" . rand(1000, 9999),
                        'capacity' => $selectedVehicleS['capacity'],
                        'status' => 'available',
                    ]);
                    $driverS->update(['transport_vehicle_id' => $vehicleS->id]);
                }

                // ----------------------------------------------------
                // ثانياً: إنشاء سائقي المواصلات (Transport) - [15 سائق لكل شفت = 360 سائق]
                // ----------------------------------------------------
                $vehicleTypesT = [
                    ['type' => 'Small Car (Kia Cerato)', 'capacity' => 4],
                    ['type' => 'Medium Van (Hyundai Staria)', 'capacity' => 9],
                    ['type' => 'Minibus (Toyota Coaster)', 'capacity' => 22],
                    ['type' => 'Large Bus (Mercedes)', 'capacity' => 50],
                    ['type' => 'VIP SUV (GMC Yukon)', 'capacity' => 7],
                ];

                for ($i = 1; $i <= 15; $i++) {
                    // اختيار نوع سيارة بالترتيب لأول 4، وعشوائي للباقي
                    $vehicleData = $i <= count($vehicleTypesT) ? $vehicleTypesT[$i-1] : $vehicleTypesT[array_rand($vehicleTypesT)];
                    $driverName = $faker->name;

                    $driverT = User::create([
                        'email' => "driver.t." . strtolower($gov) . "." . $shift . "." . $i . "@mazraa.com",
                        'name' => "كابتن " . $driverName,
                        'phone' => '077' . rand(1000000, 9999999),
                        'role' => 'transport_driver',
                        'password' => $password,
                        'governorate' => $gov,
                        'shift' => $shift,
                        'latitude' => $data['lat'] + (rand(-100, 100) / 10000),
                        'longitude' => $data['lng'] + (rand(-100, 100) / 10000),
                        'company_id' => $transportCompany->id,
                        'trips_count' => rand(20, 300),
                        'orders_count' => 0,
                        'bank_name' => 'Etihad Bank',
                        'iban' => 'JO00 ETHI ' . rand(1000, 9999) . ' 0000 0000 0000 0000 00',
                    ]);

                    $vehicleT = Vehicle::create([
                        'company_id'    => $transportCompany->id,
                        'driver_id'     => $driverT->id,
                        'type'          => $vehicleData['type'],
                        'license_plate' => rand(10, 99) . "-" . rand(1000, 9999),
                        'capacity'      => $vehicleData['capacity'],
                        'status'        => 'available',
                    ]);
                    $driverT->update(['transport_vehicle_id' => $vehicleT->id]);
                }
            }
        }

        // ==========================================
        // 1000 مستخدم عشوائي حقيقي (Bulk Insert للأداء)
        // ==========================================
        $usersData = [];
        $govKeys = array_keys($governorates);

        for ($i = 1; $i <= 1000; $i++) {
            $randomGov = $govKeys[array_rand($govKeys)];
            $usersData[] = [
                'name' => $faker->name,
                'email' => "customer_{$i}_" . Str::random(5) . "@example.com",
                'password' => $password,
                'phone' => '07' . rand(7,9) . rand(1000000, 9999999),
                'role' => 'user',
                'governorate' => $randomGov,
                'latitude' => $governorates[$randomGov]['lat'] + (rand(-150, 150) / 10000),
                'longitude' => $governorates[$randomGov]['lng'] + (rand(-150, 150) / 10000),
                'created_at' => now()->subDays(rand(1, 365)),
                'updated_at' => now(),
            ];

            // إدخال كل 200 يوزر دفعة وحدة عشان ما يعلق السيرفر
            if (count($usersData) == 200) {
                User::insert($usersData);
                $usersData = [];
            }
        }
        // إدخال الباقي إن وُجد
        if (count($usersData) > 0) {
            User::insert($usersData);
        }
    }
}
