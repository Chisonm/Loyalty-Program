<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RequirementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Metadata\Version\Requirement;

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
}
