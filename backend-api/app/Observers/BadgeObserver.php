<?php

declare(strict_types=1);

namespace App\Observers;

use App\Events\BadgeUnlocked;
use App\Models\Badge;
use App\Models\User;
use App\Queries\BadgeQueries;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BadgeObserver
{
    public function __construct(public BadgeQueries $badgeQueries) {}

    public function checkAndUnlockBadges(User $user): ?Badge
    {
        try {
            $user->refresh();
            $totalPurchases = $user->userStats?->total_purchases ?? 0;

            Log::info('Badge check debug', [
                'user_id' => $user->id,
                'total_purchases' => $totalPurchases,
                'current_badge_key' => $user->userStats?->current_badge_key,
            ]);

            $earnedBadge = $this->badgeQueries->getCurrentBadge($user);

            Log::info('Badge check result', [
                'user_id' => $user->id,
                'total_purchases' => $totalPurchases,
                'earned_badge' => $earnedBadge?->key,
                'earned_badge_requirement' => $earnedBadge?->requirement,
            ]);
            if (! $earnedBadge) {
                return null;
            }

            return $this->upgradeBadge($user, $earnedBadge);
        } catch (Throwable $e) {
            Log::error('Error checking or upgrading badge', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function upgradeBadge(User $user, Badge $earnedBadge): Badge
    {
        try {
            // Update the user's current badge key
            $user->userStats->update(['current_badge_key' => $earnedBadge->key]);
            if (! $this->badgeQueries->userHasBadge($user, $earnedBadge)) {
                // Update the new badge for the user
                $user->badges()->attach($earnedBadge->id);
                // we will use this event to give the user a cashback when a new badge is unlocked
                BadgeUnlocked::dispatch($user, $earnedBadge);
            }

            return $earnedBadge;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
