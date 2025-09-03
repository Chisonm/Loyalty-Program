<?php

declare(strict_types=1);

namespace App\Http\Resources\APIs\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'current_progress' => $this->current_progress,
            'progress_percentage' => $this->progress_percentage,
            'remaining' => $this->remaining,
        ];
    }
}
