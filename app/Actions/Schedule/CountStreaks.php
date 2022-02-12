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
        StreakCount::insert(
            $this->withoutStreaks(User::class)
                ->map(fn ($user) => ['user_id' => $user->id])
                ->toArray(),
        );
        StreakCount::insert(
            $this->withoutStreaks(Team::class)
                ->map(fn ($team) => ['team_id' => $team->id])
                ->toArray(),
        );
    }

    protected function withoutStreaks($class)
    {
        return $class::withoutUnfinishedChores(today()->subDay())
            ->whereDoesntHave('currentStreak')
            ->get();
    }

    protected function incrementRunningStreaks()
    {
        $this->incrementRunningStreakFor('user_id', User::class);
        $this->incrementRunningStreakFor('team_id', Team::class);
    }

    protected function incrementRunningStreakFor($class_id, $class)
    {
        StreakCount::current()
            ->whereIn(
                $class_id,
                $class::withoutUnfinishedChores(today()->subDay())->get()->map->id
            )
            ->increment('count');
    }

    protected function endStreaks()
    {
        $this->endStreaksFor('user_id', User::class);
        $this->endStreaksFor('team_id', Team::class);
    }

    protected function endStreaksFor($class_id, $class)
    {
        StreakCount::whereIn(
            $class_id,
            $class::withUnfinishedChores(today()->subDay())->get()->map->id
        )
            ->update(['ended_at' => today()->subDay()]);
    }
}
