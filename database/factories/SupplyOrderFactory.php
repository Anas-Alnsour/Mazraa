<?php

namespace Database\Factories;

use App\Models\SupplyOrder;
use App\Models\User;
use App\Models\Supply;
use App\Models\FarmBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyOrderFactory extends Factory
{
    protected $model = SupplyOrder::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'accepted', 'out_for_delivery', 'delivered', 'cancelled']);
        
        return [
            'user_id' => User::factory(),
            'supply_id' => Supply::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->numberBetween(10, 200),
            'status' => $status,
            'order_id' => 'ORD-' . $this->faker->unique()->numberBetween(1000, 9999),
            'booking_id' => FarmBooking::factory(),
            'driver_id' => null, // To be filled by seeder for active orders
            'commission_amount' => $this->faker->numberBetween(1, 20),
            'net_company_amount' => $this->faker->numberBetween(8, 180),
            'destination_governorate' => $this->faker->randomElement(['Amman', 'Zarqa', 'Irbid', 'Jerash', 'Balqa']),
            'created_at' => $this->faker->dateTimeBetween('-6 months', '+1 week'),
        ];
    }
}
