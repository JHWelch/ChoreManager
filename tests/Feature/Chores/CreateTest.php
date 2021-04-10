<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chore_edit_page_can_be_reached()
    {
        // Arrange
        // Create a test user
        $this->testUser();

        // Act
        // Navigate to Chore Create page
        $response = $this->get(route('chores.create'));

        // Assert
        // A page is successfully returned
        $response->assertStatus(200);
    }

    /** @test */
    public function can_create_chore()
    {
        // Arrange
        $user = $this->testUser()['user'];

        // Act
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->call('save');

        // Assert
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => 'Do dishes',
            'description'  => 'Do the dishes every night.',
            'frequency_id' => Frequency::DAILY,
            'user_id'      => $user->id,
        ]);
    }

    /** @test */
    public function a_user_can_assign_a_chore_to_another_team_member()
    {
        // Arrange
        // Create team with two users, log in with first
        $users         = User::factory()->count(2)->hasTeams()->create();
        $team          = Team::first();
        $assigned_user = $users->pop();
        $chore         = Chore::factory()->raw();

        $this->actingAs($users->first());
        $users->first()->switchTeam($team);

        // Act
        // Create chore, assign to user
        Livewire::test(Save::class)
             ->set('chore.title', $chore['title'])
             ->set('chore.description', $chore['description'])
             ->set('chore.frequency_id', $chore['frequency_id'])
             ->set('chore.user_id', $assigned_user->id)
             ->set('chore_instance.due_date', null)
             ->call('save');

        // Assert
        // The chore is created and assigned to that user
        $this->assertDatabaseHas((new Chore)->getTable(), [
             'user_id'      => $assigned_user->id,
             'title'        => $chore['title'],
             'description'  => $chore['description'],
             'frequency_id' => $chore['frequency_id'],
         ]);
    }

    /** @test */
    public function a_chore_can_be_assigned_to_a_team()
    {
        // Arrange
        // Create user
        $this->testUser();
        $chore = Chore::factory()->raw();

        // Act
        // Navigate to create chore, create chore without owner
        $component = Livewire::test(Save::class)
            ->set('chore.title', $chore['title'])
            ->set('chore.description', $chore['description'])
            ->set('chore.frequency_id', $chore['frequency_id'])
            ->set('chore.user_id', null)
            ->call('save');

        // Assert
        // Chore is create with no owner.
        $component->assertHasNoErrors();
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => $chore['title'],
            'description'  => $chore['description'],
            'frequency_id' => $chore['frequency_id'],
            'user_id'      => null,
        ]);
    }
}
