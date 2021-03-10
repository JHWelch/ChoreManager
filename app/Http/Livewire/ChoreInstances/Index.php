<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Http\Livewire\Concerns\FiltersByTeamOrUser;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser {
        setTeamFilter as _setTeamFilter;
    }

    public $chore_instance_groups;

    public $listeners = [
        'chore_instance.completed' => 'choreInstanceUpdated',
        'chore_instance.updated'   => 'choreInstanceUpdated',
    ];

    public function mount()
    {
        $this->updateChoreInstanceList();
    }

    public function choreInstanceUpdated()
    {
        $this->updateChoreInstanceList();
        $this->dispatchBrowserEvent('choreinstanceupdated');
    }

    public function updateChoreInstanceList()
    {
        $this->chore_instance_groups = $this->choreQueryByTeamOrUser()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->get()
            ->mapToGroups(fn ($item) => [$item->due_date => $item]);
    }

    public function setTeamFilter($filter)
    {
        $this->_setTeamFilter($filter);
        $this->updateChoreInstanceList();
    }
}
