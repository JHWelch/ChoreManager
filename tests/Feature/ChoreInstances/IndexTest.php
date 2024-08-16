<?php

use App\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

test('chore instance index page can be reached', function () {
    $this->testUser()['user'];

    $response = $this->get(route('chore_instances.index'));

    $response->assertOk();
});

test('chores with chore instances show on index', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore->title);
});

test('chores without chore instances do not show on index', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertDontSee($chore->title);
});

test('when there are no chore instances see empty state', function () {
    $this->testUser();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee('All done for today');
});

test('future chores do not show by default', function () {
    $user = $this->testUser()['user'];
    $chore1 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today())
        ->create();
    $chore2 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()->addDays(4))
        ->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore1->title);
    $component->assertDontSee($chore2->title);
});

test('user can show future chores', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
            ->addDays(4))->create();

    $component = livewire(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');

    $component->assertSeeInOrder(['Future', $chore->title]);
});

test('show future chores is remembered when revisiting page', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
            ->addDays(4))->create();

    livewire(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');
    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore->title);
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

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'today');

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

it('can snooze chores due today for a user unti l the weekend', function () {
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

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'today');

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

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'past_due');

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

it('can snooze chores due in the past for a user unti l the weekend', function () {
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

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilWeekend', 'past_due');

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

it('wont snooze chores due today for a team unti l the weekend if filter is user', function () {
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
        ->withFirstInstance(today())
        ->for(User::factory()->hasAttached($this->team))
        ->create();

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilWeekend', 'today');

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('wont snooze chores due in the past for a team unti l the weekend if filter is user', function () {
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
        ->withFirstInstance(today()->subDay())
        ->for(User::factory()->hasAttached($this->team))
        ->create();

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilWeekend', 'past_due');

    assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

test('snoozes chores owned by team but assigned to user', function () {
    $this->testUser();
    $chore = Chore::factory()
        ->withFirstInstance(today()->subDays(2), $this->user->id)
        ->for($this->team)
        ->create();
    $tomorrow = today()->addDay();

    livewire(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'past_due');

    assertDatabaseHas(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $tomorrow,
    ]);
});

test('chore instances are split into groups based on date', function () {
    $this->testUser();
    Chore::factory(['title' => 'walk dog'])
        ->for($this->user)
        ->withFirstInstance(today()->addDay(), $this->user)
        ->create();
    Chore::factory(['title' => 'do laundry'])
        ->for($this->user)
        ->withFirstInstance(today()->subDay(), $this->user)
        ->create();
    Chore::factory(['title' => 'clean dishes'])
        ->for($this->user)
        ->withFirstInstance(today(), $this->user)
        ->create();

    livewire(ChoreInstancesIndex::class)
        ->assertSeeInOrder([
            'Past due',
            'do laundry',
            'Today',
            'clean dishes',
        ]);
});
