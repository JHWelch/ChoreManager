<?php

namespace Tests\Feature\ChoreInstance;

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
            'chore'          => $chore,
        ])->call('complete');

        // Assert
        // It is completed
        $chore_instance->refresh();

        $this->assertTrue($chore_instance->is_completed);
    }
}
