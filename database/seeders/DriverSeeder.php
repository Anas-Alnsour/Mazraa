<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::create(['name' => 'Ahmed', 'transport_type' => 'Car']);
        Driver::create(['name' => 'Mohammed', 'transport_type' => 'Bus']);
        Driver::create(['name' => 'Ali', 'transport_type' => 'Large Bus']);
        Driver::create(['name' => 'Sara', 'transport_type' => 'Car']);
        Driver::create(['name' => 'Khaled', 'transport_type' => 'Bus']);
    }
}
