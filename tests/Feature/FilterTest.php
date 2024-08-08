<?php

namespace Tests\Feature;

use App\Livewire\ChoreInstances\Index as ChoreInstanceIndex;
use App\Livewire\Chores\Index as ChoreIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class FilterTest extends TestCase
{
    public function assertAndReDispatch($component)
    {
        // This is a workaround because the emit does not seem to be working
        // Correctly in this test, but does in the running code.
        // Make sure emit was triggered, but re-emit
        $component->assertDispatched('filterUpdated');
        $component->dispatch('filterUpdated');
    }

    /** @test */
    public function when_filter_is_set_to_user_see_only_users_chores(): void
    {
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

        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'user');

        $component->assertSee('Walk the dog.');
        $component->assertDontSee('Wash the dishes.');
    }

    /** @test */
    public function when_filter_is_set_to_team_see_all_users_in_that_teams_chores(): void
    {
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

        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'team');

        $this->assertAndReDispatch($component);
        $component->assertSee('Walk the dog.');
        $component->assertSee('Wash the dishes.');
    }

    /** @test */
    public function chores_with_instances_assigned_to_others_do_not_show_on_chore_owners_filter(): void
    {
        $this->testUser();
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

        $component = Livewire::test(ChoreInstanceIndex::class)
            ->call('setTeamFilter', 'user');

        $this->assertAndReDispatch($component);
        $component->assertDontSee('Walk the dog.');

        $component->call('setTeamFilter', 'team');

        $this->assertAndReDispatch($component);
        $component->assertSee('Walk the dog.');
    }

    /** @test */
    public function chores_assigned_to_team_show_on_team_page_but_not_user_page(): void
    {
        $this->testUser();
        Chore::factory([
            'title' => 'Walk the dog',
        ])
            ->assignedToTeam()
            ->for($this->team)
            ->create();

        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'team');

        $this->assertAndReDispatch($component);
        $component->assertSee('Walk the dog');

        $component->call('setTeamFilter', 'user');

        $this->assertAndReDispatch($component);
        $component->assertDontSee('Walk the dog');
    }

    /** @test */
    public function filter_persists_between_component_loads(): void
    {
        $this->testUser();

        Livewire::test(ChoreInstanceIndex::class)
            ->call('setTeamFilter', 'team');

        $component = Livewire::test(ChoreInstanceIndex::class);

        $component->assertSet('team_or_user', 'team');
    }
}
