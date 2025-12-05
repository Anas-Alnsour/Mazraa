<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = \App\Models\Driver::class;

    public function definition()
    {
        $transportTypes = ['Car (Max 4 passengers)', 'Bus (Max 20 passengers)', 'Large Bus (Max 50 passengers)'];

        return [
            'name' => $this->faker->name(),
            'transport_type' => $this->faker->randomElement($transportTypes),
        ];
    }
}
