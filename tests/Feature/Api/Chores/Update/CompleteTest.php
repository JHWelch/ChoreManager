<?php

use App\Models\Chore;
use App\Models\ChoreInstance;

beforeEach(function () {
    $this->callCompleteEndpoint = fn (Chore $chore) => $this->patch(
        route('api.chores.update', ['chore' => $chore]),
        ['completed' => true],
    );
});

test('calling update with complete flag completes current instance', function () {
    $this->testUser();
    $chore = Chore::factory()->for($this->user)->create();
    $chore_instance = ChoreInstance::factory()
        ->for($chore)
        ->create();

    $response = ($this->callCompleteEndpoint)($chore);

    $response->assertOk();
    expect($chore_instance->refresh()->is_completed)->toBeTrue();
});

test('user can complete chore for their team', function () {
    $this->testUser();
    $chore = Chore::factory()->for($this->team)->create();
    $chore_instance = ChoreInstance::factory()
        ->for($chore)
        ->create();

    $response = ($this->callCompleteEndpoint)($chore);

    $response->assertOk();
    expect($chore_instance->refresh()->is_completed)->toBeTrue();
});

test('user cannot complete chores they do not own', function () {
    $this->testUser();
    $chore = Chore::factory()->create();
    $chore_instance = ChoreInstance::factory()
        ->for($chore)
        ->create();

    $response = ($this->callCompleteEndpoint)($chore);

    $response->assertForbidden();
    expect($chore_instance->refresh()->is_completed)->toBeFalse();
});

test('chore is returned with new information', function () {
    $this->testUser();
    $chore = Chore::factory()
        ->for($this->user)
        ->daily()
        ->withFirstInstance()
        ->create();

    $response = ($this->callCompleteEndpoint)($chore);
    $chore->refresh();

    $response->assertJson(['data' => [
        'id' => $chore->id,
        'user_id' => $chore->user_id,
        'title' => $chore->title,
        'description' => $chore->description,
        'team_id' => $chore->team_id,
        'frequency_id' => $chore->frequency_id->value,
        'frequency_interval' => $chore->frequency_interval,
        'frequency_day_of' => $chore->frequency_day_of,
        'created_at' => $chore->created_at->toIsoString(),
        'updated_at' => $chore->updated_at->toIsoString(),
        'next_due_user_id' => $chore->nextChoreInstance?->user_id,
        'next_due_date' => $chore->next_due_date->toDateString(),
        'due_date_updated_at' => $chore->due_date_updated_at->toIsoString(),
    ]]);
});
