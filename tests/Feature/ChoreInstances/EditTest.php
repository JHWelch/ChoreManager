<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;
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
        $this->assertDatabaseHas((new ChoreInstance())->getTable(), [
            'chore_id' => $chore->id,
            'due_date' => $date->format('Y-m-d 00:00:00'),
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

    /** @test */
    public function when_opening_chore_edit_due_date_is_populated()
    {
        // Arrange
        // Create a chore with a chore instance
        $this->testUser();
        $date  = today()->addDays(5);
        $chore = Chore::factory()->has($chore_instance = ChoreInstance::factory([
            'due_date' => $date,
        ]))->create();

        // Act
        // navigate to edit page
        $component = Livewire::test(Save::class, ['chore' => $chore]);

        // Assert
        // The due date is set.
        $component->assertSet('chore_instance.due_date', $date->startOfDay());
    }

    /** @test */
    public function after_completing_a_chore_you_can_see_next_chore_instance_date()
    {
        // Arrange
        // Create a chore with a chore instance
        $this->testUser();
        $date  = Carbon::now();
        $chore = Chore::factory()->has(ChoreInstance::factory([
            'due_date' => $date,
        ]))->create([
            'frequency_id' => 1,
        ]);

        $chore->nextChoreInstance->complete();
        $chore->refresh();

        // Act
        // navigate to edit page
        $component = Livewire::test(Save::class, ['chore' => $chore]);

        // Assert
        // The due date is set.
        $component->assertSet('chore_instance.due_date', $date->addDay()->startOfDay());
    }
}
