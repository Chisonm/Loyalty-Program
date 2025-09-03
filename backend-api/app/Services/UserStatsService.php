<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserStats;
use App\Queries\BadgeQueries;
use Illuminate\Database\Eloquent\Collection;

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

    public function getUserNextBadge(User $user): ?Badge
    {
        $userStats = $this->getUserStatsModel($user);

        return $this->badgeQueries->getNextBadge($userStats->total_purchases);
    }

    public function getUserCurrentBadge(User $user): ?Badge
    {
        return $this->badgeQueries->getCurrentBadge($user);
    }

    public function getUserRemainingToUnlockNextBadge(User $user): int
    {
        $nextBadge = $this->getUserNextBadge($user);
        if (! $nextBadge) {
            return 0;
        }

        $userStats = $this->getUserStatsModel($user);

        return max(0, $nextBadge->requirement - $userStats->total_purchases);
    }

    public function getUserUnlockedAchievements(User $user): Collection
    {
        $userStats = $this->getUserStatsModel($user);
        $totalPurchases = $userStats->total_purchases ?? 0;
        $totalSpent = $userStats->total_spent ?? 0;

        return $user->achievements()->where('is_active', true)->get()
            ->map(function ($achievement) use ($totalPurchases, $totalSpent) {
                $progress = match ($achievement->requirement_type->value) {
                    'purchases_count' => $totalPurchases,
                    'total_spent' => $totalSpent,
                    default => 0,
                };

                $requirementValue = (float) $achievement->requirement_value;
                $progressPercentage = $requirementValue > 0 ? min(100, ($progress / $requirementValue) * 100) : 0;

                $achievement->current_progress = min($progress, $requirementValue);
                $achievement->progress_percentage = round($progressPercentage, 2);
                $achievement->remaining = max(0, $requirementValue - $progress);

                return $achievement;
            });
    }

    public function getUserNextAvailableAchievements(User $user)
    {
        $userStats = $this->getUserStatsModel($user);
        $totalPurchases = $userStats->total_purchases ?? 0;
        $totalSpent = $userStats->total_spent ?? 0;

        $unlockedAchievementKeys = $user->achievements()
            ->where('is_active', true)
            ->pluck('key')
            ->toArray();

        return Achievement::where('is_active', true)
            ->whereNotIn('key', $unlockedAchievementKeys)
            ->orderBy('requirement_value', 'asc')
            ->take(5)
            ->get()
            ->map(function ($achievement) use ($totalPurchases, $totalSpent) {
                $progress = match ($achievement->requirement_type->value) {
                    'purchases_count' => $totalPurchases,
                    'total_spent' => $totalSpent,
                    default => 0,
                };

                $requirementValue = (float) $achievement->requirement_value;
                $progressPercentage = $requirementValue > 0 ? ($progress / $requirementValue) * 100 : 0;

                $achievement->current_progress = $progress;
                $achievement->progress_percentage = round($progressPercentage, 2);
                $achievement->remaining = max(0, $requirementValue - $progress);

                return $achievement;
            });
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
