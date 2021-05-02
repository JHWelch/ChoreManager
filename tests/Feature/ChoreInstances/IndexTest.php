<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Models\Chore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chore_instance_index_page_can_be_reached()
    {
        // Arrange
        // Create a test user
        $this->testUser()['user'];

        // Act
        // Navigate to Chore instance Index page
        $response = $this->get(route('chore_instances.index'));

        // Assert
        // A page is successfully returned
        $response->assertStatus(200);
    }

    /** @test */
    public function chores_with_chore_instances_show_on_index()
    {
        // Arrange
        // Create a chore with a chore instance
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

        // Act
        // Open chore instance index
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // Chore and instance date is show on page
        $component->assertSee($chore->title);
    }

    /** @test */
    public function chores_without_chore_instances_do_not_show_on_index()
    {
        // Arrange
        // Createa a chore without chore instance
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        // Act
        // Open chore instance index
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // I do not see that chore's title
        $component->assertDontSee($chore->title);
    }

    /** @test */
    public function when_there_are_no_chore_instances_see_empty_state()
    {
        // Arrange
        // create user
        $this->testUser();

        // Act
        // Go to Index page
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // See empty state
        $component->assertSee('No chores here! Good job.');
    }

    /** @test */
    public function future_chores_do_not_show_by_default()
    {
        // Arrange
        // Create two chores, one due today, one in future
        $user   = $this->testUser()['user'];
        $chore1 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today())
            ->create();
        $chore2 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        // Act
        // View Index page
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // Can see the chore due today, but not the one in the future.
        $component->assertSee($chore1->title);
        $component->assertDontSee($chore2->title);
    }

    /** @test */
    public function user_can_show_future_chores()
    {
        // Arrange
        // Create two chores, one due today, one in future
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        // Act
        // View Index page
        $component = Livewire::test(ChoreInstancesIndex::class)
            ->call('toggleShowFutureChores');

        // Assert
        // Can see the chore due today, but not the one in the future.
        $component->assertSee($chore->title);
    }
}
