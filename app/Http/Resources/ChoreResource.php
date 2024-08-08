<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Chore */
class ChoreResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'team_id' => $this->team_id,
            'frequency_id' => $this->frequency_id,
            'frequency_interval' => $this->frequency_interval,
            'frequency_day_of' => $this->frequency_day_of,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'next_due_user_id' => $this->nextChoreInstance?->user_id,
            'next_due_date' => $this->next_due_date?->toDateString(),
            'due_date_updated_at' => $this->due_date_updated_at,
        ];
    }
}
