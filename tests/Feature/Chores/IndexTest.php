<?php

namespace Tests\Feature\Chores;

use App\Http\Livewire\Chores\Index;
use App\Models\Chore;
use App\Models\ChoreInstance;
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
        $this->testUser()['user'];

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
        $user = $this->testUser()['user'];
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

    /*****************************
     * Sorting
     *****************************/

    /** @test */
    public function chores_can_be_sorted_by_title()
    {
        // Arrange
        // Create chores with known titles'
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Clean car'],
            )
            ->for($user)
            ->create();

        // Act
        // Navigate to index and sort by title
        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.title');

        // Assert
        // The titles are in the sorted order
        $component->assertSeeInOrder(['Clean car', 'Do dishes', 'Walk dog']);
    }

    /** @test */
    public function chores_can_be_sorted_by_frequency()
    {
        // Arrange
        // Create chores with known titles'
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes', 'frequency_id' => 3],
                ['title' => 'Walk dog', 'frequency_id' => 1],
                ['title' => 'Clean car', 'frequency_id' => 2],
            )
            ->for($user)
            ->create();

        // Act
        // Navigate to index and sort by title
        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.frequency_id');

        // Assert
        // The titles are in the sorted order
        $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
    }

    /** @test */
    public function chores_can_be_sorted_by_next_due_date()
    {
        // Arrange
        // Create chores with known titles'
        $date1  = today()->addDays(1);
        $date2  = today()->addDays(2);
        $date3  = today()->addDays(3);
        $user   = $this->testUser()['user'];
        $chores = Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes', 'frequency_id' => 1],
                ['title' => 'Walk dog', 'frequency_id' => 1],
                ['title' => 'Clean car', 'frequency_id' => 1],
            )
            ->for($user)
            ->create();
        $chores[0]->createNewInstance($date3);
        $chores[1]->createNewInstance($date1);
        $chores[2]->createNewInstance($date2);

        // Act
        // Navigate to index and sort by title
        $component = Livewire::test(Index::class)
            ->set('sort', 'chores.title') // Default is due date.
            ->call('sortBy', 'chore_instances.due_date');

        // Assert
        // The titles are in the sorted order
        $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
    }

    /** @test */
    public function chores_can_be_sorted_by_descending_title()
    {
        $this->withoutExceptionHandling();
        // Arrange
        // Create chores with known titles'
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Clean car'],
            )
            ->for($user)
            ->create();

        // Act
        // Navigate to index and sort by title
        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.title')
            ->call('sortBy', 'chores.title');

        // Assert
        // The titles are in the sorted order
        $component->assertSeeInOrder(['Walk dog', 'Do dishes', 'Clean car']);
    }
}
