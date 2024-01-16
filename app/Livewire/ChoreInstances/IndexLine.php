<?php

namespace App\Livewire\ChoreInstances;

use App\Livewire\Concerns\SnoozesChores;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Livewire\Component;

class IndexLine extends Component
{
    use SnoozesChores {
        snoozeUntilTomorrow as snoozeUntilTomorrowTrait;
        snoozeUntilWeekend as snoozeUntilWeekendTrait;
    }

    public Chore $chore;

    public ChoreInstance $chore_instance;

    public function mount(Chore $chore): void
    {
        $chore->load('nextChoreInstance');
        $this->chore = $chore;
        $this->chore_instance = $chore->nextChoreInstance;
    }

    public function complete(?int $for = null): void
    {
        $this->chore_instance->complete($for);
        $this->chore_instance->refresh();
        $this->dispatch('chore_instance.completed');
    }

    public function snoozeUntilTomorrow(): void
    {
        $this->snoozeUntilTomorrowTrait($this->chore_instance);
        $this->dispatch('chore_instance.updated');
    }

    public function snoozeUntilWeekend(): void
    {
        $this->snoozeUntilWeekendTrait($this->chore_instance);
        $this->dispatch('chore_instance.updated');
    }
}
