<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *  @return array<string, mixed>|Arrayable|JsonSerializable
     */
    public function toArray(Request $request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_photo_path' => $this->profile_photo_path,
        ];
    }
}
