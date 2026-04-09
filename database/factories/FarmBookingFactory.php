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
        // Data spanning the last 6 months to 2 months ahead
        $startDate = $this->faker->dateTimeBetween('-6 months', '+2 months');
        $start = Carbon::instance($startDate)->setHour($this->faker->numberBetween(8, 18))->setMinute(0);
        $end = (clone $start)->addHours(12);

        $status = $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled', 'pending_payment', 'pending_verification']);
        $paymentStatus = ($status === 'completed' || $status === 'confirmed') ? 'paid' : $this->faker->randomElement(['pending', 'paid', 'failed', 'refunded']);
        $paymentMethod = $this->faker->randomElement(['Stripe', 'CliQ', 'Cash']);

        return [
            'user_id' => User::where('role', 'user')->inRandomOrder()->first()->id ?? User::factory(),
            'farm_id' => Farm::inRandomOrder()->first()->id ?? Farm::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'event_type' => $this->faker->randomElement(['Birthday', 'Family Gathering', 'Wedding', 'Weekend Stay', 'Corporate Retreat']),
            'status' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentMethod === 'CliQ' ? 'REF-' . $this->faker->unique()->numberBetween(100000, 999999) : null,
            'total_price' => $this->faker->numberBetween(100, 500),
            'created_at' => (clone $start)->subDays(rand(1, 15)),
        ];
    }
}
