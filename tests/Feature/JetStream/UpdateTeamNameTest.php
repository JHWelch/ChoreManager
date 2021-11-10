<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateTeamNameForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(UpdateTeamNameForm::class, ['team' => $user->currentTeam])
                ->set(['state' => ['name' => 'Test Team']])
                ->call('updateTeamName');

    $this->assertCount(1, $user->fresh()->ownedTeams);
    $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
});
