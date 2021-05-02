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

    public $showFutureChores = false;

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
        $only_with_filter = $this->showFutureChores
            ? 'onlyWithNextInstance'
            : 'onlyWithDueNextInstance';

        $this->chore_instance_groups = $this->choreQueryByTeamOrUser(false)
            ->{$only_with_filter}()
            ->orderBy('chore_instances.due_date')
            ->get()
            ->mapToGroups(function ($chore_instance) {
                $due_date = $chore_instance->due_date->startOfDay();

                if ($due_date < today()) {
                    return ['past_due' => $chore_instance];
                } elseif ($due_date == today()) {
                    return ['today' => $chore_instance];
                }

                return ['future' => $chore_instance];
            })
            ->map(function ($date_group) {
                return $date_group->mapToGroups(fn ($chore) => [$chore->due_date->diffDaysForHumans() => $chore]);
            });
    }

    public function toggleShowFutureChores()
    {
        $this->showFutureChores = ! $this->showFutureChores;
        $this->updateChoreInstanceList();
    }

    public function setTeamFilter($filter)
    {
        $this->_setTeamFilter($filter);
        $this->updateChoreInstanceList();
    }
}
