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
        // 50% chance for past bookings, 50% for future
        $isPast = $this->faker->boolean();
        
        if ($isPast) {
            $start = Carbon::now()->subDays($this->faker->numberBetween(1, 60))->setHour($this->faker->numberBetween(8, 18))->setMinute(0);
        } else {
            $start = Carbon::now()->addDays($this->faker->numberBetween(1, 60))->setHour($this->faker->numberBetween(8, 18))->setMinute(0);
        }

        $end = (clone $start)->addHours(12);

        return [
            'user_id' => User::where('role', 'user')->first()->id ?? User::factory(),
            'farm_id' => Farm::first()->id ?? Farm::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $this->faker->randomElement(['Birthday', 'Family Gathering', 'Wedding', 'Weekend Stay']),
            'status' => $isPast ? 'completed' : 'confirmed',
            'payment_status' => 'paid',
            'total_price' => $this->faker->numberBetween(100, 300),
        ];
    }
}
