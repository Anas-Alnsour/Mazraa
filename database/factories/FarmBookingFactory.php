<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Farm;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmBooking>
 */
class FarmBookingFactory extends Factory
{
    protected $model = \App\Models\FarmBooking::class;

    public function definition(): array
    {
        $start = Carbon::now()->addDays($this->faker->numberBetween(1, 30))->setHour($this->faker->numberBetween(8, 18))->setMinute(0);
        $end = (clone $start)->addHours(12);

        return [
            'user_id' => User::factory(),   // إذا لم يكن موجود سيتم إنشاء مستخدم جديد
            'farm_id' => Farm::factory(),   // إذا لم يكن موجود سيتم إنشاء مزرعة جديدة
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $this->faker->randomElement(['Birthday', 'Wedding', 'Other', null]),
        ];
    }
}
