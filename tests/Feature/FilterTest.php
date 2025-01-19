<?php

use App\Livewire\ChoreInstances\Index as ChoreInstanceIndex;
use App\Livewire\Chores\Index as ChoreIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;

use function Pest\Livewire\livewire;

function assertAndReDispatch($component)
{
    // This is a workaround because the emit does not seem to be working
    // Correctly in this test, but does in the running code.
    // Make sure emit was triggered, but re-emit
    $component->assertDispatched('filterUpdated');
    $component->dispatch('filterUpdated');
}

test('when filter is set to user see only users chores', function () {
    $team = Team::factory()->create();
    $users = User::factory()->count(2)->hasAttached($team)->create();
    Chore::factory([
        'title' => 'Walk the dog.',
    ])
        ->for($users->first())
        ->withFirstInstance()
        ->create();
    Chore::factory([
        'title' => 'Wash the dishes.',
    ])
        ->for($users->pop())
        ->withFirstInstance()
        ->create();
    $this->actingAs($users->first());

    livewire(ChoreIndex::class)
        ->call('setTeamFilter', 'user')

        ->assertSee('Walk the dog.')
        ->assertDontSee('Wash the dishes.');
});

test('when filter is set to team see all users in that teams chores', function () {
    $users = User::factory()->count(2)->hasTeams()->create();
    $team = Team::first();
    Chore::factory([
        'title' => 'Walk the dog.',
    ])
        ->for($users->first())
        ->for($team)
        ->withFirstInstance()
        ->create();
    Chore::factory([
        'title' => 'Wash the dishes.',
    ])
        ->for($users->pop())
        ->for($team)
        ->withFirstInstance()
        ->create();
    $this->actingAs($users->first());
    $users->first()->switchTeam($team);

    $component = livewire(ChoreIndex::class)
        ->call('setTeamFilter', 'team');

    assertAndReDispatch($component);
    $component->assertSee('Walk the dog.');
    $component->assertSee('Wash the dishes.');
});

test('chores with instances assigned to others do not show on chore owners filter', function () {
    $this->user();
    $other_user = User::factory()->hasAttached($this->team)->create();
    Chore::factory([
        'title' => 'Walk the dog.',
    ])
        ->for($this->team)
        ->for($this->user)
        ->has(ChoreInstance::factory([
            'user_id' => $other_user->id,
            'due_date' => today(),
        ]))
        ->create();

    $component = livewire(ChoreInstanceIndex::class)
        ->call('setTeamFilter', 'user');

    assertAndReDispatch($component);
    $component->assertDontSee('Walk the dog.');

    $component->call('setTeamFilter', 'team');

    assertAndReDispatch($component);
    $component->assertSee('Walk the dog.');
});

test('chores assigned to team show on team page but not user page', function () {
    $this->user();
    Chore::factory([
        'title' => 'Walk the dog',
    ])
        ->assignedToTeam()
        ->for($this->team)
        ->create();

    $component = livewire(ChoreIndex::class)
        ->call('setTeamFilter', 'team');

    assertAndReDispatch($component);
    $component->assertSee('Walk the dog');

    $component->call('setTeamFilter', 'user');

    assertAndReDispatch($component);
    $component->assertDontSee('Walk the dog');
});

test('filter persists between component loads', function () {
    $this->user();

    livewire(ChoreInstanceIndex::class)
        ->call('setTeamFilter', 'team');

    $component = livewire(ChoreInstanceIndex::class);

    $component->assertSet('team_or_user', 'team');
});
