<?php

declare(strict_types=1);

namespace Tests\Feature\APIs\Users;

use App\Enums\RequirementType;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use App\Models\UserStats;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserAchievementsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_get_user_achievements_returns_successful_response(): void
    {
        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User achievements retrieved successfully',
                'data' => [
                    'unlocked_achievements' => [],
                    'next_available_achievements' => [],
                    'current_badge' => null,
                    'next_badge' => null,
                    'remaining_to_unlock_next_badge' => 0,
                ],
            ]);
    }

    public function test_get_user_achievements_with_unlocked_achievements(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 10,
            'total_spent' => 500.00,
        ]);

        $unlockedAchievement = Achievement::factory()->create([
            'key' => 'first_purchase',
            'name' => 'First Purchase',
            'description' => 'Complete your very first purchase',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 1.00,
            'is_active' => true,
        ]);

        $this->user->achievements()->attach($unlockedAchievement);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User achievements retrieved successfully',
                'data' => [
                    'unlocked_achievements' => [
                        [
                            'id' => $unlockedAchievement->id,
                            'key' => 'first_purchase',
                            'name' => 'First Purchase',
                            'description' => 'Complete your very first purchase',
                            'is_active' => true,
                            'current_progress' => 1,
                            'progress_percentage' => 100,
                            'remaining' => 0,
                        ],
                    ],
                ],
            ]);
    }

    public function test_get_user_achievements_with_next_available_achievements(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 3,
            'total_spent' => 150.00,
        ]);

        Achievement::factory()->create([
            'key' => 'loyal_customer',
            'name' => 'Loyal Customer',
            'description' => 'Make 5 purchases',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonPath('data.next_available_achievements.0.key', 'loyal_customer')
            ->assertJsonPath('data.next_available_achievements.0.name', 'Loyal Customer')
            ->assertJsonPath('data.next_available_achievements.0.current_progress', 3)
            ->assertJsonPath('data.next_available_achievements.0.progress_percentage', 60)
            ->assertJsonPath('data.next_available_achievements.0.remaining', 2);
    }

    public function test_get_user_achievements_with_current_badge(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 15,
        ]);

        Badge::factory()->create(['requirement' => 5, 'is_active' => true]);
        $currentBadge = Badge::factory()->create([
            'key' => 'silver',
            'name' => 'Silver Badge',
            'requirement' => 10,
            'is_active' => true,
        ]);
        Badge::factory()->create(['requirement' => 20, 'is_active' => true]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'current_badge' => 'silver',
                ],
            ]);
    }

    public function test_get_user_achievements_with_next_badge_and_remaining(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 7,
        ]);

        Badge::factory()->create(['requirement' => 5, 'is_active' => true]);
        $nextBadge = Badge::factory()->create([
            'key' => 'gold',
            'name' => 'Gold Badge',
            'requirement' => 10,
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'next_badge' => 'gold',
                    'remaining_to_unlock_next_badge' => 3,
                ],
            ]);
    }

    public function test_get_user_achievements_with_complete_data(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 8,
            'total_spent' => 400.00,
        ]);

        $unlockedAchievement = Achievement::factory()->create([
            'key' => 'first_purchase',
            'name' => 'First Purchase',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 1.00,
            'is_active' => true,
        ]);

        $availableAchievement = Achievement::factory()->create([
            'key' => 'big_spender',
            'name' => 'Big Spender',
            'requirement_type' => RequirementType::TOTAL_SPENT,
            'requirement_value' => 500.00,
            'is_active' => true,
        ]);

        $currentBadge = Badge::factory()->create([
            'key' => 'bronze',
            'name' => 'Bronze Badge',
            'requirement' => 5,
            'is_active' => true,
        ]);

        $nextBadge = Badge::factory()->create([
            'key' => 'silver',
            'name' => 'Silver Badge',
            'requirement' => 10,
            'is_active' => true,
        ]);

        $this->user->achievements()->attach($unlockedAchievement);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User achievements retrieved successfully',
                'data' => [
                    'unlocked_achievements' => [
                        [
                            'id' => $unlockedAchievement->id,
                            'key' => 'first_purchase',
                            'name' => 'First Purchase',
                            'current_progress' => 1,
                            'progress_percentage' => 100,
                            'remaining' => 0,
                        ],
                    ],
                    'next_available_achievements' => [
                        [
                            'id' => $availableAchievement->id,
                            'key' => 'big_spender',
                            'name' => 'Big Spender',
                            'current_progress' => 400,
                            'progress_percentage' => 80,
                            'remaining' => 100,
                        ],
                    ],
                    'current_badge' => 'bronze',
                    'next_badge' => 'silver',
                    'remaining_to_unlock_next_badge' => 2,
                ],
            ]);
    }

    public function test_get_user_achievements_with_no_user_stats(): void
    {
        Achievement::factory()->create([
            'key' => 'first_purchase',
            'name' => 'First Purchase',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 1.00,
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonPath('data.next_available_achievements.0.current_progress', 0)
            ->assertJsonPath('data.next_available_achievements.0.progress_percentage', 0)
            ->assertJsonPath('data.next_available_achievements.0.remaining', 1);
    }

    public function test_get_user_achievements_with_inactive_achievements(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 5,
        ]);

        Achievement::factory()->create([
            'key' => 'active_achievement',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 10.00,
            'is_active' => true,
        ]);

        Achievement::factory()->create([
            'key' => 'inactive_achievement',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 10.00,
            'is_active' => false,
        ]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.next_available_achievements')
            ->assertJsonPath('data.next_available_achievements.0.key', 'active_achievement');
    }

    public function test_get_user_achievements_with_inactive_badges(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 15,
        ]);

        Badge::factory()->create(['requirement' => 5, 'is_active' => true]);
        $currentBadge = Badge::factory()->create(['requirement' => 10, 'is_active' => true]);
        $nextBadge = Badge::factory()->create(['requirement' => 20, 'is_active' => true]);

        Badge::factory()->create(['requirement' => 12, 'is_active' => false]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonPath('data.current_badge', $currentBadge->key)
            ->assertJsonPath('data.next_badge', $nextBadge->key)
            ->assertJsonPath('data.remaining_to_unlock_next_badge', 5);
    }

    public function test_get_user_achievements_with_nonexistent_user(): void
    {
        $response = $this->getJson('/api/users/99999/achievements');

        $response->assertStatus(404);
    }

    public function test_get_user_achievements_response_structure(): void
    {
        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'unlocked_achievements',
                    'next_available_achievements',
                    'current_badge',
                    'next_badge',
                    'remaining_to_unlock_next_badge',
                ],
            ]);
    }

    public function test_get_user_achievements_with_uuid_user_id(): void
    {
        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200);
    }

    public function test_achievement_resource_includes_all_required_fields(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 10,
        ]);

        $achievement = Achievement::factory()->create([
            'key' => 'test_achievement',
            'name' => 'Test Achievement',
            'description' => 'Test Description',
            'requirement_type' => RequirementType::PURCHASES_COUNT,
            'requirement_value' => 5.00,
            'is_active' => true,
        ]);

        $this->user->achievements()->attach($achievement);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonPath('data.unlocked_achievements.0.id', $achievement->id)
            ->assertJsonPath('data.unlocked_achievements.0.key', 'test_achievement')
            ->assertJsonPath('data.unlocked_achievements.0.name', 'Test Achievement')
            ->assertJsonPath('data.unlocked_achievements.0.description', 'Test Description')
            ->assertJsonPath('data.unlocked_achievements.0.is_active', true)
            ->assertJsonPath('data.unlocked_achievements.0.current_progress', 5)
            ->assertJsonPath('data.unlocked_achievements.0.progress_percentage', 100)
            ->assertJsonPath('data.unlocked_achievements.0.remaining', 0);
    }

    public function test_badge_response_returns_string_keys(): void
    {
        UserStats::factory()->create([
            'user_id' => $this->user->id,
            'total_purchases' => 10,
        ]);

        $currentBadge = Badge::factory()->create([
            'key' => 'test_current_badge',
            'name' => 'Test Current Badge',
            'requirement' => 5,
            'is_active' => true,
        ]);

        $nextBadge = Badge::factory()->create([
            'key' => 'test_next_badge',
            'name' => 'Test Next Badge',
            'requirement' => 15,
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/users/{$this->user->id}/achievements");

        $response->assertStatus(200)
            ->assertJsonPath('data.current_badge', 'test_current_badge')
            ->assertJsonPath('data.next_badge', 'test_next_badge');
    }
}
