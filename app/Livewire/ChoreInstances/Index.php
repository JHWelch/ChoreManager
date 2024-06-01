<?php

namespace App\Livewire\ChoreInstances;

use App\Livewire\ChoreInstances\Concerns\SnoozesGroups;
use App\Livewire\Concerns\FiltersByTeamOrUser;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser;
    use SnoozesGroups;

    /** @var Collection<string, array<string, mixed>> */
    public Collection $choreInstanceGroups;

    public bool $showFutureChores = false;

    /** @var array<string, string> */
    public $listeners = [
        'chore_instance.completed' => 'choreInstanceUpdated',
        'chore_instance.updated' => 'choreInstanceUpdated',
        'filterUpdated' => 'updateChoreInstanceList',
    ];

    public function mount(): void
    {
        $this->showFutureChores = session('show_future_chores', false);
        $this->setupFiltersByTeamOrUser();
        $this->updateChoreInstanceList();
    }

    public function choreInstanceUpdated(): void
    {
        $this->updateChoreInstanceList();
        $this->dispatch('choreinstanceupdated');
    }

    public function updateChoreInstanceList(): void
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

                return match (true) {
                    $due_date < today() => ['past_due' => $chore_instance],
                    $due_date == today() => ['today' => $chore_instance],
                    default => ['future' => $chore_instance],
                };
            })
            ->map(function ($date_group) {
                return $date_group->mapToGroups(fn ($chore) => [$chore->due_date->diffDaysForHumans() => $chore]);
            });
    }

    public function toggleShowFutureChores(): void
    {
        $this->showFutureChores = ! $this->showFutureChores;
        $this->updateChoreInstanceList();
        session(['show_future_chores' => $this->showFutureChores]);
    }

    public function render(): View
    {
        return view('livewire.chore-instances');
    }
}
