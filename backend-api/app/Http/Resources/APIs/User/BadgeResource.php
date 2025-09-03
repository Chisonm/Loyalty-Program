<?php

declare(strict_types=1);

namespace App\Http\Resources\APIs\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class BadgeResource extends JsonResource
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
            'requirement' => $this->requirement,
            'processed_at' => $this->processed_at,
        ];
    }
}
