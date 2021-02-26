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
            ->select('chores.*', 'chore_instances.due_date')
            ->leftJoin('chore_instances', function ($join) {
                $join->on('chores.id', '=', 'chore_instances.chore_id')
                    ->where('chore_instances.completed_date', null);
            })
            ->orderBy(DB::raw('ISNULL(chore_instances.due_date), chore_instances.due_date'), 'ASC')
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
