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
        $acting_as_user = $this->testUser()['user'];
        $team           = Team::first();
        $other_user     = User::factory()->hasAttached($team)->create();

        Chore::factory([
            'title'   => 'Walk the dog.',
        ])
            ->for($team)
            ->for($acting_as_user)
            ->has(ChoreInstance::factory([
                'user_id' => $other_user->id,
            ]))
            ->create();

        // Act
        // Navigate to index
        $component = Livewire::test(ChoreInstanceIndex::class)
            ->call('setTeamFilter', 'user');

        // Assert
        // See only the user's chores
        $component->assertDontSee('Walk the dog.');
        $component->call('setTeamFilter', 'team');
        $component->assertSee('Walk the dog.');
    }
}
