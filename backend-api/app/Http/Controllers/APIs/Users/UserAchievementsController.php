<?php

declare(strict_types=1);

namespace App\Http\Controllers\APIs\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\APIs\User\AchievementResource;
use App\Models\User;
use App\Services\UserStatsService;
use Illuminate\Http\JsonResponse;

final class UserAchievementsController extends Controller
{
    public function __construct(public readonly UserStatsService $userStatsService) {}

    public function __invoke(User $user): JsonResponse
    {
        $unlockedAchievements = $this->userStatsService->getUserUnlockedAchievements($user);
        $nextAvailableAchievements = $this->userStatsService->getUserNextAvailableAchievements($user);

        $currentBadge = $this->userStatsService->getUserCurrentBadge($user);
        $nextBadge = $this->userStatsService->getUserNextBadge($user);
        $remainingToUnlockNextBadge = $this->userStatsService->getUserRemainingToUnlockNextBadge($user);

        return $this->successResponse([
            'unlocked_achievements' => AchievementResource::collection($unlockedAchievements),
            'next_available_achievements' => AchievementResource::collection($nextAvailableAchievements),
            'current_badge' => $currentBadge ? $currentBadge->key : null,
            'next_badge' => $nextBadge ? $nextBadge->key : null,
            'remaining_to_unlock_next_badge' => (int) $remainingToUnlockNextBadge,
        ], 'User achievements retrieved successfully');
    }
}
