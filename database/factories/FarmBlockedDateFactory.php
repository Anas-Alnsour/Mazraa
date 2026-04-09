<?php

namespace Database\Factories;

use App\Models\FarmBlockedDate;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory;

class FarmBlockedDateFactory extends Factory
{
    protected $model = FarmBlockedDate::class;

    public function definition(): array
    {
        $reasons = [
            'صيانة دورية للمسبح',
            'أعمال دهان وترميم',
            'حجز خاص للعائلة',
            'إجازة سنوية للمالك',
            'تجهيز المزرعة لموسم الشتاء',
            'تصوير عمل فني',
            'صيانة الكهرباء والإنارة'
        ];

        return [
            'farm_id' => Farm::factory(),
            // التعديل هنا: إضافة unique() لمنع تكرار التواريخ وتجنب خطأ قاعدة البيانات
            'date' => $this->faker->unique()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'shift' => $this->faker->randomElement(['morning', 'evening', 'full_day']),
            'reason' => $this->faker->randomElement($reasons),
        ];
    }
}
