<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/** @mixin \App\Models\StreakCount */
class StreakCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>|Arrayable|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'count' => $this->count,
            'team_id' => $this->team_id,
        ];
    }
}
