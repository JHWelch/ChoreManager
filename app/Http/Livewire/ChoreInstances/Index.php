<?php

namespace App\Http\Livewire\ChoreInstances;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $chore_instance_groups;

    public $listeners = [
        'chore_instance.completed' => 'choreInstanceCompleted',
    ];

    public function mount()
    {
        $this->updateChoreInstanceList();
    }

    public function choreInstanceCompleted()
    {
        $this->updateChoreInstanceList();
    }

    public function updateChoreInstanceList()
    {
        $this->chore_instance_groups = Auth::user()
            ->chores()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->get()
            ->mapToGroups(fn ($item) => [$item->due_date->toDateString() => $item]);
    }
}
