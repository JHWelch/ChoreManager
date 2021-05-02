<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Http\Livewire\Concerns\FiltersByTeamOrUser;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser {
        setTeamFilter as _setTeamFilter;
    }

    public $choreInstanceGroups;

    public $showFutureChores;

    public $listeners = [
        'chore_instance.completed' => 'choreInstanceUpdated',
        'chore_instance.updated'   => 'choreInstanceUpdated',
    ];

    public function mount()
    {
        $this->showFutureChores = session('show_future_chores', false);
        $this->updateChoreInstanceList();
    }

    public function choreInstanceUpdated()
    {
        $this->updateChoreInstanceList();
        $this->dispatchBrowserEvent('choreinstanceupdated');
    }

    public function updateChoreInstanceList()
    {
        $onlyWithFilter = $this->showFutureChores
            ? 'onlyWithNextInstance'
            : 'onlyWithDueNextInstance';

        $this->choreInstanceGroups = $this->choreQueryByTeamOrUser(false)
            ->{$onlyWithFilter}()
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
        session(['show_future_chores' => $this->showFutureChores]);
    }

    public function setTeamFilter($filter)
    {
        $this->_setTeamFilter($filter);
        $this->updateChoreInstanceList();
    }
}
