<?php

use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->user = $this->user()['user'];
    $this->snoozeClass = new class
    {
        use \App\Livewire\Concerns\SnoozesChores;
    };
});

describe('one chore', function () {
    beforeEach(function () {
        $this->chore = Chore::factory()
            ->for($this->user)
            ->withFirstInstance()
            ->create();
    });

    it('can snooze a chore until tomorrow', function () {
        $this->snoozeClass
            ->snoozeUntilTomorrow(
                $this->chore->nextChoreInstance
            );

        assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $this->chore->id,
            'due_date' => today()->addDay()->startOfDay(),
        ]);
    });

    it('can snooze a chore until the weekend', function () {
        $this->travelToKnownMonday();

        $this->snoozeClass
            ->snoozeUntilWeekend(
                $this->chore->nextChoreInstance
            );

        assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $this->chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    });

    test('snoozing until weekend on a weekend pushes to next weekend', function () {
        $this->travelTo(Carbon::parse('2021-02-28'));

        $this->snoozeClass
            ->snoozeUntilWeekend(
                $this->chore->nextChoreInstance
            );

        assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $this->chore->id,
            'due_date' => Carbon::parse('2021-03-06'),
        ]);
    });
});

describe('several chores', function () {
    beforeEach(function () {
        // Set current date to a known monday
        $this->travelToKnownMonday();

        $this->chores = Chore::factory()
            ->for($this->user)
            ->count(3)
            ->withFirstInstance(today())
            ->create();
    });

    test('user can snooze a group of chores until tomorrow at the same time', function () {
        $this->snoozeClass->snoozeUntilTomorrow(
            ChoreInstance::where('due_date', today()),
        );

        $tomorrow = today()->addDay()->startOfDay();

        $this->chores->each(function ($chore) use ($tomorrow) {
            assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => $tomorrow,
            ]);
        });
    });

    test('user can snooze a group of chores until the weekend', function () {
        $this->snoozeClass->snoozeUntilWeekend(
            ChoreInstance::where('due_date', today()),
        );

        $this->chores->each(function ($chore) {
            assertDatabaseHas((new ChoreInstance)->getTable(), [
                'chore_id' => $chore->id,
                'due_date' => $this->knownSaturday(),
            ]);
        });
    });
});
