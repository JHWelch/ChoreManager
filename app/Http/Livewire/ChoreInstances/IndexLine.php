<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Livewire\Component;

class IndexLine extends Component
{
    public Chore $chore;
    public ChoreInstance $chore_instance;

    public $user_id;

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

    public function completeForUser()
    {
        $this->complete($this->user_id);
    }

    public function snoozeUntilTomorrow()
    {
        $this->chore_instance->due_date = today()->addDay();
        $this->chore_instance->save();
        $this->emit('chore_instance.updated', $this->chore_instance->id);
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
        $this->emit('chore_instance.updated', $this->chore_instance->id);
    }
}
