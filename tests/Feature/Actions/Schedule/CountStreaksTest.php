<?php

use App\Actions\Schedule\CountStreaks;
use App\Models\Chore;
use App\Models\StreakCount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

function uncompletedChoreForUser($user)
{
    Chore::factory()
        ->for($user)
        ->withFirstInstance((new Carbon)->subDay(), $user->id)
        ->create();
}

function uncompletedChoreForTeam($team)
{
    Chore::factory()
        ->for($team)
        ->withFirstInstance((new Carbon)->subDay(), $team->users()->first()->id)
        ->create();
}

it('creates streaks for users who have not started one', function () {
    $user = User::factory()->create();

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'team_id' => null,
        'user_id' => $user->id,
        'count' => 1,
        'ended_at' => null,
    ]);
});

it('will not create streak if user has unfinished chores', function () {
    $user = User::factory()->create();
    uncompletedChoreForUser($user);

    (new CountStreaks)();

    assertDatabaseCount((new StreakCount)->getTable(), 0);
});

it('increments current streaks for users', function () {
    $user = User::factory()->create();
    $streak = StreakCount::factory()
        ->for($user)
        ->create(['count' => 5]);

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'id' => $streak->id,
        'team_id' => null,
        'user_id' => $user->id,
        'count' => 6,
        'ended_at' => null,
    ]);
});

it('will not increment streak if user has unfinished chores', function () {
    $user = User::factory()->create();
    $streak = StreakCount::factory()
        ->for($user)
        ->create(['count' => 5]);
    uncompletedChoreForUser($user);

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'id' => $streak->id,
        'team_id' => null,
        'user_id' => $user->id,
        'count' => 5,
    ]);
});

it('ends streak if user has uncompleted chores', function () {
    $user = User::factory()->create();
    $streak = StreakCount::factory()
        ->for($user)
        ->create(['count' => 5]);
    uncompletedChoreForUser($user);

    (new CountStreaks)();

    $streak->refresh();
    expect($streak->ended_at)->not->toBeNull();
    expect(5)->toEqual($streak->count);
});

it('creates streaks for teams who have not started one', function () {
    $team = Team::factory()->hasUsers()->create();

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'user_id' => null,
        'team_id' => $team->id,
        'count' => 1,
        'ended_at' => null,
    ]);
});

it('will not create streak if team has unfinished chores', function () {
    $team = Team::factory()->hasUsers()->create();
    uncompletedChoreForTeam($team);

    (new CountStreaks)();

    assertDatabaseMissing((new StreakCount)->getTable(), [
        'team_id' => $team->id,
    ]);
});

it('increments current streaks for teams', function () {
    $team = Team::factory()->hasUsers()->create();
    $streak = StreakCount::factory()
        ->for($team)
        ->create(['count' => 5]);

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'id' => $streak->id,
        'team_id' => $team->id,
        'count' => 6,
        'ended_at' => null,
    ]);
});

it('will not increment streak if team has unfinished chores', function () {
    $team = Team::factory()->hasUsers()->create();
    $streak = StreakCount::factory()
        ->for($team)
        ->create(['count' => 5]);
    uncompletedChoreForTeam($team);

    (new CountStreaks)();

    assertDatabaseHas((new StreakCount)->getTable(), [
        'id' => $streak->id,
        'team_id' => $team->id,
        'count' => 5,
    ]);
});

it('ends streak if team has uncompleted chores', function () {
    $team = Team::factory()->hasUsers()->create();
    $streak = StreakCount::factory()
        ->for($team)
        ->create(['count' => 5]);
    uncompletedChoreForTeam($team);

    (new CountStreaks)();

    $streak->refresh();
    expect($streak->ended_at)->not->toBeNull();
    expect(5)->toEqual($streak->count);
});

it('sets time stamps correctly for user', function () {
    $user = User::factory()->create();

    (new CountStreaks)();

    assertDatabaseMissing((new StreakCount)->getTable(), [
        'user_id' => $user->id,
        'created_at' => null,
        'updated_at' => null,
    ]);
});

it('sets time stamps correctly for team', function () {
    $team = Team::factory()->hasUsers()->create();

    (new CountStreaks)();

    assertDatabaseMissing((new StreakCount)->getTable(), [
        'team_id' => $team->id,
        'created_at' => null,
        'updated_at' => null,
    ]);
});

it('updates timestamp on increment', function () {
    $user = User::factory()->create();
    $streak = StreakCount::factory()
        ->for($user)
        ->create(['count' => 5]);
    Carbon::setTestNow(Carbon::now()->addDay());

    (new CountStreaks)();

    assertDatabaseMissing((new StreakCount)->getTable(), [
        'id' => $streak->id,
        'updated_at' => $streak->updated_at,
    ]);
});
