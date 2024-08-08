<?php

namespace Tests\Feature\Concerns;

use App\Livewire\Concerns\SnoozesChores;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;
use Tests\TestCase;

class SnoozeTest extends TestCase
{
    private function arrange(int $count = 1)
    {
        // Create chore for user. Get a carbon instance for today.
        return [
            'today' => $today = today(),
            'user' => $user = $this->testUser()['user'],
            'chores' => Chore::factory()
                ->count($count)
                ->for($user)
                ->withFirstInstance($today)
                ->create(),
        ];
    }

    /** @test */
    public function can_snooze_a_chore_until_tomorrow(): void
    {
        $values = $this->arrange();

        (new SnoozeClass())
            ->snoozeUntilTomorrow(
                $values['chores']->first()->nextChoreInstance
            );

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => $values['today']->addDay()->startOfDay(),
        ]);
    }

    /** @test */
    public function can_snooze_a_chore_until_the_weekend(): void
    {
        $this->travelToKnownMonday();
        $values = $this->arrange();

        (new SnoozeClass())
            ->snoozeUntilWeekend(
                $values['chores']->first()->nextChoreInstance
            );

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function snoozing_until_weekend_on_a_weekend_pushes_to_next_weekend(): void
    {
        $this->travelTo(Carbon::parse('2021-02-28'));
        $values = $this->arrange();

        (new SnoozeClass())
            ->snoozeUntilWeekend(
                $values['chores']->first()->nextChoreInstance
            );

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => Carbon::parse('2021-03-06'),
        ]);
    }

    /** @test */
    public function user_can_snooze_a_group_of_chores_until_tomorrow_at_the_same_time(): void
    {
        $values = $this->arrange(3);

        $later_date = $values['today']->copy()->addDays(3);

        (new SnoozeClass())->snoozeUntilTomorrow(
            ChoreInstance::where('due_date', $values['today']),
        );

        $tomorrow = $values['today']->addDay()->startOfDay();

        $values['chores']->each(function ($chore) use ($tomorrow) {
            $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => $tomorrow,
            ]);
        });
    }

    /** @test */
    public function user_can_snooze_a_group_of_chores_until_the_weekend(): void
    {
        // Set current date to a known monday
        $this->travelToKnownMonday();
        $values = $this->arrange(3);

        (new SnoozeClass())->snoozeUntilWeekend(
            ChoreInstance::where('due_date', $values['today']),
        );

        $values['chores']->each(function ($chore) {
            $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => $this->knownSaturday(),
            ]);
        });
    }
}

class SnoozeClass
{
    use SnoozesChores;
}
