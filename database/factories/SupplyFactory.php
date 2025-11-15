<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplyFactory extends Factory
{
    protected $model = \App\Models\Supply::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),           // اسم المستلزم
            'description' => $this->faker->sentence(), // وصف قصير
            'price' => $this->faker->numberBetween(10, 100), // سعر
            'stock' => $this->faker->numberBetween(5, 50),   // كمية متوفرة
        ];
    }
}
