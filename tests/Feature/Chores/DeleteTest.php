<?php

namespace Tests\Feature\Chores;

use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function user_can_delete_chore_from_show()
    {
        // Arrange
        // Create a chore
        $chore = Chore::factory()->for($this->testUser()['user'])->create();

        // Act
        // Navigate to chore and delete it
        $component = Livewire::test(Show::class, ['chore' => $chore])
            ->call('delete');

        // Assert
        // Chore is no longer in database
        $this->assertDatabaseCount((new Chore)->getTable(), 0);
        $component->assertRedirect('/');
    }
}
