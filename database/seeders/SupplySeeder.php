<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;

class SupplySeeder extends Seeder
{
    public function run()
    {
        // إنشاء 10 مستلزمات وهمية
        Supply::factory()->count(10)->create();
    }
}
