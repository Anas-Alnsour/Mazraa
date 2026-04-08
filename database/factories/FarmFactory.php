<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Farm;
use App\Models\User;
use App\Models\FarmImage;

class FarmFactory extends Factory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Farm',
            'location' => $this->faker->city . ', Jordan', // خليناها بالأردن للمنطق
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(5, 50),
            'price_per_morning_shift' => $this->faker->numberBetween(50, 150),
            'price_per_evening_shift' => $this->faker->numberBetween(60, 200),
            'price_per_full_day' => $this->faker->numberBetween(100, 300),
            'governorate' => $this->faker->randomElement(['Amman', 'Jerash', 'Irbid', 'Zarqa', 'Dead Sea', 'Balqa']),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'main_image' => 'https://images.unsplash.com/photo-1516253593875-bd7ba052fbc5?q=80&w=1000&auto=format&fit=crop', // صورة مزرعة حقيقية أحسن من العشوائي
            'owner_id' => User::factory()->create(['role' => 'farm_owner'])->id,
            'latitude' => $this->faker->latitude(29.0, 33.0), // إحداثيات قريبة للأردن
            'longitude' => $this->faker->longitude(35.0, 39.0),
            'commission_rate' => $this->faker->randomElement([null, 5.0, 10.0, 15.0]),
            'is_approved' => true, // 👈 التعديل الأهم عشان تظهر بالـ Explore
            'status' => 'active', // 👈 لتفعيل ظهورها الفوري بعد البحث الجديد
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Farm $farm) {
            // ملاحظة: تأكد إنه عندك FarmImageFactory عشان هاد الكود يشتغل
            FarmImage::factory(4)->create([
                'farm_id' => $farm->id,
            ]);
        });
    }
}
