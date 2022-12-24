<?php

namespace App\Http\Livewire\ChoreInstances\Concerns;

use App\Http\Livewire\Concerns\SnoozesChores;

trait SnoozesGroups
{
    use SnoozesChores;

    public bool $showSnoozeConfirmation = false;
    public string $snoozeGroup;
    public $snoozeUntil;

    public function snoozeGroupUntilTomorrow(string $group) : void
    {
        switch ($group) {
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

        $this->emit('chore_instance.updated');
    }

    public function snoozeGroupUntilWeekend(string $group) : void
    {
        switch ($group) {
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

        $this->emit('chore_instance.updated');
    }

    public function showSnoozeConfirmation(string $group, string $until) : void
    {
        $this->snoozeGroup            = $group;
        $this->snoozeUntil            = $until;
        $this->showSnoozeConfirmation = true;
    }

    public function snoozeGroup() : void
    {
        if ($this->snoozeUntil === 'tomorrow') {
            $this->snoozeGroupUntilTomorrow($this->snoozeGroup);
        } elseif ($this->snoozeUntil === 'the weekend') {
            $this->snoozeGroupUntilWeekend($this->snoozeGroup);
        }

        $this->showSnoozeConfirmation = false;
    }
}
