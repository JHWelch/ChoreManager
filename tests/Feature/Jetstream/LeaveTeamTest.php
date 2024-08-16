<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;

use function Pest\Livewire\livewire;

test('users can leave teams', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(),
        ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->call('leaveTeam');

    expect($user->currentTeam->fresh()->users)->toHaveCount(0);
});

test('team owners cant leave their own team', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->call('leaveTeam')
        ->assertHasErrors(['team']);

    expect($user->currentTeam->fresh())->not->toBeNull();
});
