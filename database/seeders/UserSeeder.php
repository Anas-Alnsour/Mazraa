<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vehicle; // مهم جداً
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. تنظيف الجدول قبل البدء لضمان عدم وجود بيانات قديمة
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Vehicle::truncate(); // تنظيف المركبات أيضاً لأننا سنربطها بالسائقين
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $password = Hash::make('password123');

        User::updateOrCreate(['email' => 'admin@mazraa.com'], [
            'name' => 'فريق إدارة مزرعة',
            'phone' => '0799999901',
            'role' => 'admin',
            'password' => $password,
        ]);

        // ملاك المزارع (مثال واحد والباقي يتبع نفس النمط)
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

        // 3. Exactly 12 Supply Companies (One per Governorate)
        $governorates = [
            'Amman'   => ['lat' => 31.9454, 'lng' => 35.9284, 'ar' => 'عمان'],
            'Zarqa'   => ['lat' => 32.0608, 'lng' => 36.0942, 'ar' => 'الزرقاء'],
            'Irbid'   => ['lat' => 32.5514, 'lng' => 35.8515, 'ar' => 'إربد'],
            'Aqaba'   => ['lat' => 29.5319, 'lng' => 35.0061, 'ar' => 'العقبة'],
            'Mafraq'  => ['lat' => 32.3326, 'lng' => 36.2045, 'ar' => 'المفرق'],
            'Jerash'  => ['lat' => 32.2723, 'lng' => 35.8914, 'ar' => 'جرش'],
            'Ajloun'  => ['lat' => 32.3326, 'lng' => 35.7517, 'ar' => 'عجلون'],
            'Salt'   => ['lat' => 32.0401, 'lng' => 35.7148, 'ar' => 'السلط'],
            'Madaba'  => ['lat' => 31.7176, 'lng' => 35.7939, 'ar' => 'مأدبا'],
            'Karak'   => ['lat' => 31.1853, 'lng' => 35.7048, 'ar' => 'الكرك'],
            'Tafilah' => ['lat' => 30.8358, 'lng' => 35.6122, 'ar' => 'الطفيلة'],
            'Maan'    => ['lat' => 30.1920, 'lng' => 35.7321, 'ar' => 'معان'],
        ];

        // User::updateOrCreate(['email' => 'transport@mazraa.com'], [
        //     'name' => 'النخبة لنقل الركاب',
        //     'phone' => '0799999904',
        //     'role' => 'transport_company',
        //     'password' => $password,
        // ]);

        // شركة المواصلات المركزية
        $transportCompany = User::create([
            'name' => 'النخبة لنقل الركاب',
            'email' => 'transport@mazraa.com',
            'phone' => '0799999904',
            'role' => 'transport_company',
            'password' => $password,
        ]);

        User::updateOrCreate([
            'name'     => 'شركة التوريد الرئيسية ', // يمكنك تغيير الاسم كما تحب
            'email'    => 'hq@mazraa.com',
            'password' => Hash::make('password123'),
            // أضف أي حقول أخرى مطلوبة في جدولك، مثلاً:
            'phone' => '0780000011',
            'role' => 'supply_company', // أو أي دور آخر يناسب هذا المستخدم 
            'is_hq' => true, // حقل لتحديد إذا كان هذا المستخدم هو المقر الرئيسي    
        ]);

        User::updateOrCreate(['email' => 'OmarAlnsour@gmail.com'], [
            'name' => 'عمر النسور',
            'phone' => '0799999907',
            'role' => 'user',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'AdnanAwad@gmail.com'], [
            'name' => 'AdnanAwad',
            'phone' => '0791979907',
            'role' => 'user',
            'password' => $password,
        ]);

        User::updateOrCreate(['email' => 'user@mazraa.com'], [
            'name' => 'Main user',
            'phone' => '0799797907',
            'role' => 'user',
            'password' => $password,
        ]);

        // 4. الدوران الرئيسي: إنشاء الشركات والسائقين وربط المركبات
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
            ]);

            $shifts = ['morning', 'evening'];

            foreach ($shifts as $shift) {
                // ب. إنشاء سائق التوريد
                $driverS = User::create([
                    'email' => "driver.s." . strtolower($gov) . "." . $shift . "@mazraa.com",
                    'name' => "سائق توريد " . $data['ar'] . " (" . ucfirst($shift) . ")",
                    'phone' => '078' . rand(1000000, 9999999),
                    'role' => 'supply_driver',
                    'password' => $password,
                    'governorate' => $gov,
                    'shift' => $shift,
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'company_id' => $supplyCompany->id, // مربوط بشركة المحافظة
                    'trips_count' => 0,
                    'orders_count' => 0,
                ]);

                 $vehicleTypesS = [
                    ['type' => 'Toyota', 'capacity' => 5],
                    ['type' => 'Camry', 'capacity' => 5],
                    ['type' => 'ford', 'capacity' => 5],
                    ['type' => 'honda', 'capacity' => 5],
                ];

                // 2. اختيار نوع عشوائي
                $selectedVehicle = $vehicleTypesS[array_rand($vehicleTypesS)];

                // ج. إنشاء مركبة شحن للسائق
                $vehicleS = Vehicle::create([
                    'company_id' => $supplyCompany->id,
                    'driver_id' => $driverS->id,
                    'type' => $selectedVehicle['type'],
                    'license_plate' => rand(10, 99) . "-" . rand(1000, 9999),
                    'capacity' => $selectedVehicle['capacity'],
                    'status' => 'available',
                ]);
                $driverS->update(['transport_vehicle_id' => $vehicleS->id]);

                // د. إنشاء سائق المواصلات
                $driverT = User::create([
                    'email' => "driver.t." . strtolower($gov) . "." . $shift . "@mazraa.com",
                    'name' => "سائق مواصلات " . $data['ar'] . " (" . ucfirst($shift) . ")",
                    'phone' => '077' . rand(1000000, 9999999),
                    'role' => 'transport_driver',
                    'password' => $password,
                    'governorate' => $gov,
                    'shift' => $shift,
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'company_id' => $transportCompany->id, // مربوط بالشركة المركزية
                    'trips_count' => 0,
                    'orders_count' => 0,
                ]);

                // هـ. إنشاء مركبة ركاب للسائق
                // 1. تعريف أنواع المركبات وسعاتها
                $vehicleTypesT = [
                    ['type' => 'Small Car', 'capacity' => 5],
                    ['type' => 'Medium Van', 'capacity' => 12],
                    ['type' => 'Bus', 'capacity' => 25],
                    ['type' => 'JETT Bus', 'capacity' => 50],
                ];

                // 2. اختيار نوع عشوائي
                $selectedVehicle = $vehicleTypesT[array_rand($vehicleTypesT)];

                // 3. إنشاء المركبة بناءً على الاختيار
                $vehicleT = Vehicle::create([
                    'company_id'    => $transportCompany->id,
                    'driver_id'     => $driverT->id,
                    'type'          => $selectedVehicle['type'],
                    'license_plate' => rand(10, 99) . "-" . rand(1000, 9999),
                    'capacity'      => $selectedVehicle['capacity'],
                    'status'        => 'available',
                ]);
                $driverT->update(['transport_vehicle_id' => $vehicleT->id]);
            }
        }



        // 8. إنشاء 50 مستخدم عشوائي (تعليقك كان مكتوب 50 لكن الكود كان 10، عدلتها لك لتطابق التعليق)
        User::factory(50)->create([
            'password' => $password,
        ]);
    } // القوس الخاص بإغلاق دالة run()
} // القوس الخاص بإغلاق كلاس UserSeeder