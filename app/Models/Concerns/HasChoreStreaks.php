<?php

namespace App\Models\Concerns;

use App\Models\StreakCount;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasChoreStreaks
{
    /** @return HasOne<StreakCount, $this> */
    public function currentStreak(): HasOne
    {
        return $this->hasOne(StreakCount::class)->whereNull('ended_at');
    }
}
