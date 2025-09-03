<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserStats extends Model
{
    protected $fillable = [
        'user_id',
        'total_purchases',
        'total_spent',
        'current_badge_key',
        'last_purchase_at',
        'first_purchase_at',
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'total_purchases' => 'integer',
        'last_purchase_at' => 'datetime',
        'first_purchase_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentBadge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'current_badge_key', 'key');
    }
}
