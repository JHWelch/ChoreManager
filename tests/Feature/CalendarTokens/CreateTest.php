<?php

use App\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Livewire\Livewire;

it('can create a calendar token to display only their chores', function () {
    $user = $this->testUser()['user'];

    Livewire::test(Index::class)
        ->set('form.type', 'user')
        ->call('addCalendarLink');

    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $user->id,
        'team_id' => null,
    ]);
});

test('calendar token has generated uuid', function () {
    $this->testUser();

    Livewire::test(Index::class)
        ->set('form.type', 'user')
        ->call('addCalendarLink');

    expect(strlen(CalendarToken::first()->token))->toEqual(36);
});

it('can create a calendar token to display their teams chores', function () {
    $userAndTeam = $this->testUser();

    Livewire::test(Index::class)
        ->set('form.type', 'team')
        ->set('form.team_id', $userAndTeam['team']->id)
        ->call('addCalendarLink');

    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $userAndTeam['user']->id,
        'team_id' => $userAndTeam['team']->id,
    ]);
});

test('when team calendar is selected user must pick team', function () {
    $this->testUser();

    $component = Livewire::test(Index::class)
        ->set('form.type', 'team')
        ->call('addCalendarLink');

    $component->assertHasErrors(['form.team_id' => 'required_if']);
    $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
});

test('if user calendar selected will not have team even if specified', function () {
    $userAndTeam = $this->testUser();

    Livewire::test(Index::class)
        ->set('form.type', 'user')
        ->set('form.team_id', $userAndTeam['team']->id)
        ->call('addCalendarLink');

    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'user_id' => $userAndTeam['user']->id,
        'team_id' => null,
    ]);
});

test('calendars can be created with names', function () {
    $user = $this->testUser()['user'];

    Livewire::test(Index::class)
        ->set('form.type', 'user')
        ->set('form.name', 'Chore Calendar')
        ->call('addCalendarLink');

    $this->assertDatabaseHas((new CalendarToken)->getTable(), [
        'name' => 'Chore Calendar',
        'user_id' => $user->id,
        'team_id' => null,
    ]);
});
