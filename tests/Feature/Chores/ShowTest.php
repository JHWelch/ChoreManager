<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use LazilyRefreshDatabase;

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
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function user_cannot_view_chores_for_another_user()
    {
        // Arrange
        // Create user and  chores for another user
        $this->testUser();
        $chore = Chore::factory()->forUser()->create();

        // Act
        // Call show endpoint
        $response = $this->get(route('chores.show', ['chore' => $chore]));

        // Assert
        // Unauthorized
        $response->assertStatus(Response::HTTP_FORBIDDEN);
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
        $chore    = Chore::factory()->for($this->user)->withFirstInstance()->create();
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

        $chore = Chore::factory()
            ->for($this->user)
            ->has(
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
            )
            ->create();

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

    /** @test */
    public function can_complete_chore_for_another_another_team_user()
    {
        // Arrange
        // Create acting as user and another user in the same team and chore.
        $this->testUser();
        $other_user = User::factory()->hasAttached($this->team)->create();

        $chore = Chore::factory()
            ->for($this->team)
            ->for($other_user)
            ->withFirstInstance()
            ->create();

        // Act
        // Complete the chore for the other user
        Livewire::test(Show::class, [
            'chore' => $chore,
        ])
            ->set('user_id', $other_user->id)
            ->call('customComplete');

        // Assert
        // The chore is completed and completed by the user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'        => $chore->id,
            'completed_date'  => today(),
            'completed_by_id' => $other_user->id,
        ]);
    }

    /** @test */
    public function can_complete_chore_on_a_past_date()
    {
        // Arrange
        // Create acting as user and chore
        $user  = $this->testUser()['user'];
        $date  = today()->subDays(2);
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance()
            ->create();

        // Act
        // Complete the chore on a date in the past
        Livewire::test(Show::class, [
            'chore' => $chore,
        ])
            ->set('completed_date', $date)
            ->call('customComplete');

        // Assert
        // The chore is completed with a completion date in the past
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'        => $chore->id,
            'completed_date'  => $date,
            'completed_by_id' => $user->id,
        ]);
    }
}
