<?php

use App\Livewire\ChoreInstances\Modals\SnoozeGroup;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

it('displays copy correctly', function () {
    livewire(SnoozeGroup::class, [
        'group' => 'today',
        'until' => 'tomorrow',
    ])
        ->assertSee('Snooze all chores due Today')
        ->assertSee('Are you sure you want to snooze all chores due Today until tomorrow?');
});

it('can snooze chores due today for a user until tomorrow', function () {
    $this->testUser();
    $chores = Chore::factory()
        ->count(3)
        ->withFirstInstance(today())
        ->for($this->user)
        ->create();
    $other_chore = Chore::factory()
        ->withFirstInstance(today()->subDays(3))
        ->for($this->user)
        ->create();
    $tomorrow = today()->addDay();

    livewire(SnoozeGroup::class, ['group' => 'today'])
        ->call('snoozeGroupUntilTomorrow');

    foreach ($chores as $chore) {
        assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $tomorrow,
    ]);
});

it('can snooze chores due today for a user until the weekend', function () {
    $this->testUser();
    $chores = Chore::factory()
        ->count(3)
        ->withFirstInstance(today())
        ->for($this->user)
        ->create();
    $other_chore = Chore::factory()
        ->withFirstInstance(today()->subDays(3))
        ->for($this->user)
        ->create();
    $tomorrow = today()->addDay();

    livewire(SnoozeGroup::class, ['group' => 'today'])
        ->call('snoozeGroupUntilTomorrow');

    foreach ($chores as $chore) {
        assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $tomorrow,
    ]);
});

it('can snooze chores due in the past for a user until tomorrow', function () {
    $this->testUser();
    $chores = Chore::factory()
        ->count(3)
        ->withFirstInstance(today()->subDays(2))
        ->for($this->user)
        ->create();
    $other_chore = Chore::factory()
        ->withFirstInstance(today())
        ->for($this->user)
        ->create();
    $tomorrow = today()->addDay();

    livewire(SnoozeGroup::class, ['group' => 'past_due'])
        ->call('snoozeGroupUntilTomorrow');

    foreach ($chores as $chore) {
        assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $tomorrow,
    ]);
});

it('can snooze chores due in the past for a user until the weekend', function () {
    $this->travelToKnownMonday();
    $this->testUser();
    $chores = Chore::factory()
        ->count(3)
        ->withFirstInstance(today()->subDay())
        ->for($this->user)
        ->create();
    $other_chore = Chore::factory()
        ->withFirstInstance(today())
        ->for($this->user)
        ->create();

    livewire(SnoozeGroup::class, ['group' => 'past_due'])
        ->call('snoozeGroupUntilWeekend');

    foreach ($chores as $chore) {
        assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('wont snooze chores due today for a team until the weekend if filter is user', function () {
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
        ->withFirstInstance(today())
        ->for(User::factory()->hasAttached($this->team))
        ->create();

    livewire(SnoozeGroup::class, ['group' => 'today'])
        ->call('snoozeGroupUntilWeekend');

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('wont snooze chores due in the past for a team until the weekend if filter is user', function () {
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
        ->withFirstInstance(today()->subDay())
        ->for(User::factory()->hasAttached($this->team))
        ->create();

    livewire(SnoozeGroup::class, ['group' => 'past_due'])
        ->call('snoozeGroupUntilWeekend');

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('snoozes chores owned by team but assigned to user', function () {
    $this->testUser();
    $chore = Chore::factory()
        ->withFirstInstance(today()->subDays(2), $this->user->id)
        ->for($this->team)
        ->create();
    $tomorrow = today()->addDay();

    livewire(SnoozeGroup::class, ['group' => 'past_due'])
        ->call('snoozeGroupUntilTomorrow');

    assertDatabaseHas(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $tomorrow,
    ]);
});
