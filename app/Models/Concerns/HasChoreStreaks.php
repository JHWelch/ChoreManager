<?php

namespace App\Models\Concerns;

use App\Models\StreakCount;

trait HasChoreStreaks
{
    public function currentStreak()
    {
        return $this->hasOne(StreakCount::class)->whereNull('ended_at');
    }
}
