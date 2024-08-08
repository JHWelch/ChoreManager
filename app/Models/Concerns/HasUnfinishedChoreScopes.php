<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasUnfinishedChoreScopes
{
    public function scopeWithUnfinishedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): Builder {
        return $query->whereHas(
            'choreInstances',
            fn ($q) => $this->uncompletedChores($q, $on_or_before)
        );
    }

    public function scopeWithoutUnfinishedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): Builder {
        return $query->whereDoesntHave(
            'choreInstances',
            fn ($q) => $this->uncompletedChores($q, $on_or_before)
        );
    }

    protected function uncompletedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): void {
        $query
            ->where('due_date', '<=', $on_or_before ?? new Carbon)
            ->whereNull('completed_date');
    }
}
