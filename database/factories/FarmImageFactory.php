<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Farm;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmImage>
 */
class FarmImageFactory extends Factory
{
    protected $model = \App\Models\FarmImage::class;

    public function definition(): array
    {
        return [
            'farm_id' => Farm::factory(), // سيتم استبداله لاحقًا عند الاستخدام
            'image_url' => $this->faker->imageUrl(1200, 800, 'farm', true),
        ];
    }
}
