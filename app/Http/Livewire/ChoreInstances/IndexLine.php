<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Livewire\Component;

class IndexLine extends Component
{
    public Chore $chore;
    public ChoreInstance $chore_instance;

    public function mount(Chore $chore)
    {
        $this->chore          = $chore;
        $this->chore_instance = $chore->nextChoreInstance;
    }

    public function complete()
    {
        $this->chore_instance->complete();
        $this->chore_instance->refresh();
        $this->emit('chore_instance.completed', $this->chore_instance->id);
    }

    public function snoozeUntilTomorrow()
    {
        $this->chore_instance->due_date = today()->addDay();
        $this->chore_instance->save();
    }

    public function snoozeUntilWeekend()
    {
        $today = today();

        if ($today->isWeekend()) {
            $this->chore_instance->due_date = $today->startOfWeek()->addDays(12);
        } else {
            $this->chore_instance->due_date = $today->startOfWeek()->addDays(5);
        }

        $this->chore_instance->save();
    }
}
