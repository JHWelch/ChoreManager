<?php

use App\Models\CalendarToken;
use App\Models\Team;
use App\Models\User;

test('calendar token is team calendar', function () {
    $calendar_token = CalendarToken::make([
        'user_id' => 1,
        'team_id' => 1,
    ]);
    expect($calendar_token)
        ->is_team_calendar->toEqual(true)
        ->is_user_calendar->toEqual(false);
});

test('is team calendar attribute false', function () {
    $calendar_token = CalendarToken::make([
        'user_id' => 1,
        'team_id' => null,
    ]);

    expect($calendar_token)
        ->is_team_calendar->toEqual(false)
        ->is_user_calendar->toEqual(true);
});

test('display name with defined name is name', function () {
    $user = User::factory()->create();
    $calendar_token = CalendarToken::make([
        'user_id' => $user->id,
        'name' => 'Special Calendar',
    ]);

    expect($calendar_token->display_name)->toEqual('Special Calendar');
});

test('user calendar without defined name named after user', function () {
    $user = User::factory([
        'name' => 'Steve Smith',
    ])->create();

    $calendar_token = CalendarToken::make([
        'user_id' => $user->id,
    ]);

    expect($calendar_token->display_name)->toEqual('Steve Smith\'s Chores');
});

test('team calendar without defined name named after team', function () {
    $team = Team::factory([
        'name' => 'Smith Family',
    ])->create();
    $calendar_token = CalendarToken::make([
        'team_id' => $team->id,
    ]);

    expect($calendar_token->display_name)->toEqual('Smith Family Chores');
});
