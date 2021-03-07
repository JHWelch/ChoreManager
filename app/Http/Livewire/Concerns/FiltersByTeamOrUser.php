<?php

namespace App\Http\Livewire\Concerns;

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
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships::hasMany
     */
    public function choreQueryByTeamOrUser()
    {
        if ($this->team_or_user === 'team') {
            return Auth::user()->currentTeam->chores();
        } else {
            return Auth::user()->chores();
        }
    }
}
