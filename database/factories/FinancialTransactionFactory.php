<?php

namespace Database\Factories;

use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialTransactionFactory extends Factory
{
    protected $model = FinancialTransaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reference_type' => $this->faker->randomElement(['farm_booking', 'supply_order', 'transport']),
            'reference_id' => $this->faker->numberBetween(1, 1000),
            'transaction_type' => $this->faker->randomElement(['credit', 'debit']),
            'amount' => $this->faker->randomFloat(2, 5, 500),
            'description' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
