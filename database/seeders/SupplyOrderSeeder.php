<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\SupplyOrder;
use App\Models\User;

class SupplyOrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $supplies = Supply::all();

        foreach ($users as $user) {
            foreach ($supplies as $supply) {
                SupplyOrder::create([
                    'user_id' => $user->id,
                    'supply_id' => $supply->id,
                    'quantity' => rand(1, 5),
                    'total_price' => $supply->price * rand(1, 5),
                ]);
            }
        }
    }
}
