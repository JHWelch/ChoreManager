<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Http\Livewire\Concerns\SnoozesChores;
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

    public function mount(Chore $chore)
    {
        $this->chore          = $chore;
        $this->chore_instance = $chore->nextChoreInstance;
    }

    public function complete($for = null)
    {
        $this->chore_instance->complete($for);
        $this->chore_instance->refresh();
        $this->emit('chore_instance.completed', $this->chore_instance->id);
    }

    public function snoozeUntilTomorrow()
    {
        $this->snoozeUntilTomorrowTrait($this->chore_instance);
    }

    public function snoozeUntilWeekend()
    {
        $this->snoozeUntilWeekendTrait($this->chore_instance);
    }
}
