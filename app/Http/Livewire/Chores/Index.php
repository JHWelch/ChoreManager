<?php

namespace App\Http\Livewire\Chores;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public Collection $chores;
    public $sort = 'chore_instances.due_date';
    public $desc = false;

    public function mount()
    {
        $this->loadChores();
    }

    public function loadChores()
    {
        $this->chores = Auth::user()
            ->chores()
            ->withNextInstance()
            ->nullDueDatesAtEnd()
            ->orderBy($this->sort, $this->desc ? 'desc' : 'asc')
            ->get();

        ray($this->chores);
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
}
