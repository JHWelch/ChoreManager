<?php

namespace App\Http\Livewire\Concerns;

use App\Models\ChoreInstance;

trait SnoozesChores
{
    public function snoozeUntilTomorrow(mixed $chore_instances)
    {
        $chore_instances->update(['due_date' => today()->addDay()]);
    }

    public function snoozeUntilWeekend(mixed $chore_instances)
    {
        $today = today();

        $chore_instances->update([
            'due_date' => $today->isWeekend()
                ? $today->startOfWeek()->addDays(12)
                : $today->startOfWeek()->addDays(5),
        ]);
    }
}
