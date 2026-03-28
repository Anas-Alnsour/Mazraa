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
'image_url' => 'https://images.unsplash.com/photo-1590490359683-658d3d23f972?q=80&w=1000&auto=format&fit=crop',        ];
    }
}
