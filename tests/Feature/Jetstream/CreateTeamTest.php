<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;

use function Pest\Livewire\livewire;

test('teams can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    livewire(CreateTeamForm::class)
        ->set(['state' => ['name' => 'Test Team']])
        ->call('createTeam');

    expect($user->fresh()->ownedTeams)->toHaveCount(2);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->name)->toEqual('Test Team');
});
