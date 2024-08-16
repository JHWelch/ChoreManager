<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\UpdateTeamNameForm;

use function Pest\Livewire\livewire;

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    livewire(UpdateTeamNameForm::class, ['team' => $user->currentTeam])
        ->set(['state' => ['name' => 'Test Team']])
        ->call('updateTeamName');

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->currentTeam->fresh()->name)->toEqual('Test Team');
});
