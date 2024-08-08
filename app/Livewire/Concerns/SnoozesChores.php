<?php

namespace App\Livewire\Concerns;

trait SnoozesChores
{
    public function snoozeUntilTomorrow(mixed $chore_instances): void
    {
        $chore_instances->update(['due_date' => today()->addDay()]);
    }

    public function snoozeUntilWeekend(mixed $chore_instances): void
    {
        $today = today();

        $chore_instances->update([
            'due_date' => $today->isWeekend()
                ? $today->startOfWeek()->addDays(12)
                : $today->startOfWeek()->addDays(5),
        ]);
    }
}
