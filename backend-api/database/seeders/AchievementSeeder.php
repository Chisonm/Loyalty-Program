<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

final class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'key' => 'first_purchase',
                'name' => 'First Purchase',
                'description' => 'Complete your very first purchase',
                'icon' => 'Bag',
                'requirement_type' => 'purchases_count',
                'requirement_value' => 1,
            ],
            [
                'key' => 'big_spender',
                'name' => 'Big Spender',
                'description' => 'Spend ₦10,000 in total',
                'icon' => 'DollarSign',
                'requirement_type' => 'total_spent',
                'requirement_value' => 10000,
            ],
            [
                'key' => 'loyal_customer',
                'name' => 'Loyal Customer',
                'description' => 'Make 5 purchases',
                'icon' => 'Star',
                'requirement_type' => 'purchases_count',
                'requirement_value' => 5,
            ],
            [
                'key' => 'premium_shopper',
                'name' => 'Premium Shopper',
                'description' => 'Spend ₦50,000 in total',
                'icon' => 'Crown',
                'requirement_type' => 'total_spent',
                'requirement_value' => 50000,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['key' => $achievement['key']],
                $achievement
            );
        }
    }
}
