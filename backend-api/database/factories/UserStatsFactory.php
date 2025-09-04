<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\UserStats;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserStats>
 */
final class UserStatsFactory extends Factory
{
    protected $model = UserStats::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total_purchases' => $this->faker->numberBetween(0, 50),
            'total_spent' => $this->faker->randomFloat(2, 0, 5000),
            'current_badge_key' => 'bronze',
            'first_purchase_at' => $this->faker->optional()->dateTimeThisYear(),
            'last_purchase_at' => $this->faker->optional()->dateTimeThisYear(),
        ];
    }
}
