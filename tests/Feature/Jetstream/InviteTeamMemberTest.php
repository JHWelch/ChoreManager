<?php

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Laravel\Jetstream\Mail\TeamInvitation;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Config::set([
        'mail.from.address' => 'fake@example.com',
    ]);
});

test('team members can be invited to team', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('addTeamMemberForm', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ])->call('addTeamMember');

    Mail::assertSent(TeamInvitation::class);

    expect($user->currentTeam->fresh()->teamInvitations)->toHaveCount(1);
});

test('team member invitations can be cancelled', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    // Add the team member...
    $component = livewire(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('addTeamMemberForm', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ])->call('addTeamMember');

    $invitationId = $user->currentTeam->fresh()->teamInvitations->first()->id;

    // Cancel the team invitation...
    $component->call('cancelTeamInvitation', $invitationId);

    expect($user->currentTeam->fresh()->teamInvitations)->toHaveCount(0);
});
