<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('teams can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(CreateTeamForm::class)
                ->set(['state' => ['name' => 'Test Team']])
                ->call('createTeam');

    expect($user->fresh()->ownedTeams)->toHaveCount(2);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->name)->toEqual('Test Team');
});
