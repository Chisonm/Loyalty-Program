<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RequirementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Achievement extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'icon',
        'requirement_type',
        'requirement_value',
        'is_active',
    ];

    protected $casts = [
        'requirement_value' => 'decimal:2',
        'is_active' => 'boolean',
        'requirement_type' => RequirementType::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withTimestamps();
    }

    public function checkRequirement(User $user): bool
    {
        return match ($this->requirement_type->value) {
            'purchases_count' => ($user->userStats?->total_purchases ?? 0) >= $this->requirement_value,
            'total_spent' => ($user->userStats?->total_spent ?? 0) >= $this->requirement_value,
            default => false,
        };
    }
}
