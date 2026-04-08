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

        // 2. Create 3 vehicles for the transport company
        $vehiclesData = [
            ['type' => 'Toyota Hiace', 'license_plate' => 'A-1234', 'capacity' => 12],
            ['type' => 'Mercedes Sprinter', 'license_plate' => 'B-5678', 'capacity' => 18],
            ['type' => 'Coaster Bus', 'license_plate' => 'C-9012', 'capacity' => 30],
        ];

        $vehicles = [];
        foreach ($vehiclesData as $vData) {
            $vehicles[] = Vehicle::create([
                'company_id'    => $company->id,
                'type'          => $vData['type'],
                'license_plate' => $vData['license_plate'],
                'capacity'      => $vData['capacity'],
                'status'        => 'available',
                'driver_id'     => null, // Will be linked below
            ]);
        }

        // 3. Create 3 drivers permanently paired to the 3 vehicles
        $driversData = [
            ['name' => 'Ahmad Khalil',   'email' => 'driver1.t@mazraa.com', 'phone' => '0791100001'],
            ['name' => 'Omar Nasser',    'email' => 'driver2.t@mazraa.com', 'phone' => '0791100002'],
            ['name' => 'Yousef Hamdan',  'email' => 'driver3.t@mazraa.com', 'phone' => '0791100003'],
        ];

        foreach ($driversData as $index => $dData) {
            $vehicle = $vehicles[$index];

            // Create driver user with permanent vehicle link
            $driver = User::create([
                'name'                 => $dData['name'],
                'email'                => $dData['email'],
                'phone'                => $dData['phone'],
                'password'             => Hash::make('password123'),
                'role'                 => 'transport_driver',
                'company_id'           => $company->id,
                'transport_vehicle_id' => $vehicle->id,  // 1-to-1 permanent link
                'governorate'          => 'Amman',
            ]);

            // Also set the vehicle's driver_id back-reference for legacy support
            $vehicle->update(['driver_id' => $driver->id]);
        }

        // 4. Create 20 dummy transport job records
        Transport::factory(20)->create();

        $this->command->info('TransportSeeder: Created 3 vehicles + 3 paired drivers for transport@mazraa.com');
    }
}
