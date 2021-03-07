<?php

namespace App\Http\Livewire\Chores;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public Collection $chores;
    public $sort = 'chore_instances.due_date';
    public $desc = false;

    public $team_or_user = 'user';

    public function mount()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $user = Auth::user();
        if ($this->team_or_user === 'team') {
            $chore_query = $user->currentTeam->chores();
        } else {
            $chore_query = $user->chores();
        }

        $this->chores = $chore_query
            ->withNextInstance()
            ->nullDueDatesAtEnd()
            ->orderBy($this->sort, $this->desc ? 'desc' : 'asc')
            ->get();
    }

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->desc = ! $this->desc;
        } else {
            $this->sort = $column;
            $this->desc = false;
        }

        $this->loadChores();
    }

    public function setTeamFilter($filter)
    {
        $this->team_or_user = $filter;
        $this->loadChores();
    }
}
