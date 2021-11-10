<?php

use App\Models\CalendarToken;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('calendar token is team calendar', function () {
    // Arrange
    // Make team calendar token.
    $calendar_token = CalendarToken::make([
        'user_id' => 1,
        'team_id' => 1,
    ]);

    // Assert
    // is team token
    $this->assertEquals(true, $calendar_token->is_team_calendar);
    $this->assertEquals(false, $calendar_token->is_user_calendar);
});

test('is team calendar attribute false', function () {
    // Arrange
    // Make team calendar token.
    $calendar_token = CalendarToken::make([
        'user_id' => 1,
        'team_id' => null,
    ]);

    // Assert
    // is team token
    $this->assertEquals(false, $calendar_token->is_team_calendar);
    $this->assertEquals(true, $calendar_token->is_user_calendar);
});

test('display name with defined name is name', function () {
    // Arrange
    // Make calendar token with name
    $user           = User::factory()->create();
    $calendar_token = CalendarToken::make([
        'user_id' => $user->id,
        'name'    => 'Special Calendar',
    ]);

    // Assert
    // Calendar display name is defined name
    $this->assertEquals('Special Calendar', $calendar_token->display_name);
});

test('user calendar without defined name named after user', function () {
    // Arrange
    // Make user calendar token without name, but with user name.
    $user = User::factory([
        'name' => 'Steve Smith',
    ])->create();

    $calendar_token = CalendarToken::make([
        'user_id' => $user->id,
    ]);

    // Assert
    // Calendar display name is defined name
    $this->assertEquals('Steve Smith\'s Chores', $calendar_token->display_name);
});

test('team calendar without defined name named after team', function () {
    // Arrange
    // Make team calendar token without name, but with team name
    $team = Team::factory([
        'name' => 'Smith Family',
    ])->create();

    $calendar_token = CalendarToken::make([
        'team_id' => $team->id,
    ]);

    // Assert
    // Calendar display name is defined name
    $this->assertEquals('Smith Family Chores', $calendar_token->display_name);
});
