<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RequirementType;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achievement>
 */
final class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->imageUrl(100, 100, 'business'),
            'requirement_type' => $this->faker->randomElement(RequirementType::cases()),
            'requirement_value' => $this->faker->randomFloat(2, 1, 1000),
            'is_active' => true,
        ];
    }
}
