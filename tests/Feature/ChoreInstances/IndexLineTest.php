<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexLineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_complete_a_chore_instance()
    {
        // Arrange
        // Create user and a chore Instance
        $user           = $this->testUser();
        $chore          = Chore::factory()->for($user)->hasChoreInstances()->create();
        $chore_instance = $chore->nextChoreInstance;

        // Act
        // Open chore instance on line and complete it
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        // Assert
        // It is completed
        $chore_instance->refresh();

        $this->assertTrue($chore_instance->is_completed);
    }

    /** @test */
    public function when_a_chore_instance_is_completed_a_new_one_is_created_daily()
    {
        // Arrange
        // Create a chore with an instance
        $now   = today();
        $user  = $this->testUser();
        $chore = Chore::factory(
            ['frequency_id' => 1]
        )
            ->for($user)
            ->has(ChoreInstance::factory(['due_date' => $now]))
            ->create();

        // Act
        // Navigate to line and click complete
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        // Assert
        // Check to see if a chore was created the next day.
        $chore->refresh();

        $this->assertEquals(
            $now->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }
}
