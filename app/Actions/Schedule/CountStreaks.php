<?php

namespace App\Actions\Schedule;

use App\Models\StreakCount;
use App\Models\Team;
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
        $users_without_streaks = User::withoutUnfinishedChores(today()->subDay())
            ->whereDoesntHave('currentStreak')
            ->get();

        $teams_without_streaks = Team::withoutUnfinishedChores(today()->subDay())
            ->whereDoesntHave('currentStreak')
            ->get();

        StreakCount::insert(
            $users_without_streaks->map(fn ($user) => ['user_id' => $user->id])->toArray(),
        );
        StreakCount::insert(
            $teams_without_streaks->map(fn ($team) => ['team_id' => $team->id])->toArray(),
        );
    }

    protected function incrementRunningStreaks()
    {
        StreakCount::current()
            ->whereIn(
                'user_id',
                User::withoutUnfinishedChores(today()->subDay())->get()->map->id
            )
            ->increment('count');

        StreakCount::current()
            ->whereIn(
                'team_id',
                Team::withoutUnfinishedChores(today()->subDay())->get()->map->id
            )
            ->increment('count');
    }

    protected function endStreaks()
    {
        StreakCount::whereIn(
            'user_id',
            User::withUnfinishedChores(today()->subDay())->get()->map->id
        )
            ->update(['ended_at' => today()->subDay()]);

        StreakCount::whereIn(
            'team_id',
            Team::withUnfinishedChores(today()->subDay())->get()->map->id
        )
            ->update(['ended_at' => today()->subDay()]);
    }
}
