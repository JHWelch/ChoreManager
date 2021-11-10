<?php

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('do not repeat chore creates no instance', function () {
    // Arrange
    // Create Chore with Daily Frequency
    $chore = Chore::factory()->create([
        'frequency_id' => Frequency::DOES_NOT_REPEAT,
    ]);

    // Act
    // Create Chore instance
    $chore->createNewInstance();

    // Assert
    // Chore instance due date is in 1 day.
    $this->assertEquals(
        null,
        $chore->nextChoreInstance
    );
});

test('chores can be completed with a frequency', function () {
    // Arrange
    // Create Chore with Daily Frequency
    $chore = Chore::factory()->create([
        'frequency_id' => Frequency::DAILY,
    ]);

    // Act
    // Create Chore instance
    $chore->createNewInstance();

    // Assert
    // Chore instance due date is in 1 day.
    $this->assertEquals(
        today()->addDay()->toDateString(),
        $chore->nextChoreInstance->due_date->toDateString(),
    );
});

test('chores can be completed with a frequency plus interval', function () {
    // Arrange
    // Create Chores with Daily Frequency every 2 and every 3 days
    $chore1 = Chore::factory()->create([
        'frequency_id'       => Frequency::DAILY,
        'frequency_interval' => 2,
    ]);
    $chore2 = Chore::factory()->create([
        'frequency_id'       => Frequency::WEEKLY,
        'frequency_interval' => 3,
    ]);

    // Act
    // Create Chore instance
    $chore1->createNewInstance();
    $chore2->createNewInstance();

    // Assert
    // Chore instance due dates are in 2 and 3 days respectively.
    $this->assertEquals(
        today()->addDays(2)->toDateString(),
        $chore1->nextChoreInstance->due_date->toDateString(),
    );
    $this->assertEquals(
        today()->addWeeks(3)->toDateString(),
        $chore2->nextChoreInstance->due_date->toDateString(),
    );
});

test('chores can be completed with day of frequency', function () {
    // Arrange
    // Create Chores with two different day of frequencies
    Carbon::setTestNow('2021-07-06');
    $date   = Carbon::parse('2021-07-06');
    $chore1 = Chore::factory()->create([
        'frequency_id'       => Frequency::WEEKLY,
        'frequency_interval' => 1,
        'frequency_day_of'   => Carbon::TUESDAY,
    ]);
    $chore2 = Chore::factory()->create([
        'frequency_id'       => Frequency::MONTHLY,
        'frequency_interval' => 1,
        'frequency_day_of'   => 17,
    ]);

    // Act
    // Create Chore instance
    $chore1->createNewInstance();
    $chore2->createNewInstance();

    // Assert
    // Chore instance due dates are in 2 and 3 days respectively.
    $this->assertEquals(
        '2021-07-13',
        $chore1->nextChoreInstance->due_date->toDateString(),
    );
    $this->assertEquals(
        '2021-08-17',
        $chore2->nextChoreInstance->due_date->toDateString(),
    );
});

test('completing a chore instance creates a new instance with same owner', function () {
    // Arrange
    // create chore with user and instance
    $user  = User::factory()->create();
    $chore = Chore::factory()->for($user)->withFirstInstance()->create();

    // Act
    // Complete chore instance
    $chore->complete();

    // Assert
    // Next chore instance has the same user
    $this->assertEquals(
        $user->id,
        $chore->nextChoreInstance->user_id,
    );
});

test('when a chore is completed the completed by id is set to the user completing it', function () {
    // Arrange
    // Create a test user acting as and a chore assigned to a different user.
    $acting_as_user = $this->testUser()['user'];
    $assigned_user  = User::factory()->create();
    $chore          = Chore::factory()->for($assigned_user)->withFirstInstance()->create();

    // Act
    // Complete Chore
    $chore->complete();

    // Assert
    // Completed chore instance is marked completed by acting_as_user
    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id'         => $assigned_user->id,
        'completed_by_id' => $acting_as_user->id,
        'chore_id'        => $chore->id,
    ]);
});

test('when a chore assigned to a team is completed the next instance is assigned to the next person alphabetically', function () {
    // Arrange
    // Create three users, a chore with a first instance assigned to the second user
    $user_and_team = $this->testUser(['name' => 'Albert Albany']);
    $user1         = $user_and_team['user'];
    $team          = $user_and_team['team'];
    $users         = User::factory()
        ->hasAttached($team)
        ->count(2)
        ->sequence(
            ['name' => 'Bobby Boston'],
            ['name' => 'Charlie Chicago'],
        )
        ->create();
    $user2 = $users->first();
    $user3 = $users->last();
    $chore = Chore::factory(['frequency_id' => Frequency::DAILY])
        ->for($team)
        ->assignedToTeam()
        ->has(ChoreInstance::factory()->for($user2))
        ->create();

    // Act
    // Complete the first instnace
    $chore->complete();

    // Assert
    // The next chore instance is assigned to the third user
    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id'        => $user3->id,
        'chore_id'       => $chore->id,
        'completed_date' => null,
    ]);
});

test('when an instance is assigned to the last person alphabetically it will wrap around', function () {
    // Arrange
    // Create three users, a chore with a first instance assigned to the third user
    $user_and_team = $this->testUser(['name' => 'Albert Albany']);
    $user1         = $user_and_team['user'];
    $team          = $user_and_team['team'];
    $users         = User::factory()
        ->hasAttached($team)
        ->count(2)
        ->sequence(
            ['name' => 'Bobby Boston'],
            ['name' => 'Charlie Chicago'],
        )
        ->create();
    $user2 = $users->first();
    $user3 = $users->last();
    $chore = Chore::factory(['frequency_id' => Frequency::DAILY])
        ->for($team)
        ->assignedToTeam()
        ->has(ChoreInstance::factory()->for($user3))
        ->create();

    // Act
    // Complete the first instnace
    $chore->complete();

    // Assert
    // The next chore instance is assigned to the first user
    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id'        => $user1->id,
        'chore_id'       => $chore->id,
        'completed_date' => null,
    ]);
});

test('when chore is completed in the past the next instance date is based on that date', function () {
    // Arrange
    // Create chore with predictable frequency
    $date  = today();
    $chore = Chore::factory()->withFirstInstance()->create([
        'frequency_id'       => Frequency::DAILY,
        'frequency_interval' => 4,
    ]);

    // Act
    // Complete chore
    $chore->complete(null, $date->subDays(3));

    // Assert
    // new instance counts from the completed dated
    $this->assertEquals(
        today()->addDay()->toDateString(),
        $chore->refresh()->nextChoreInstance->due_date->toDateString(),
    );
});
