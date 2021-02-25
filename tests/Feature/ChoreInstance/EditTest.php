<?php

namespace Tests\Feature\ChoreInstance;

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function when_updating_chore_instance_with_null_date_create_chore_instance()
    {
        // Arrange
        // Create a user and a chore for that user without a date
        $user  = $this->testUser();
        $chore = Chore::factory()
            ->for($user)
            ->create();
        $date = $this->faker->dateTimeBetween('+0 days', '+1 year');

        // Act
        // Navigate to the chore page and update the date
        Livewire::test(Save::class, ['chore' => $chore])
            ->set('chore_instance.due_date', $date)
            ->call('save');

        // Assert
        // Assert that a chore instance has been created.
        ray(ChoreInstance::all());
        $this->assertDatabaseHas((new ChoreInstance())->getTable(), [
            'chore_id' => $chore->id,
            'due_date' => $date,
        ]);
    }

    /** @test */
    public function when_removing_the_due_date_from_a_chore_it_will_delete_the_chore_instance()
    {
        // Arrange
        // Create a user and a chore with a chore instance
        $user  = $this->testUser();
        $chore = Chore::factory()->for($user)->hasChoreInstances(1)->create();

        // Act
        // Navigate to Chore page, remove date and save
        Livewire::test(Save::class, ['chore' => $chore])
            ->set('chore_instance.due_date', null)
            ->call('save');

        // Assert
        // There are no chore instances in the database
        $this->assertDatabaseCount((new ChoreInstance)->getTable(), 0);
    }
}