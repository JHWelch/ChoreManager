<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Http\Livewire\Concerns\FiltersByTeamOrUser;
use App\Http\Livewire\Concerns\SnoozesChores;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser;
    use SnoozesChores;

    public $choreInstanceGroups;

    public $showFutureChores;

    public $listeners = [
        'chore_instance.completed' => 'choreInstanceUpdated',
        'chore_instance.updated'   => 'choreInstanceUpdated',
        'filterUpdated'            => 'updateChoreInstanceList',
    ];

    public function mount()
    {
        $this->showFutureChores = session('show_future_chores', false);
        $this->setupFiltersByTeamOrUser();
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

    public function snoozeGroupUntilTomorrow($group)
    {
        switch ($group) {
            case 'today':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', today()
                    )
                );
                break;
            case 'past_due':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', '<', today()
                    )
                );
        }

        $this->emit('chore_instance.updated');
    }

    public function snoozeGroupUntilWeekend($group)
    {
        switch ($group) {
            case 'today':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', today()
                    )
                );
                break;
            case 'past_due':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', '<', today()
                    )
                );
                break;
        }

        $this->emit('chore_instance.updated');
    }
}
