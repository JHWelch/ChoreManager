<?php

namespace App\Actions\Schedule;

use App\Models\StreakCount;
use App\Models\User;

class CountStreaks
{
    public function __invoke()
    {
        $this->createNewStreaks();
        $this->incrementRunningStreaks();
    }

    protected function createNewStreaks()
    {
        $users_without_streaks = User::doesntHave('currentStreak')->get();

        StreakCount::insert(
            $users_without_streaks->map(fn ($user) => ['user_id' => $user->id])->toArray()
        );
    }

    protected function incrementRunningStreaks()
    {
        StreakCount::current()->increment('count');
    }
}
