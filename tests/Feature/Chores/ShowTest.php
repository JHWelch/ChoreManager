<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
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
}
