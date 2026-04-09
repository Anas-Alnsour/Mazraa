<?php

namespace Database\Seeders;

use App\Models\Transport;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TransportSeeder extends Seeder
{
    public function run()
    {
        // 1. Get the seeded Transport Company account
        $company = User::where('email', 'transport@mazraa.com')->first();

        if (!$company) {
            $this->command->warn('Transport company user not found. Skipping TransportSeeder.');
            return;
        }

        // 2. Create 20+ vehicles for the transport company
        $vehicleTypes = [
            'Hyundai Coaster 2023', 'Mercedes Sprinter VIP', 'GMC Savana Executive',
            'Toyota Hiace High Roof', 'Ford Transit Custom', 'Volkswagen Crafter'
        ];

        $vehicles = [];
        for ($i = 1; $i <= 25; $i++) {
            $vehicles[] = Vehicle::create([
                'company_id'    => $company->id,
                'type'          => $vehicleTypes[array_rand($vehicleTypes)],
                'license_plate' => rand(10, 99) . "-" . rand(1000, 9999),
                'capacity'      => rand(7, 30),
                'status'        => 'available',
                'driver_id'     => null,
            ]);
        }

        // 3. Create unique drivers for these vehicles
        $firstNames = ['سامي', 'خالد', 'إبراهيم', 'محمد', 'يوسف', 'عمر', 'أحمد', 'زيد', 'ليث', 'حمزة'];
        $lastNames = ['الفايز', 'العبادي', 'الضمور', 'القيسي', 'المجالي', 'العدوان', 'النسور', 'القرالة'];

        foreach ($vehicles as $index => $vehicle) {
            $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
            
            // Create driver user with permanent vehicle link
            $driver = User::create([
                'name'                 => $name,
                'email'                => "driver{$index}.t@mazraa.com",
                'phone'                => '079' . rand(1000000, 9999999),
                'password'             => Hash::make('password123'),
                'role'                 => 'transport_driver',
                'company_id'           => $company->id,
                'transport_vehicle_id' => $vehicle->id,
                'governorate'          => ['Amman', 'Zarqa', 'Irbid', 'Jerash', 'Balqa'][array_rand(['Amman', 'Zarqa', 'Irbid', 'Jerash', 'Balqa'])],
            ]);

            // Also set the vehicle's driver_id back-reference
            $vehicle->update(['driver_id' => $driver->id]);
        }

        // 4. Create 20 dummy transport job records
        Transport::factory(20)->create();

        $this->command->info('TransportSeeder: Created 3 vehicles + 3 paired drivers for transport@mazraa.com');
    }
}
