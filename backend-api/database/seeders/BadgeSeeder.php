<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

final class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'key' => 'bronze',
                'name' => 'Bronze',
                'description' => 'Welcome to our loyalty program!',
                'icon' => 'Bronze',
                'requirement' => 0,
            ],
            [
                'key' => 'silver',
                'name' => 'Silver',
                'description' => 'You\'re getting the hang of this!',
                'icon' => 'Silver',
                'requirement' => 3,
            ],
            [
                'key' => 'gold',
                'name' => 'Gold',
                'description' => 'You\'re a valued customer!',
                'icon' => 'Gold',
                'requirement' => 10,
            ],
            [
                'key' => 'diamond',
                'name' => 'Diamond',
                'description' => 'You\'re a valued customer!',
                'icon' => 'Diamond',
                'requirement' => 15,
            ],
            [
                'key' => 'ruby',
                'name' => 'Ruby',
                'description' => 'You\'re our VIP customer!',
                'icon' => 'Ruby',
                'requirement' => 20,
            ],
            [
                'key' => 'platinum',
                'name' => 'Platinum',
                'description' => 'You\'re our VIP customer!',
                'icon' => 'Platinum',
                'requirement' => 25,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['key' => $badge['key']],
                $badge
            );
        }
    }
}
