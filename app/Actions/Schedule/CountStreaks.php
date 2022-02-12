<?php

namespace App\Actions\Schedule;

use App\Models\StreakCount;
use App\Models\User;

class CountStreaks
{
    public function __invoke()
    {
        $this->incrementRunningStreaks();
        $this->createNewStreaks();
        $this->endStreaks();
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
        StreakCount::current()
            ->whereIn('user_id', User::withoutUnfinishedChores()->get()->map->id)
            ->increment('count');
    }

    protected function endStreaks()
    {
        StreakCount::whereIn('user_id', User::withUnfinishedChores()->get()->map->id)
            ->update([
                'ended_at' => today()->subDay(),
            ]);
    }
}
