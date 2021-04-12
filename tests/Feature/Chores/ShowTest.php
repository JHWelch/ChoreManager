<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_reach_show_page()
    {
        // Arrange
        // Create chore and user
        $chore = Chore::factory()->for($this->testUser()['user'])->create();

        // Act
        // navigate to chore show page
        $response = $this->get(route('chores.show', $chore));

        // Assert
        // that page can be reached.
        $response->assertStatus(200);
    }

    /** @test */
    public function can_see_chore_info_on_chores_show()
    {
        // Arrange
        // Create a chore
        $chore = Chore::factory([
            'title'              => 'Walk the dog.',
            'description'        => 'Do not forget the poop bags.',
            'frequency_id'       => 1,
            'frequency_interval' => 2,
            'user_id'            => $this->testUser()['user']->id,
        ])->create();

        // Act
        // Navigate to Chore page
        $component = Livewire::test(Show::class, ['chore' => $chore]);

        // Assert
        // Can see all chore details
        $component->assertSee('Walk the dog.');
        $component->assertSee('Do not forget the poop bags.');
        $component->assertSee('Repeats every 2 days');
    }

    /** @test */
    public function can_complete_chore_from_chore_page()
    {
        // Arrange
        // Create a chore
        $this->testUser();
        $chore    = Chore::factory()->withFirstInstance()->create();
        $instance = $chore->nextChoreInstance;

        // Act
        // Navigate to chore page and complete chore
        Livewire::test(Show::class, ['chore' => $chore])
            ->call('complete');

        // Assert
        // Chore instance has been completed
        $instance->refresh();
        $this->assertEquals(true, $instance->is_completed);
    }

    /** @test */
    public function can_see_chore_history()
    {
        // Arrange
        // Create chore with several completed instances
        $user1 = $this->testUser()['user'];
        $user2 = User::factory()->create();

        $chore = Chore::factory()->has(
            ChoreInstance::factory()->count(3)->sequence(
                [
                    'completed_date'  => today()->subDays(1),
                    'user_id'         => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
                [
                    'completed_date'  => today()->subDays(2),
                    'user_id'         => $user2->id,
                    'completed_by_id' => $user2->id,
                ],
                [
                    'completed_date'  => today()->subDays(3),
                    'user_id'         => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
            )
        )->create();

        // Act
        // Navigate to Chore Show page
        $component = Livewire::test(Show::class, ['chore' => $chore]);

        // Assert
        // You see chore instance history
        $component->assertSeeInOrder([
            $user1->name,
            'completed chore',
            'yesterday',
            $user2->name,
            'completed chore',
            '2 days ago',
            $user1->name,
            'completed chore',
            '3 days ago',
        ]);
    }

    /** @test */
    public function when_completing_a_chore_it_will_appear_in_history_and_next_instance_updates()
    {
        // Arrange
        // Create a daily chore and a first instance due today
        $today = today();
        $user  = $this->testUser()['user'];
        $chore = Chore::factory([
            'frequency_id' => Frequency::DAILY,
        ])
            ->withFirstInstance($today)
            ->for($user)
            ->create();

        // Act 1
        // Navigate to show page
        $component = Livewire::test(Show::class, ['chore' => $chore]);

        // Assert 1
        // Current chore instance is showing, nothing is showing for history
        $component->assertSeeInOrder([
            'Due on',
            $today->toFormattedDateString(),
        ]);
        $component->assertDontSee('completed chore');

        // Act 2
        // Complete ChoreInstance
        $component->call('complete');

        // Assert 2
        // See completed chore instance in history and new chore instance in detail view
        $component->assertSeeInOrder([
            'Due on',
            $today->addDay(1)->toFormattedDateString(),
        ]);
        $component->assertSeeInOrder([
            $user->name,
            'completed chore',
            'today',
        ]);
    }

    /** @test */
    public function chores_assigned_to_team_display_team_as_owner()
    {
        // Arrange
        // Create chore assigned to team
        $team  = $this->testUser()['team'];
        $chore = Chore::factory([
            'title' => 'Walk the dog',
        ])
            ->assignedToTeam()
            ->for($team)
            ->create();

        // Act
        // Navigate to show page
        $component = Livewire::test(Show::class, ['chore' => $chore]);

        // Assert
        // The team is assigned as the owner
        $component->assertSeeInOrder([
            'Owner',
            $team->name,
        ]);
    }
}
