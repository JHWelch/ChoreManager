<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Chore;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FiltersByTeamOrUser
{
    public string $team_or_user = 'user';

    public function setTeamFilter($filter) : void
    {
        $this->team_or_user = $filter;
        session(['filter_team_or_user' => $filter]);
        $this->emitSelf('filterUpdated');
    }

    public function setupFiltersByTeamOrUser() : void
    {
        $this->team_or_user = session('filter_team_or_user', 'user');
    }

    /**
     * Return a chore query for the current user or current Team based on filter state.
     *
     * @param bool $filter_by_chore_owner If false filter by chore instance owner
     * @return \Illuminate\Database\Eloquent\Builder|Chore
     */
    public function choreQueryByTeamOrUser(bool $filter_by_chore_owner = true) : Builder|Chore
    {
        if ($this->team_or_user === 'team') {
            return Auth::user()->currentTeam->chores();
        } else {
            return $filter_by_chore_owner
                ? Auth::user()->chores()
                : Chore::where('chore_instances.user_id', Auth::id());
        }
    }
}
