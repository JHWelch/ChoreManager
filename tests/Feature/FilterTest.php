<?php

namespace Tests\Feature;

use App\Http\Livewire\Chores\Index;
use App\Models\Chore;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        ])->for($users->first())->create();
        Chore::factory([
            'title' => 'Wash the dishes.',
        ])->for($users->pop())->create();

        ray(Chore::all());

        $this->actingAs($users->first());

        // Act
        // Navigate to index
        $component = Livewire::test(Index::class)
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

        $team = Team::first();

        Chore::factory([
            'title' => 'Walk the dog.',
        ])->for($users->first())->for($team)->create();
        Chore::factory([
            'title' => 'Wash the dishes.',
        ])->for($users->pop())->for($team)->create();

        $this->actingAs($users->first());
        $users->first()->switchTeam($team);

        // Act
        // Navigate to index
        $component = Livewire::test(Index::class)
            ->call('setTeamFilter', 'team');

        // Assert
        // See only the user's chores
        $component->assertSee('Walk the dog.');
        $component->assertSee('Wash the dishes.');
    }
}
