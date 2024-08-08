<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
trait HasUnfinishedChoreScopes
{
    /** @param Builder<TModel> $query */
    public function scopeWithUnfinishedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): void {
        $query->whereHas(
            'choreInstances',
            fn ($q) => $this->uncompletedChores($q, $on_or_before)
        );
    }

    /** @param Builder<TModel> $query */
    public function scopeWithoutUnfinishedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): void {
        $query->whereDoesntHave(
            'choreInstances',
            fn ($q) => $this->uncompletedChores($q, $on_or_before)
        );
    }

    /** @param Builder<TModel> $query */
    protected function uncompletedChores(
        Builder $query,
        ?Carbon $on_or_before = null
    ): void {
        $query
            ->where('due_date', '<=', $on_or_before ?? new Carbon)
            ->whereNull('completed_date');
    }
}
