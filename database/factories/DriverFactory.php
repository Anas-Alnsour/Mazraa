<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    protected $model = \App\Models\Driver::class;

    public function definition()
    {
        $transportTypes = ['Car', 'Bus', 'Large Bus'];

        return [
            'name' => $this->faker->name(),
            'transport_type' => $this->faker->randomElement($transportTypes),
        ];
    }
}
