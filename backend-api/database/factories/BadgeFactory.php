<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Badge>
 */
final class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'icon' => $this->faker->imageUrl(100, 100, 'business'),
            'requirement' => $this->faker->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
