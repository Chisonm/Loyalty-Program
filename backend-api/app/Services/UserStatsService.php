<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Purchase;
use App\Models\User;
use App\Models\UserStats;
use App\Queries\BadgeQueries;

final class UserStatsService
{
    public function __construct(
        private BadgeQueries $badgeQueries
    ) {}

    public function updateStatsWhenPurchaseMade(User $user, Purchase $purchase): void
    {
        $userStats = $this->getUserStatsModel($user);
        $userStats->increment('total_purchases');
        $userStats->increment('total_spent', (float) $purchase->amount);
        $userStats->last_purchase_at = $purchase->created_at;

        if (! $userStats->first_purchase_at) {
            $userStats->first_purchase_at = $purchase->created_at;
        }

        $userStats->save();
    }

    private function getUserStatsModel(User $user): UserStats
    {
        return UserStats::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total_purchases' => 0,
                'total_spent' => 0.00,
                'current_badge_key' => 'bronze',
                'first_purchase_at' => null,
                'last_purchase_at' => null,
            ]
        );
    }
}
