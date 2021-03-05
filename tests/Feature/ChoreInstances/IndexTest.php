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
        $this->testUser();

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
        $user  = $this->testUser();
        $chore = Chore::factory()->for($user)->hasChoreInstances(1)->create();

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
        $user  = $this->testUser();
        $chore = Chore::factory()->for($user)->create();

        // Act
        // Open chore instance index
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // I do not see that chore's title
        $component->assertDontSee($chore->title);
    }
}
