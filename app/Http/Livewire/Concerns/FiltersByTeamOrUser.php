<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Chore;
use Auth;

trait FiltersByTeamOrUser
{
    public $team_or_user = 'user';

    public function setTeamFilter($filter)
    {
        $this->team_or_user = $filter;
    }

    /**
     * Return a chore query for the current user or current Team based on filter state.
     *
     * @param bool $filter_by_chore_owner If false filter by chore instance owner
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function choreQueryByTeamOrUser($filter_by_chore_owner = true)
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
