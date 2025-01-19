<?php

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Factories\Sequence;

beforeEach(function () {
    $this->user();
});

test('api user can get chore list', function () {
    Chore::factory()
        ->count(3)
        ->state(new Sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Do laundry'],
        ))
        ->for($this->user)
        ->create();

    $response = $this->get(route('api.chores.index'));

    $response->assertJson(['data' => [
        ['title' => 'Do dishes'],
        ['title' => 'Walk dog'],
        ['title' => 'Do laundry'],
    ]]);
    $response->assertJsonStructure(['data' => [[
        'id',
        'user_id',
        'title',
        'description',
        'team_id',
        'frequency_id',
        'frequency_interval',
        'frequency_day_of',
        'created_at',
        'updated_at',
        'next_due_user_id',
        'next_due_date',
        'due_date_updated_at',
    ]]]);
});

test('chores returned have next due date', function () {
    $chore_instance = ChoreInstance::factory()
        ->for(Chore::factory()->for($this->user))
        ->for($this->user)
        ->create();

    $response = $this->get(route('api.chores.index'));

    $response->assertJson(['data' => [[
        'next_due_date' => $chore_instance->due_date->toDateString(),
        'due_date_updated_at' => $chore_instance->updated_at->toJSON(),
    ]]]);
});

test('includes chores for team', function () {
    $chore = Chore::factory()
        ->for($this->team)
        ->withFirstInstance()
        ->create();

    $response = $this->get(route('api.chores.index'));

    $response->assertJson(['data' => [[
        'id' => $chore->id,
    ]]]);
});

test('user will not get other s chores', function () {
    $chore = Chore::factory()
        ->withFirstInstance()
        ->create();

    $response = $this->get(route('api.chores.index'));

    $response->assertJsonMissing(['data' => [[
        'id' => $chore->id,
    ]]]);
});
