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
    public function can_create_chore()
    {
        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act
        Livewire::test(Save::class)
            ->set('title', 'Do dishes')
            ->set('description', 'Do the dishes every night.')
            ->set('frequency', 1)
            ->call('save');

        // Assert
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'       => 'Do dishes',
            'description' => 'Do the dishes every night.',
            'frequency'   => 1,
            'user_id'     => $user->id,
        ]);
    }
}
