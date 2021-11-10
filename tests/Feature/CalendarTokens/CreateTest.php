<?php

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


test('can create a calendar token to display only their chores', function () {
    // Arrange
    // Create a user
    $user = $this->testUser()['user'];

    // Act
    // Navigate to calendar page, set to user calendar and add.
    Livewire::test(Index::class)
        ->set('calendar_type', 'user')
        ->call('addCalendarLink');

    // Assert
    // Token exists.
    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $user->id,
        'team_id' => null,
    ]);
});

test('can create a calendar token to display their teams chores', function () {
    // Arrange
    // Create a user with a team.
    $userAndTeam = $this->testUser();

    // Act
    // Navigate to calendar page, set team to their team, and add.
    Livewire::test(Index::class)
        ->set('calendar_type', 'team')
        ->set('calendar_token.team_id', $userAndTeam['team']->id)
        ->call('addCalendarLink');

    // Assert
    // Token has been created with the user and team.
    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $userAndTeam['user']->id,
        'team_id' => $userAndTeam['team']->id,
    ]);
});

test('when team calendar is selected user must pick team', function () {
    // Arrange
    // Create a user with a team
    $this->testUser();

    // Act
    // Navigate to calendar page, set team calendar and then add
    $component = Livewire::test(Index::class)
        ->set('calendar_type', 'team')
        ->call('addCalendarLink');

    // Assert
    // There is an error, nothing was created in database.
    $component->assertHasErrors(['calendar_token.team_id' => 'required_if']);
    $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
});

test('if user calendar selected will not have team even if specified', function () {
    // Arrange
    // Create a user with a team.
    $userAndTeam = $this->testUser();

    // Act
    // Navigate to calendar page, set team to their team, but calendar type to user.
    Livewire::test(Index::class)
        ->set('calendar_type', 'user')
        ->set('calendar_token.team_id', $userAndTeam['team']->id)
        ->call('addCalendarLink');

    // Assert
    // Token has been created with the user, but not the team
    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $userAndTeam['user']->id,
        'team_id' => null,
    ]);
});

test('calendars can be created with names', function () {
    // Arrange
    // Create user
    $user = $this->testUser()['user'];

    // Act
    // Navigate to page and create user calendar with a name
    Livewire::test(Index::class)
        ->set('calendar_type', 'user')
        ->set('calendar_token.name', 'Chore Calendar')
        ->call('addCalendarLink');

    // Assert
    // Calendar token exists with the given name
    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'name'    => 'Chore Calendar',
        'user_id' => $user->id,
        'team_id' => null,
    ]);
});
