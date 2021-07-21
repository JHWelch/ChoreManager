<?php

namespace Tests\Feature;

use App\Http\Livewire\ChoreInstances\Index as ChoreInstanceIndex;
use App\Http\Livewire\Chores\Index as ChoreIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    public function assertAndReemit($component)
    {
        // This is a workaround because the emit does not seem to be working
        // Correctly in this test, but does in the running code.
        // Make sure emit was triggered, but re-emit
        $component->assertEmitted('filterUpdated');
        $component->emit('filterUpdated');
    }

    /** @test */
    public function when_filter_is_set_to_user_see_only_users_chores()
    {
        // Arrange
        // Create two users on a team with chores
        $team  = Team::factory()->create();
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

        // Act
        // Navigate to index
        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'user');

        // Assert
        // See only the user's chores
        $component->assertSee('Walk the dog.');
        $component->assertDontSee('Wash the dishes.');
    }

    /** @test */
    public function when_filter_is_set_to_team_see_all_users_in_that_teams_chores()
    {
        // Arrange
        // Create two users on a team with chores
        $users = User::factory()->count(2)->hasTeams()->create();
        $team  = Team::first();

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

        // Act
        // Navigate to index
        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'team');
        $this->assertAndReemit($component);

        // Assert
        // See only the user's chores
        $component->assertSee('Walk the dog.');
        $component->assertSee('Wash the dishes.');
    }

    /** @test */
    public function chores_with_instances_assigned_to_others_do_not_show_on_chore_owners_filter()
    {
        // Arrange
        // Create two users on a team with chores
        $this->testUser();
        $other_user = User::factory()->hasAttached($this->team)->create();

        Chore::factory([
            'title'   => 'Walk the dog.',
        ])
            ->for($this->team)
            ->for($this->user)
            ->has(ChoreInstance::factory([
                'user_id'  => $other_user->id,
                'due_date' => today(),
            ]))
            ->create();

        // Act
        // Navigate to index
        $component = Livewire::test(ChoreInstanceIndex::class)
            ->call('setTeamFilter', 'user');
        $this->assertAndReemit($component);

        // Assert
        // See only the user's chores
        $component->assertDontSee('Walk the dog.');
        $component->call('setTeamFilter', 'team');
        $this->assertAndReemit($component);
        $component->assertSee('Walk the dog.');
    }

    /** @test */
    public function chores_assigned_to_team_show_on_team_page_but_not_user_page()
    {
        // Arrange
        // Create chore assigned to no one
        $this->testUser();
        Chore::factory([
            'title' => 'Walk the dog',
        ])
            ->assignedToTeam()
            ->for($this->team)
            ->create();

        // Act
        // Navigate to index page and filter by group
        $component = Livewire::test(ChoreIndex::class)
            ->call('setTeamFilter', 'team');
        $this->assertAndReemit($component);

        // Assert
        // That the chore can be seen
        $component->assertSee('Walk the dog');
        $component->call('setTeamFilter', 'user');
        $this->assertAndReemit($component);
        $component->assertDontSee('Walk the dog');
    }

    /** @test */
    public function filter_persists_between_component_loads()
    {
        // Arrange
        // Create user
        $this->testUser();

        // Act
        // Load Livewire component, toggle filter to Team, Load again
        Livewire::test(ChoreInstanceIndex::class)
            ->call('setTeamFilter', 'team');

        $component = Livewire::test(ChoreInstanceIndex::class);

        // Assert
        // The Team filter is still active.
        $component->assertSet('team_or_user', 'team');
    }
}
