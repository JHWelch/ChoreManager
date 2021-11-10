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
    expect($calendar_token->is_team_calendar)->toEqual(true);
    expect($calendar_token->is_user_calendar)->toEqual(false);
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
    expect($calendar_token->is_team_calendar)->toEqual(false);
    expect($calendar_token->is_user_calendar)->toEqual(true);
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
    expect($calendar_token->display_name)->toEqual('Special Calendar');
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
    expect($calendar_token->display_name)->toEqual('Steve Smith\'s Chores');
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
    expect($calendar_token->display_name)->toEqual('Smith Family Chores');
});
