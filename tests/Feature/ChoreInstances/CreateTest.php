<?php

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

test('when a user specifies a date while creating a chore a chore instance is created', function () {
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
});

test('a chore can be created without a date and chore instance', function () {
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
});

test('when creating a chore with an owner the chore instance has the same owner', function () {
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
});
