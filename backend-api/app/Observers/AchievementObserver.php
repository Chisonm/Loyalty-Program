<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;

final class AchievementObserver
{
    public function checkAndUnlockAchievements(User $user): array
    {
        $unlockedAchievements = [];

        $availableAchievements = Achievement::where('is_active', true)
            ->whereDoesntHave('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($availableAchievements as $achievement) {
            if ($achievement->checkRequirement($user)) {
                $this->unlockAchievement($user, $achievement);
                $unlockedAchievements[] = $achievement;
            }
        }

        return $unlockedAchievements;
    }

    public function unlockAchievement(User $user, Achievement $achievement): void
    {
        if ($user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            return;
        }

        $user->achievements()->attach($achievement->id);

        AchievementUnlocked::dispatch($user, $achievement);
    }
}
