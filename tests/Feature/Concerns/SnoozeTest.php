<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\Concerns\SnoozesChores;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SnoozeTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function arrange(int $count = 1)
    {
        // Create chore for user. Get a carbon instance for today.
        return [
            'today'  => $today = today(),
            'user'   => $user = $this->testUser()['user'],
            'chores' => Chore::factory()
                ->count($count)
                ->for($user)
                ->withFirstInstance($today)
                ->create(),
        ];
    }

    /** @test */
    public function can_snooze_a_chore_until_tomorrow()
    {
        // Arrange
        // Create user and chore
        $values = $this->arrange();

        // Act
        // Open Index Line and Snooze for one day.
        (new SnoozeClass())
            ->snoozeUntilTomorrow(
                $values['chores']->first()->nextChoreInstance
            );

        // Assert
        // The chore instance has moved one day.
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => $values['today']->addDay()->startOfDay(),
        ]);
    }

    /** @test */
    public function can_snooze_a_chore_until_the_weekend()
    {
        // Arrange
        // Set current date to a known monday, get chore,user and "today"
        $this->travelTo(Carbon::parse('2021-03-01'));
        $values = $this->arrange();

        // Act
        // Open Index line and snooze until the weekend
        (new SnoozeClass())
            ->snoozeUntilWeekend(
                $values['chores']->first()->nextChoreInstance
            );

        // Assert
        // The chore instance is moved until the next (known) weekend
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => Carbon::parse('2021-03-06'),
        ]);
    }

    /** @test */
    public function snoozing_until_weekend_on_a_weekend_pushes_to_next_weekend()
    {
        // Arrange
        // Set current date to known weekend, get chore
        $this->travelTo(Carbon::parse('2021-02-28'));
        $values = $this->arrange();

        // Act
        // Open Index line and snooze until the weekend
        (new SnoozeClass())
            ->snoozeUntilWeekend(
                $values['chores']->first()->nextChoreInstance
            );

        // Assert
        // The chore instance is moved until the next (known) weekend
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chores']->first()->id,
            'due_date' => Carbon::parse('2021-03-06'),
        ]);
    }

    /** @test */
    public function user_can_snooze_a_group_of_chores_until_tomorrow_at_the_same_time()
    {
        // Arrange
        // Create several chores due today, one that is not
        $values = $this->arrange(3);

        $later_date = $values['today']->copy()->addDays(3);

        // Act
        // Snooze all chores due today for a day
        (new SnoozeClass())->snoozeUntilTomorrow(
            ChoreInstance::where('due_date', $values['today']),
        );

        // Assert
        // Chores due today have been snoozed for a day,
        $tomorrow = $values['today']->addDay()->startOfDay();

        $values['chores']->each(function ($chore) use ($tomorrow) {
            $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => $tomorrow,
            ]);
        });
    }

    /** @test */
    public function user_can_snooze_a_group_of_chores_until_the_weekend()
    {
        // Arrange
        // Create several chores due today, one that is not
        // Set current date to a known monday
        $this->travelTo(Carbon::parse('2021-03-01'));
        $values = $this->arrange(3);

        // Act
        // Snooze all chores due today for a day
        (new SnoozeClass())->snoozeUntilWeekend(
            ChoreInstance::where('due_date', $values['today']),
        );

        // Assert
        // Chores due today have been snoozed for a day,
        $values['chores']->each(function ($chore) {
            $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => Carbon::parse('2021-03-06'),
            ]);
        });
    }
}

class SnoozeClass
{
    use SnoozesChores;
}
