<?php

namespace App\Http\Livewire\Chores;

use App\Http\Livewire\Concerns\FiltersByTeamOrUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser {
        setTeamFilter as _setTeamFilter;
    }

    public Collection $chores;
    public $sort = 'chore_instances.due_date';
    public $desc = false;

    public function mount()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $query = $this->choreQueryByTeamOrUser()->withNextInstance();
        $query = $this->sort === 'chore_instances.due_date' && ! $this->desc
            ? $query->nullDueDatesAtEnd()
            : $query->orderBy($this->sort, $this->desc ? 'desc' : 'asc');

        $this->chores = $query->get();
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
        $this->_setTeamFilter($filter);
        $this->loadChores();
    }
}
