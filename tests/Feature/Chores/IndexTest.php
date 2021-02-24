<?php

namespace Tests\Feature\Chores;

use App\Http\Livewire\Chores\Index;
use App\Models\Chore;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_navigate_to_chores_index()
    {
        // Arrange
        // Create and act as a user
        $this->testUser();

        // Act
        // Navigate to the Chores index page
        $response = $this->get(route('chores.index'));

        // Assert
        // Assert that it is returns a 200
        $response->assertStatus(200);
    }

    /** @test */
    public function chores_display_on_index_page()
    {
        // Arrange
        // create a user and some chores
        $user = $this->testUser();
        Chore::factory()
            ->count(3)
            ->state(new Sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Do laundry'],
            ))
            ->for($user)
            ->create();

        // Act
        // Navigate to the chores index page
        $component = Livewire::test(Index::class);

        // Assert
        // Assert we can see all the chore titles
        $component->assertSee('Do dishes')
            ->assertSee('Walk dog')
            ->assertSee('Do laundry');
    }
}
