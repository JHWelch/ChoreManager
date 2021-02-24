<?php

namespace Tests\Feature;

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chore_edit_page_can_be_reached()
    {
        // Arrange
        // Create a test user
        $this->testUser();

        // Act
        // Navigate to Chore Create page
        $response = $this->get(route('chores.create'));

        // Assert
        // A page is successfully returned
        $response->assertStatus(200);
    }

    /** @test */
    public function can_create_chore()
    {
        // Arrange
        $user = $this->testUser();

        // Act
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->call('save');

        // Assert
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => 'Do dishes',
            'description'  => 'Do the dishes every night.',
            'frequency_id' => 1,
            'user_id'      => $user->id,
        ]);
    }
}
