<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'transport_type' => $this->faker->randomElement(['Bus', 'Car', 'Truck']),
            'passengers' => $this->faker->numberBetween(1, 50),
            'driver_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'start_point' => $this->faker->city,
            'destination' => $this->faker->city,
            'distance' => $this->faker->randomFloat(1, 5, 200),
            'price' => $this->faker->numberBetween(50, 500),
            'departure_time' => $this->faker->dateTimeBetween('+1 days', '+2 days'),
            'arrival_time' => $this->faker->dateTimeBetween('+3 days', '+4 days'),
            'status' => $this->faker->randomElement(['Pending', 'In Transit', 'Completed']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
