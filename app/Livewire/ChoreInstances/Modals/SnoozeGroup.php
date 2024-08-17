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
        switch ($this->group) {
            case 'today':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser(false)
                        ->withNextInstance()
                        ->whereDate(
                            'chore_instances.due_date',
                            today()
                        )
                );
                break;
            case 'past_due':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser(false)
                        ->withNextInstance()
                        ->whereDate(
                            'chore_instances.due_date',
                            '<',
                            today()
                        )
                );
        }

        $this->dispatch('chore_instance.updated');
    }

    public function snoozeGroupUntilWeekend(): void
    {
        switch ($this->group) {
            case 'today':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser(false)
                        ->withNextInstance()
                        ->whereDate(
                            'chore_instances.due_date',
                            today()
                        )
                );
                break;
            case 'past_due':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser(false)
                        ->withNextInstance()
                        ->whereDate(
                            'chore_instances.due_date',
                            '<',
                            today()
                        )
                );
                break;
        }

        $this->dispatch('chore_instance.updated');
    }

    public function snoozeGroup(): void
    {
        if ($this->until === 'tomorrow') {
            $this->snoozeGroupUntilTomorrow();
        } elseif ($this->until === 'the weekend') {
            $this->snoozeGroupUntilWeekend();
        }

        $this->closeModal();
    }
}
