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
                    'completed_date' => today()->subDays(1),
                    'user_id'        => $user1->id,
                ],
                [
                    'completed_date' => today()->subDays(2),
                    'user_id'        => $user2->id,
                ],
                [
                    'completed_date' => today()->subDays(3),
                    'user_id'        => $user1->id,
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
            'yesterday',
            $user2->name,
            '2 days ago',
            $user1->name,
            '3 days ago',
        ]);
    }
}
