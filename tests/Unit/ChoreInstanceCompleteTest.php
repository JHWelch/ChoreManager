<?php

use App\Enums\FrequencyType;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;

test('do not repeat chore creates no instance', function () {
    $chore = Chore::factory()->create([
        'frequency_id' => FrequencyType::doesNotRepeat,
    ]);

    $chore->createNewInstance();

    expect($chore->nextChoreInstance)->toEqual(null);
});

test('chores can be completed with a frequency', function () {
    $chore = Chore::factory()->create([
        'frequency_id' => FrequencyType::daily,
    ]);

    $chore->createNewInstance();

    expect($chore->nextChoreInstance->due_date->toDateString())->toEqual(today()->addDay()->toDateString());
});

test('chores can be completed with a frequency plus interval', function () {
    $chore1 = Chore::factory()->create([
        'frequency_id' => FrequencyType::daily,
        'frequency_interval' => 2,
    ]);
    $chore2 = Chore::factory()->create([
        'frequency_id' => FrequencyType::weekly,
        'frequency_interval' => 3,
    ]);

    $chore1->createNewInstance();
    $chore2->createNewInstance();

    expect($chore1->nextChoreInstance->due_date->toDateString())->toEqual(today()->addDays(2)->toDateString());
    expect($chore2->nextChoreInstance->due_date->toDateString())->toEqual(today()->addWeeks(3)->toDateString());
});

test('chores can be completed with day of frequency', function () {
    Carbon::setTestNow('2021-07-06');
    $chore1 = Chore::factory()->create([
        'frequency_id' => FrequencyType::weekly,
        'frequency_interval' => 1,
        'frequency_day_of' => Carbon::TUESDAY,
    ]);
    $chore2 = Chore::factory()->create([
        'frequency_id' => FrequencyType::monthly,
        'frequency_interval' => 1,
        'frequency_day_of' => 17,
    ]);

    $chore1->createNewInstance();
    $chore2->createNewInstance();

    expect($chore1->nextChoreInstance->due_date->toDateString())->toEqual('2021-07-13');
    expect($chore2->nextChoreInstance->due_date->toDateString())->toEqual('2021-08-17');
});

test('completing a chore instance creates a new instance with same owner', function () {
    $user = User::factory()->create();
    $chore = Chore::factory()
        ->repeatable()
        ->for($user)
        ->withFirstInstance()
        ->create();

    $chore->complete();

    expect($chore->nextChoreInstance->user_id)->toEqual($user->id);
});

test('when a chore is completed the completed by id is set to the user completing it', function () {
    $acting_as_user = $this->testUser()['user'];
    $assigned_user = User::factory()->create();
    $chore = Chore::factory()->for($assigned_user)->withFirstInstance()->create();

    $chore->complete();

    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id' => $assigned_user->id,
        'completed_by_id' => $acting_as_user->id,
        'chore_id' => $chore->id,
    ]);
});

test('when a chore assigned to a team is completed the next instance is assigned to the next person alphabetically', function () {
    $user_and_team = $this->testUser(['name' => 'Albert Albany']);
    $team = $user_and_team['team'];
    $users = User::factory()
        ->hasAttached($team)
        ->count(2)
        ->sequence(
            ['name' => 'Bobby Boston'],
            ['name' => 'Charlie Chicago'],
        )
        ->create();
    $user2 = $users->first();
    $user3 = $users->last();
    $chore = Chore::factory(['frequency_id' => FrequencyType::daily])
        ->for($team)
        ->assignedToTeam()
        ->has(ChoreInstance::factory()->for($user2))
        ->create();

    $chore->complete();

    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id' => $user3->id,
        'chore_id' => $chore->id,
        'completed_date' => null,
    ]);
});

test('when an instance is assigned to the last person alphabetically it will wrap around', function () {
    $user_and_team = $this->testUser(['name' => 'Albert Albany']);
    $user1 = $user_and_team['user'];
    $team = $user_and_team['team'];
    $users = User::factory()
        ->hasAttached($team)
        ->count(2)
        ->sequence(
            ['name' => 'Bobby Boston'],
            ['name' => 'Charlie Chicago'],
        )
        ->create();
    $user3 = $users->last();
    $chore = Chore::factory(['frequency_id' => FrequencyType::daily])
        ->for($team)
        ->assignedToTeam()
        ->has(ChoreInstance::factory()->for($user3))
        ->create();

    $chore->complete();

    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id' => $user1->id,
        'chore_id' => $chore->id,
        'completed_date' => null,
    ]);
});

test('when chore is completed in the past the next instance date is based on that date', function () {
    $date = today();
    $chore = Chore::factory()->withFirstInstance()->create([
        'frequency_id' => FrequencyType::daily,
        'frequency_interval' => 4,
    ]);

    $chore->complete(null, $date->subDays(3));

    expect($chore->refresh()->nextChoreInstance->due_date->toDateString())->toEqual(today()->addDay()->toDateString());
});
