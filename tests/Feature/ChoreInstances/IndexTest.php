<?php

use App\Http\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('chore instance index page can be reached', function () {
    // Arrange
    // Create a test user
    $this->testUser()['user'];

    // Act
    // Navigate to Chore instance Index page
    $response = $this->get(route('chore_instances.index'));

    // Assert
    // A page is successfully returned
    $response->assertStatus(200);
});

test('chores with chore instances show on index', function () {
    // Arrange
    // Create a chore with a chore instance
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

    // Act
    // Open chore instance index
    $component = Livewire::test(ChoreInstancesIndex::class);

    // Assert
    // Chore and instance date is show on page
    $component->assertSee($chore->title);
});

test('chores without chore instances do not show on index', function () {
    // Arrange
    // Createa a chore without chore instance
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->create();

    // Act
    // Open chore instance index
    $component = Livewire::test(ChoreInstancesIndex::class);

    // Assert
    // I do not see that chore's title
    $component->assertDontSee($chore->title);
});

test('when there are no chore instances see empty state', function () {
    // Arrange
    // create user
    $this->testUser();

    // Act
    // Go to Index page
    $component = Livewire::test(ChoreInstancesIndex::class);

    // Assert
    // See empty state
    $component->assertSee('All done for today');
});

test('future chores do not show by default', function () {
    // Arrange
    // Create two chores, one due today, one in future
    $user   = $this->testUser()['user'];
    $chore1 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today())
        ->create();
    $chore2 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
        ->addDays(4))->create();

    // Act
    // View Index page
    $component = Livewire::test(ChoreInstancesIndex::class);

    // Assert
    // Can see the chore due today, but not the one in the future.
    $component->assertSee($chore1->title);
    $component->assertDontSee($chore2->title);
});

test('user can show future chores', function () {
    // Arrange
    // Create chore in the future
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
        ->addDays(4))->create();

    // Act
    // View index page and toggle showing future chores
    $component = Livewire::test(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');

    // Assert
    // User can see future chore
    $component->assertSee($chore->title);
});

test('show future chores is remembered when revisiting page', function () {
    // Arrange
    // Create chore in the future
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
        ->addDays(4))->create();

    // Act
    // Open component and toggle Show, load another component
    Livewire::test(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');
    $component = Livewire::test(ChoreInstancesIndex::class);

    // Assert
    // User can see future chore
    $component->assertSee($chore->title);
});

it('can snooze chores due today for a user until tomorrow', function () {
    // Arrange
    // Create chores due today, and one other chore
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

    // Act
    // Snooze Chores due today until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'today');

    // Assert
    // Chores due today are snoozed, the other is not
    foreach ($chores as $chore) {
        $this->assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    $this->assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $tomorrow,
    ]);
});

it('can snooze chores due today for a user unti l the weekend', function () {
    // Arrange
    // Create chores due today, and one other chore
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

    // Act
    // Snooze Chores due today until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
           ->call('snoozeGroupUntilTomorrow', 'today');

    // Assert
    // Chores due today are snoozed, the other is not
    foreach ($chores as $chore) {
        $this->assertDatabaseHas(ChoreInstance::class, [
               'chore_id' => $chore->id,
               'due_date' => $tomorrow,
           ]);
    }

    $this->assertDatabaseMissing(ChoreInstance::class, [
           'chore_id' => $other_chore->id,
           'due_date' => $tomorrow,
       ]);
});

it('can snooze chores due in the past for a user until tomorrow', function () {
    // Arrange
    // Create chores due in the past, and one other chore
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

    // Act
    // Snooze Chores due in the past until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'past_due');

    // Assert
    // Chores due today are snoozed, the other is not
    foreach ($chores as $chore) {
        $this->assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    $this->assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $tomorrow,
    ]);
});

it('can snooze chores due in the past for a user unti l the weekend', function () {
    // Arrange
    // Create chores due in the past, and one other chore
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

    // Act
    // Snooze Chores due in the past until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilWeekend', 'past_due');

    // Assert
    // Chores due today are snoozed, the other is not
    foreach ($chores as $chore) {
        $this->assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    $this->assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $other_chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('wont snooze chores due today for a team unti l the weekend if filter is user', function () {
    // Arrange
    // Create chores due in the past, and one other chore
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
         ->withFirstInstance(today())
         ->for(User::factory()->hasAttached($this->team))
         ->create();

    // Act
    // Snooze Chores due in the past until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
         ->call('snoozeGroupUntilWeekend', 'today');

    // Assert
    // Chores due today are snoozed, the other is not
    $this->assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

it('wont snooze chores due in the past for a team unti l the weekend if filter is user', function () {
    // Arrange
    // Create chores due in the past, and one other chore
    $this->travelToKnownMonday();
    $this->testUser();

    $chore = Chore::factory()
         ->withFirstInstance(today()->subDay())
         ->for(User::factory()->hasAttached($this->team))
         ->create();

    // Act
    // Snooze Chores due in the past until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
         ->call('snoozeGroupUntilWeekend', 'past_due');

    // Assert
    // Chores due today are snoozed, the other is not
    $this->assertDatabaseMissing(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $this->knownSaturday(),
    ]);
});

test('snoozes chores owned by team but assigned to user', function () {
    // Arrange
    // Create chores due in the past, and one other chore
    $this->testUser();
    $chore = Chore::factory()
        ->withFirstInstance(today()->subDays(2), $this->user->id)
        ->for($this->team)
        ->create();
    $tomorrow = today()->addDay();

    // Act
    // Snooze Chores due in the past until tomorrow
    Livewire::test(ChoreInstancesIndex::class)
        ->call('snoozeGroupUntilTomorrow', 'past_due');

    // Assert
    // Chores due today are snoozed, the other is not
    $this->assertDatabaseHas(ChoreInstance::class, [
        'chore_id' => $chore->id,
        'due_date' => $tomorrow,
    ]);
});
