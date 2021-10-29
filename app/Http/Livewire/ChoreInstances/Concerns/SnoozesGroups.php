<?php

namespace App\Http\Livewire\ChoreInstances\Concerns;

use App\Http\Livewire\Concerns\SnoozesChores;

trait SnoozesGroups
{
    use SnoozesChores;

    public $showSnoozeConfirmation = false;
    public $snoozeGroup;
    public $snoozeUntil;

    public function snoozeGroupUntilTomorrow($group)
    {
        switch ($group) {
            case 'today':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', today()
                    )
                );
                break;
            case 'past_due':
                $this->snoozeUntilTomorrow(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', '<', today()
                    )
                );
        }

        $this->emit('chore_instance.updated');
    }

    public function snoozeGroupUntilWeekend($group)
    {
        switch ($group) {
            case 'today':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', today()
                    )
                );
                break;
            case 'past_due':
                $this->snoozeUntilWeekend(
                    $this->choreQueryByTeamOrUser()
                        ->withNextInstance()
                        ->whereDate('chore_instances.due_date', '<', today()
                    )
                );
                break;
        }

        $this->emit('chore_instance.updated');
    }

    public function showSnoozeConfirmation($group, $until)
    {
        $this->snoozeGroup            = $group;
        $this->snoozeUntil            = $until;
        $this->showSnoozeConfirmation = true;
    }

    public function snoozeGroup()
    {
        if ($this->snoozeUntil === 'tomorrow') {
            $this->snoozeGroupUntilTomorrow($this->snoozeGroup);
        } elseif ($this->snoozeUntil === 'the weekend') {
            $this->snoozeGroupUntilWeekend($this->snoozeGroup);
        }

        $this->showSnoozeConfirmation = false;
    }
}
