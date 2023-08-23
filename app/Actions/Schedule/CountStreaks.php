<?php

namespace App\Actions\Schedule;

use App\Models\StreakCount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;

class CountStreaks
{
    public function __invoke(): void
    {
        $this->incrementRunningStreaks();
        $this->createNewStreaks();
        $this->endStreaks();
    }

    protected function incrementRunningStreaks(): void
    {
        $this->incrementRunningStreakFor('user_id', User::class);
        $this->incrementRunningStreakFor('team_id', Team::class);
    }

    protected function createNewStreaks(): void
    {
        $this->createNewStreakFor('user_id', User::class);
        $this->createNewStreakFor('team_id', Team::class);
    }

    protected function endStreaks(): void
    {
        $this->endStreaksFor('user_id', User::class);
        $this->endStreaksFor('team_id', Team::class);
    }

    protected function incrementRunningStreakFor(string $class_id, string $class): void
    {
        StreakCount::current()
            ->whereIn(
                $class_id,
                $class::withoutUnfinishedChores(today()->subDay())->get()->map->id
            )
            ->increment('count');
    }

    protected function createNewStreakFor(string $class_id, string $class): void
    {
        StreakCount::insert(
            $class::withoutUnfinishedChores(today()->subDay())
                ->whereDoesntHave('currentStreak')
                ->get()
                ->map(fn ($class_instance) => [
                    $class_id => $class_instance->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
                ->toArray(),
        );
    }

    protected function endStreaksFor(string $class_id, string $class): void
    {
        StreakCount::whereIn(
            $class_id,
            $class::withUnfinishedChores(today()->subDay())->get()->map->id
        )
            ->update(['ended_at' => today()->subDay()]);
    }
}
