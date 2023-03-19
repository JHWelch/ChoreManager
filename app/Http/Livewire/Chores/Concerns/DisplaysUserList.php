<?php

namespace App\Http\Livewire\Chores\Concerns;

use Illuminate\Support\Facades\Auth;

trait DisplaysUserList
{
    public array $user_options = [];

    public function mountDisplaysUserList(): void
    {
        $this->user_options = array_values(Auth::user()
            ->currentTeam
            ->allUsers()
            ->sortBy(fn ($user) => $user->name)
            ->map(fn ($user) => [
                'id'                => $user->id,
                'name'              => $user->name,
                'profile_photo_url' => $user->profile_photo_url,
            ])
            ->toArray());
    }
}
