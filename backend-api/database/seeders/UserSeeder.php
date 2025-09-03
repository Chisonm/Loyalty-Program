<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // test user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        // update user stats with default badge for testing
        $user->userStats()->create([
            'current_badge_key' => 'bronze',
            'total_purchases' => 0,
            'total_spent' => 0.00,
        ]);
        // get the first badge (bronze) and attach it to the user for testing
        $badge = Badge::where('key', 'bronze')->first();
        $user->badges()->attach($badge->id);

        User::factory()->count(5)->create();
    }
}
