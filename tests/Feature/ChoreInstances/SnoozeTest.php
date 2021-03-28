<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SnoozeTest extends TestCase
{
    use RefreshDatabase;

    private function arrange()
    {
        // Create chore for user. Get a carbon instance for today.
        return [
            'today' => $today = today(),
            'user'  => $user = $this->testUser()['user'],
            'chore' => Chore::factory()
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
        Livewire::test(IndexLine::class, [
            'chore' => $values['chore'],
        ])->call('snoozeUntilTomorrow');

        // Assert
        // The chore instance has moved one day.
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chore']->id,
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
        Livewire::test(IndexLine::class, [
            'chore' => $values['chore'],
        ])->call('snoozeUntilWeekend');

        // Assert
        // The chore instance is moved until the next (known) weekend
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chore']->id,
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
        Livewire::test(IndexLine::class, [
            'chore' => $values['chore'],
        ])->call('snoozeUntilWeekend');

        // Assert
        // The chore instance is moved until the next (known) weekend
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $values['chore']->id,
            'due_date' => Carbon::parse('2021-03-06'),
        ]);
    }
}
