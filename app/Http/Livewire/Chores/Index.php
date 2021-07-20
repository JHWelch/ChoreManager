<?php

namespace App\Http\Livewire\Chores;

use App\Http\Livewire\Concerns\FiltersByTeamOrUser;
use App\Models\Chore;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser;

    public Collection $chores;
    public $sort = 'chore_instances.due_date';
    public $desc = false;

    public $showDescriptionModal = false;
    public $showDescriptionModalChore;

    protected $rules = [
        'chores.*.due_date' => 'nullable',
    ];

    protected $listeners = [
        'filterUpdated' => 'loadChores',
    ];

    public function mount()
    {
        $this->setupFiltersByTeamOrUser();
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

    public function setShowDescriptionModal($chore)
    {
        $this->showDescriptionModal      = true;
        $this->showDescriptionModalChore = Chore::find($chore);
    }
}
