<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Purchase>
 */
final class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->optional()->sentence(),
            'transaction_id' => $this->faker->optional()->uuid(),
            'payment_method' => $this->faker->optional()->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'processed_at' => $this->faker->optional()->dateTimeThisYear(),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => now(),
        ];
    }
}
