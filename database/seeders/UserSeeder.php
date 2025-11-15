<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 5 سائقين fake
        \App\Models\User::factory()->count(5)->create([
            'role' => 'driver',
        ]);

        // إذا تحب، إضافة مستخدمين آخرين للـ test
        \App\Models\User::factory()->count(5)->create([
            'role' => 'customer',
        ]);
    }
}
