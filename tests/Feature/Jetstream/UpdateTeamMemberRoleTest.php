<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;

use function Pest\Livewire\livewire;

test('team member roles can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(),
        ['role' => 'admin']
    );

    livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('managingRoleFor', $otherUser)
        ->set('currentRole', 'editor')
        ->call('updateRole');

    expect($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(),
        'editor'
    ))->toBeTrue();
});

test('only team owner can update team member roles', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(),
        ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('managingRoleFor', $otherUser)
        ->set('currentRole', 'editor')
        ->call('updateRole')
        ->assertForbidden();

    expect($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(),
        'admin'
    ))->toBeTrue();
});
