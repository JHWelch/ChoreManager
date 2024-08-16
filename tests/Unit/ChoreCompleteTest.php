<?php

use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('when there is a next instance completes instance', function () {
    $this->testUser();
    $chore = Chore::factory()->withFirstInstance()->daily()->create();
    $firstInstance = $chore->nextInstance;

    $chore->complete();

    $firstInstance->refresh();
    expect($firstInstance->is_completed)->toBeTrue();
    $this->assertDatabaseCount((new ChoreInstance)->getTable(), 2);
});

test('can complete a chore without a chore instance', function () {
    $this->testUser();
    $chore = Chore::factory()->for($this->user)->daily()->create();

    $chore->complete();

    $now = now()->toDateString();
    $choreInstance = ChoreInstance::first();
    expect($choreInstance)->not->toBeNull();
    expect($choreInstance->chore_id)->toEqual($chore->id);
    expect($choreInstance->is_completed)->toBeTrue();
    expect($choreInstance->due_date->toDateString())->toEqual($now);
    expect($choreInstance->completed_date->toDateString())->toEqual($now);
    expect($choreInstance->user_id)->toEqual($this->user->id);
    expect($choreInstance->completed_by_id)->toEqual($this->user->id);
});

test('chore can be completed at another time', function () {
    $this->testUser();
    $chore = Chore::factory()->for($this->user)->daily()->create();
    $date = now()->subDays(3);

    $chore->complete(on: $date);

    $choreInstance = ChoreInstance::first();
    expect($choreInstance)->not->toBeNull();
    expect($choreInstance->due_date->toDateString())->toEqual($date->toDateString());
    expect($choreInstance->completed_date->toDateString())->toEqual($date->toDateString());
});

test('chore can be completed for another user', function () {
    $this->testUser();
    $user = User::factory()->hasAttached($this->team)->create();
    $chore = Chore::factory()->for($this->user)->daily()->create();

    $chore->complete(for: $user->id);

    $choreInstance = ChoreInstance::first();
    expect($choreInstance)->not->toBeNull();
    expect($choreInstance->user_id)->toEqual($user->id);
    expect($choreInstance->completed_by_id)->toEqual($user->id);
});
