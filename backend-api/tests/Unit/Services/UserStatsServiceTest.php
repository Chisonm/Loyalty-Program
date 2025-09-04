<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\RequirementType;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserStats;
use App\Queries\BadgeQueries;
use App\Services\UserStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use Tests\TestCase;

final class UserStatsServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserStatsService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new UserStatsService(new BadgeQueries());
        $this->user = User::factory()->create();
    }

    public function test_update_stats_when_purchase_made_creates_new_user_stats(): void
    {
        $purchase = Purchase::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 100.50,
        ]);

        $this->service->updateStatsWhenPurchaseMade($this->user, $purchase);

        $this->assertDatabaseHas('user_stats', [
            'user_id' => $this->user->id,
            'total_purchases' => 1,
            'total_spent' => 100.50,
            'first_purchase_at' => $purchase->created_at,
            'last_purchase_at' => $purchase->created_at,
        ]);
    }

    public function test_update_stats_when_purchase_made_updates_existing_stats(): void
    {
        $firstPurchase = Purchase::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 50.00,
            'created_at' => now()->subDay(),
        ]);

        // Create initial stats
        $this->service->updateStatsWhenPurchaseMade($this->user, $firstPurchase);

        $secondPurchase = Purchase::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 75.25,
        ]);

        $this->service->updateStatsWhenPurchaseMade($this->user, $secondPurchase);

        $this->assertDatabaseHas('user_stats', [
            'user_id' => $this->user->id,
            'total_purchases' => 2,
            'total_spent' => 125.25,
            'first_purchase_at' => $firstPurchase->created_at,
            'last_purchase_at' => $secondPurchase->created_at,
        ]);
    }

    public function test_get_user_next_badge_returns_next_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 5,
        ]);

        Badge::factory()->create(['requirement' => 3, 'is_active' => true]);
        $nextBadge = Badge::factory()->create(['requirement' => 10, 'is_active' => true]);
        Badge::factory()->create(['requirement' => 20, 'is_active' => true]);

        $result = $this->service->getUserNextBadge($this->user);

        $this->assertEquals($nextBadge->id, $result->id);
        $this->assertEquals(10, $result->requirement);
    }

    public function test_get_user_next_badge_returns_null_when_no_next_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 100,
        ]);

        Badge::factory()->create(['requirement' => 5, 'is_active' => true]);
        Badge::factory()->create(['requirement' => 50, 'is_active' => true]);

        $result = $this->service->getUserNextBadge($this->user);

        $this->assertNull($result);
    }

    public function test_get_user_current_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 15,
        ]);

        Badge::factory()->create(['requirement' => 5, 'is_active' => true]);
        $currentBadge = Badge::factory()->create(['requirement' => 10, 'is_active' => true]);
        Badge::factory()->create(['requirement' => 20, 'is_active' => true]);

        $result = $this->service->getUserCurrentBadge($this->user);

        $this->assertEquals($currentBadge->id, $result->id);
        $this->assertEquals(10, $result->requirement);
    }

    public function test_get_user_remaining_to_unlock_next_badge_with_next_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 7,
        ]);

        Badge::factory()->create(['requirement' => 10, 'is_active' => true]);

        $result = $this->service->getUserRemainingToUnlockNextBadge($this->user);

        $this->assertEquals(3, $result);
    }

    public function test_get_user_remaining_to_unlock_next_badge_without_next_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 100,
        ]);

        Badge::factory()->create(['requirement' => 50, 'is_active' => true]);

        $result = $this->service->getUserRemainingToUnlockNextBadge($this->user);

        $this->assertEquals(0, $result);
    }

    public function test_get_user_remaining_to_unlock_next_badge_when_already_qualified(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 15,
        ]);

        Badge::factory()->create(['requirement' => 10, 'is_active' => true]);

        $result = $this->service->getUserRemainingToUnlockNextBadge($this->user);

        $this->assertEquals(0, $result);
    }

    public function test_get_user_unlocked_achievements_for_purchases_count(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 10,
            'total_spent' => 500.00,
        ]);

        $achievement = Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
        ]);

        $this->user->achievements()->attach($achievement);

        $result = $this->service->getUserUnlockedAchievements($this->user);

        $this->assertCount(1, $result);
        $achievementResult = $result->first();
        $this->assertEquals(5.0, $achievementResult->current_progress);
        $this->assertEquals(100.0, $achievementResult->progress_percentage);
        $this->assertEquals(0, $achievementResult->remaining);
    }

    public function test_get_user_unlocked_achievements_for_total_spent(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 5,
            'total_spent' => 1000.00,
        ]);

        $achievement = Achievement::factory()->create([
            'requirement_type' => RequirementType::TOTAL_SPENT,
            'requirement_value' => 500.00,
        ]);

        $this->user->achievements()->attach($achievement);

        $result = $this->service->getUserUnlockedAchievements($this->user);

        $this->assertCount(1, $result);
        $achievementResult = $result->first();
        $this->assertEquals(500.0, $achievementResult->current_progress);
        $this->assertEquals(100.0, $achievementResult->progress_percentage);
        $this->assertEquals(0, $achievementResult->remaining);
    }

    public function test_get_user_unlocked_achievements_when_progress_exceeds_requirement(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 20,
            'total_spent' => 500.00,
        ]);

        $achievement = Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 10.00,
        ]);

        $this->user->achievements()->attach($achievement);

        $result = $this->service->getUserUnlockedAchievements($this->user);

        $this->assertCount(1, $result);
        $achievementResult = $result->first();
        $this->assertEquals(10.0, $achievementResult->current_progress); // Capped at requirement
        $this->assertEquals(100.0, $achievementResult->progress_percentage);
        $this->assertEquals(0, $achievementResult->remaining);
    }

    public function test_get_user_unlocked_achievements_with_no_stats(): void
    {
        $achievement = Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
        ]);

        $this->user->achievements()->attach($achievement);

        $result = $this->service->getUserUnlockedAchievements($this->user);

        $this->assertCount(1, $result);
        $achievementResult = $result->first();
        $this->assertEquals(0, $achievementResult->current_progress);
        $this->assertEquals(0.0, $achievementResult->progress_percentage);
        $this->assertEquals(5, $achievementResult->remaining);
    }

    public function test_get_user_next_available_achievements(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 3,
            'total_spent' => 150.00,
        ]);

        $unlockedAchievement = Achievement::factory()->create([
            'key' => 'first_purchase',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 1.00,
        ]);

        $availableAchievement1 = Achievement::factory()->create([
            'key' => 'loyal_customer',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
        ]);

        $availableAchievement2 = Achievement::factory()->create([
            'key' => 'big_spender',
            'requirement_type' => RequirementType::TOTAL_SPENT,
            'requirement_value' => 200.00,
        ]);

        $this->user->achievements()->attach($unlockedAchievement);

        $result = $this->service->getUserNextAvailableAchievements($this->user);

        $this->assertCount(2, $result);

        $loyalCustomer = $result->firstWhere('key', 'loyal_customer');
        $this->assertEquals(3, $loyalCustomer->current_progress);
        $this->assertEquals(60.0, $loyalCustomer->progress_percentage);
        $this->assertEquals(2, $loyalCustomer->remaining);

        $bigSpender = $result->firstWhere('key', 'big_spender');
        $this->assertEquals(150, $bigSpender->current_progress);
        $this->assertEquals(75.0, $bigSpender->progress_percentage);
        $this->assertEquals(50, $bigSpender->remaining);
    }

    public function test_get_user_next_available_achievements_limits_to_five(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 1,
        ]);

        Achievement::factory()->count(7)->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 10.00,
        ]);

        $result = $this->service->getUserNextAvailableAchievements($this->user);

        $this->assertCount(5, $result);
    }

    public function test_get_user_next_available_achievements_orders_by_requirement_value(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 1,
        ]);

        Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 10.00,
        ]);

        Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
        ]);

        Achievement::factory()->create([
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 15.00,
        ]);

        $result = $this->service->getUserNextAvailableAchievements($this->user);

        $this->assertEquals(5.0, $result->first()->requirement_value);
        $this->assertEquals(10.0, $result->skip(1)->first()->requirement_value);
        $this->assertEquals(15.0, $result->last()->requirement_value);
    }

    public function test_get_user_stats_model_creates_default_stats(): void
    {
        $this->assertDatabaseMissing('user_stats', [
            'user_id' => $this->user->id,
        ]);

        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getUserStatsModel');
        $method->setAccessible(true);

        $userStats = $method->invoke($this->service, $this->user);

        $this->assertInstanceOf(UserStats::class, $userStats);
        $this->assertEquals($this->user->id, $userStats->user_id);
        $this->assertEquals(0, $userStats->total_purchases);
        $this->assertEquals(0.00, $userStats->total_spent);
        $this->assertEquals('bronze', $userStats->current_badge_key);
        $this->assertNull($userStats->first_purchase_at);
        $this->assertNull($userStats->last_purchase_at);
    }

    public function test_get_user_stats_model_returns_existing_stats(): void
    {
        $existingStats = UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 5,
            'total_spent' => 250.00,
        ]);

        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getUserStatsModel');
        $method->setAccessible(true);

        $userStats = $method->invoke($this->service, $this->user);

        $this->assertEquals($existingStats->id, $userStats->id);
        $this->assertEquals(5, $userStats->total_purchases);
        $this->assertEquals(250.00, $userStats->total_spent);
    }
}
