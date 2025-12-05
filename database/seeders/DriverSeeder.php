<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::create(['name' => 'Ahmed', 'transport_type' => 'Car (Max 4 passengers)']);
        Driver::create(['name' => 'Mohammed', 'transport_type' => 'Bus (Max 20 passengers)']);
        Driver::create(['name' => 'Ali', 'transport_type' => 'Large Bus (Max 50 passengers)']);
        Driver::create(['name' => 'Sara', 'transport_type' => 'Car (Max 4 passengers)']);
        Driver::create(['name' => 'Khaled', 'transport_type' => 'Bus (Max 20 passengers)']);
    }
}
