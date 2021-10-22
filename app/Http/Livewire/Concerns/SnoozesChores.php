<?php

namespace App\Http\Livewire\Concerns;

use App\Models\ChoreInstance;

trait SnoozesChores
{
    public function snoozeUntilTomorrow(ChoreInstance $chore_instance)
    {
        $chore_instance->due_date = today()->addDay();
        $chore_instance->save();
        $this->emit('chore_instance.updated', $chore_instance->id);
    }

    public function snoozeUntilWeekend(ChoreInstance $chore_instance)
    {
        $today = today();

        if ($today->isWeekend()) {
            $chore_instance->due_date = $today->startOfWeek()->addDays(12);
        } else {
            $chore_instance->due_date = $today->startOfWeek()->addDays(5);
        }

        $chore_instance->save();
        $this->emit('chore_instance.updated', $chore_instance->id);
    }
}
