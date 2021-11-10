<?php

use App\Models\CalendarToken;
use App\Models\Chore;
use App\Models\User;

test('user calendar tokens return users next chore instances', function () {
    // Arrange
    // Create user with chores and calendar token
    $this->testUser();
    CalendarToken::create([
        'user_id' => $this->user->id,
        'token'   => 'fake_uuid',
    ]);
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Walk the dog'],
            ['title' => 'Clean the dishes'],
            ['title' => 'Do the laundry'],
        )
        ->for($this->user)
        ->withFirstInstance()
        ->create();

    // Act
    // Call ICalendar route with token key
    $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

    // Assert
    // Calendar output has names
    // NOTE: Not testing the ICalendar library, so we are not overyly worried about structure.
    $response->assertStatus(200);
    $response->assertSee('BEGIN:VCALENDAR');
    $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
});

test('team calendar tokens return team next chore instances', function () {
    // Arrange
    // Create user with chores and calendar token
    $this->testUser();
    CalendarToken::create([
        'user_id' => $this->user->id,
        'team_id' => $this->team->id,
        'token'   => 'fake_uuid',
    ]);

    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Clean the dishes'],
            ['title' => 'Do the laundry'],
        )
        ->for($this->user)
        ->for($this->team)
        ->withFirstInstance()
        ->create();

    Chore::factory([
        'title' => 'Walk the dog.',
    ])
        ->for(User::factory()->hasAttached($this->team))
        ->for($this->team)
        ->withFirstInstance()
        ->create();

    // Act
    // Call ICalendar route with token key
    $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

    // Assert
    // Calendar output has names
    // NOTE: Not testing the ICalendar library, so we are not worried about structure.
    $response->assertStatus(200);
    $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
});

test('chore calendar shows chores assigned to team but instance assigned to user', function () {
    // Arrange
    // Create chore with instance assigned to current user and personal calendar token
    $this->testUser();
    $cal = CalendarToken::create([
        'user_id' => $this->user->id,
        'token'   => 'fake_uuid',
    ]);

    Chore::factory(
        ['title' => 'Clean the dishes']
    )
        ->assignedToTeam()
        ->for($this->team)
        ->withFirstInstance(null, $this->user->id)
        ->create();

    // Act
    // Call ICalendar Route with Token Key
    $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

    // Assert
    // Calendar output has name
    $response->assertStatus(200);
    $response->assertSee('Clean the dishes');
});
