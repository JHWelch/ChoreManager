<?php

use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;

test('can snooze a chore until tomorrow', function () {
    // Arrange
    // Create user and chore
    $values = arrange();

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
});

test('can snooze a chore until the weekend', function () {
    // Arrange
    // Set current date to a known monday, get chore,user and "today"
    $this->travelToKnownMonday();
    $values = arrange();

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
        'due_date' => $this->knownSaturday(),
    ]);
});

test('snoozing until weekend on a weekend pushes to next weekend', function () {
    // Arrange
    // Set current date to known weekend, get chore
    $this->travelTo(Carbon::parse('2021-02-28'));
    $values = arrange();

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
});

test('user can snooze a group of chores until tomorrow at the same time', function () {
    // Arrange
    // Create several chores due today, one that is not
    $values = arrange(3);

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
});

test('user can snooze a group of chores until the weekend', function () {
    // Arrange
    // Create several chores due today, one that is not
    // Set current date to a known monday
    $this->travelToKnownMonday();
    $values = arrange(3);

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
            'due_date' => $this->knownSaturday(),
        ]);
    });
});

// Helpers
function arrange(int $count = 1)
{
    // Create chore for user. Get a carbon instance for today.
    return [
        'today'  => $today = today(),
        'user'   => $user = test()->testUser()['user'],
        'chores' => Chore::factory()
            ->count($count)
            ->for($user)
            ->withFirstInstance($today)
            ->create(),
    ];
}
