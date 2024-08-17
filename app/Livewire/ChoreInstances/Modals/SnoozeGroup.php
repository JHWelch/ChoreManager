<?php

namespace App\Livewire\ChoreInstances\Modals;

use App\Livewire\Concerns\FiltersByTeamOrUser;
use App\Livewire\Concerns\SnoozesChores;
use LivewireUI\Modal\ModalComponent;

class SnoozeGroup extends ModalComponent
{
    use FiltersByTeamOrUser;
    use SnoozesChores;

    public string $group;

    public string $until;

    public function snoozeGroupUntilTomorrow(): void
    {
        $this->snoozeUntilTomorrow($this->query());

        $this->dispatch('chore_instance.updated');
    }

    public function snoozeGroupUntilWeekend(): void
    {
        $this->snoozeUntilWeekend($this->query());

        $this->dispatch('chore_instance.updated');
    }

    public function snoozeGroup(): void
    {
        match ($this->until) {
            'tomorrow' => $this->snoozeGroupUntilTomorrow(),
            'the weekend' => $this->snoozeGroupUntilWeekend(),
        };

        $this->closeModal();
    }

    protected function query(): mixed
    {
        return match ($this->group) {
            'today' => $this->todayQuery(),
            'past_due' => $this->pastDueQuery(),
        };
    }

    protected function todayQuery(): mixed
    {
        return $this->baseQuery()
            ->whereDate(
                'chore_instances.due_date',
                today()
            );
    }

    protected function pastDueQuery(): mixed
    {
        return $this->baseQuery()
            ->whereDate(
                'chore_instances.due_date',
                '<',
                today()
            );
    }

    protected function baseQuery(): mixed
    {
        return $this->choreQueryByTeamOrUser(false)->withNextInstance();
    }
}
