<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Badge;
use App\Models\User;

final class BadgeQueries
{
    public function getNextBadge(int $totalPurchases): ?Badge
    {
        return Badge::where('is_active', true)
            ->where('requirement', '>', $totalPurchases)
            ->orderBy('requirement', 'asc')
            ->first();
    }

    public function getCurrentBadge(User $user): ?Badge
    {
        return Badge::where('requirement', '<=', $user->userStats?->total_purchases ?? 0)
            ->where('is_active', true)
            ->latest('requirement')
            ->first();
    }

    public function userHasBadge(User $user, Badge $badge): bool
    {
        return $user->badges()->where('badge_id', $badge->id)->exists();
    }
}
