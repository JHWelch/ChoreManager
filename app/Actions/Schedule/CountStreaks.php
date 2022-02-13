<?php

namespace App\Actions\Schedule;

use App\Models\StreakCount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;

class CountStreaks
{
    public function __invoke()
    {
        $this->incrementRunningStreaks();
        $this->createNewStreaks();
        $this->endStreaks();
    }

    protected function incrementRunningStreaks()
    {
        $this->incrementRunningStreakFor('user_id', User::class);
        $this->incrementRunningStreakFor('team_id', Team::class);
    }

    protected function createNewStreaks()
    {
        $this->createNewStreakFor('user_id', User::class);
        $this->createNewStreakFor('team_id', Team::class);
    }

    protected function endStreaks()
    {
        $this->endStreaksFor('user_id', User::class);
        $this->endStreaksFor('team_id', Team::class);
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

    protected function createNewStreakFor($class_id, $class)
    {
        StreakCount::insert(
            $class::withoutUnfinishedChores(today()->subDay())
                ->whereDoesntHave('currentStreak')
                ->get()
                ->map(fn ($class_instance) => [
                    $class_id    => $class_instance->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
                ->toArray(),
        );
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
