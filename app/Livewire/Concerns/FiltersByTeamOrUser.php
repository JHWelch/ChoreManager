<?php

namespace App\Livewire\Concerns;

use App\Models\Chore;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

trait FiltersByTeamOrUser
{
    public string $team_or_user = 'user';

    public function setTeamFilter(string $filter): void
    {
        $this->team_or_user = $filter;
        session(['filter_team_or_user' => $filter]);
        $this->dispatch('filterUpdated')->self();
    }

    public function bootFiltersByTeamOrUser(): void
    {
        $this->team_or_user = session('filter_team_or_user', 'user');
    }

    /**
     * Return a chore query for the current user or current Team based on filter state.
     *
     * @return Builder<Chore>|HasMany<Chore, User>|HasMany<Chore, Team>
     */
    public function choreQueryByTeamOrUser(bool $filter_by_chore_owner = true): Builder|HasMany
    {
        if ($this->team_or_user === 'team' && Auth::user()->currentTeam) {
            return Auth::user()->currentTeam->chores();
        }

        return $filter_by_chore_owner
            ? Auth::user()->chores()
            : Chore::where('chore_instances.user_id', Auth::id());
    }
}
