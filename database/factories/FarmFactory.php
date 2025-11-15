<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farm>
 */
class FarmFactory extends Factory
{
    protected $model = \App\Models\Farm::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Farm',
            'location' => $this->faker->city,
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(5, 50),
            'price_per_night' => $this->faker->numberBetween(100, 800),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'main_image' => $this->faker->imageUrl(1000, 600, 'farm', true),
        ];
    }

public function configure()
{
    return $this->afterCreating(function ($farm) {
        \App\Models\FarmImage::factory(4)->create([
            'farm_id' => $farm->id,
        ]);
    });
}
}
