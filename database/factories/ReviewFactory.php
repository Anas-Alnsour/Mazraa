<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $arabicComments = [
            'المزرعة بتجنن والمسبح نظيف جداً، أنصح بها للعائلات.',
            'تجربة رائعة وتجاوب سريع من المالك، المكان هادئ ومريح.',
            'كل شيء كان ممتاز، المرافق متكاملة والنظافة عالية جداً.',
            'إطلالة خلابة وجلسات خارجية مريحة، قضينا وقت ممتع جداً.',
            'من أجمل المزارع اللي زرتها بالأردن، خصوصية تامة وفخامة.',
            'الخدمة ممتازة والمكان زي الصور بالضبط، بنصحكم فيها.',
            'تجربة بتجنن، المسبح كبير والغرف واسعة والشواء كان ممتع.',
            'هدوء تام وراحة نفسية، مناسبة جداً للاسترخاء.',
            'مكان رائع ونظيف والتعامل راقي جداً.',
            'استمتعنا كتير باليوم، المزرعة واسعة ومجهزة بكل شي.',
        ];

        return [
            'user_id' => User::factory(),
            'reviewable_id' => 1, // Will be overridden
            'reviewable_type' => 'farm',
            'rating' => $this->faker->numberBetween(3, 5),
            'comment' => $this->faker->randomElement($arabicComments),
            'created_at' => $this->faker->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
