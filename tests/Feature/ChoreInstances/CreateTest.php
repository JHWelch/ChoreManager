<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_a_user_specifies_a_date_while_creating_a_chore_a_chore_instance_is_created()
    {
        // Arrange
        // Create a user and a date
        $this->testUser();
        $date = Carbon::now()->addDays(6);

        // Act
        // Navigate to Chore Page and create a new chore, specifying date
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', $date)
            ->call('save');

        // Assert
        // Chore instance is created.
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'       => Chore::first()->id,
            'due_date'       => $date->format('Y-m-d 00:00:00'),
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function a_chore_can_be_created_without_a_date_and_chore_instance()
    {
        // Arrange
        // Create a user
        $this->testUser();

        // Act
        // Navigate to Chore page and create a new chore, without a date.
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', null)
            ->call('save');

        // Assert
        // Chore is created, but no chore instance is created
        $this->assertDatabaseCount((new ChoreInstance)->getTable(), 0);
    }

    /** @test */
    public function when_creating_a_chore_with_an_owner_the_chore_instance_has_the_same_owner()
    {
        // Arrange
        // Create a user
        $this->testUser();
        $date = Carbon::now()->addDays(6);
        $user = User::factory()->create();

        // Act
        // Create chore with an owner and a due date
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', $date)
            ->set('chore.user_id', $user->id)
            ->call('save');

        // Assert
        // Chore instance created with that chore and owner
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'due_date' => $date->toDateString(),
            'user_id'  => $user->id,
        ]);
    }
}
