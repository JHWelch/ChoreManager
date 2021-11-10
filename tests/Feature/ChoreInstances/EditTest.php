<?php

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;

uses(WithFaker::class);

test('when updating chore instance with null date create chore instance', function () {
    // Arrange
    // Create a user and a chore for that user without a date
    $user  = $this->testUser()['user'];
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
});

test('when removing the due date from a chore it will delete the chore instance', function () {
    // Arrange
    // Create a user and a chore with a chore instance
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance()->create();

    // Act
    // Navigate to Chore page, remove date and save
    Livewire::test(Save::class, ['chore' => $chore])
        ->set('chore_instance.due_date', null)
        ->call('save');

    // Assert
    // There are no chore instances in the database
    $this->assertDatabaseCount((new ChoreInstance)->getTable(), 0);
});

test('when opening chore edit due date is populated', function () {
    // Arrange
    // Create a chore with a chore instance
    $this->testUser();
    $date  = today()->addDays(5);
    $chore = Chore::factory()->withFirstInstance($date)->create();

    // Act
    // navigate to edit page
    $component = Livewire::test(Save::class, ['chore' => $chore]);

    // Assert
    // The due date is set.
    $component->assertSet('chore_instance.due_date', $date->startOfDay());
});

test('after completing a chore you can see next chore instance date', function () {
    // Arrange
    // Create a chore with a chore instance
    $this->testUser();
    $date  = Carbon::now();
    $chore = Chore::factory()->withFirstInstance($date)->create([
        'frequency_id' => Frequency::DAILY,
    ]);

    $chore->complete();
    $chore->refresh();

    // Act
    // navigate to edit page
    $component = Livewire::test(Save::class, ['chore' => $chore]);

    // Assert
    // The due date is set.
    $component->assertSet('chore_instance.due_date', $date->addDay()->startOfDay());
});

test('a chore instance can be assigned to a new user', function () {
    // Arrange
    // Create a user with a chore assigned to a user
    $this->testUser();
    $user  = User::factory()->create();
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance()
        ->create();

    // Act
    // Navigate to page and update chore instance owner
    Livewire::test(Save::class, ['chore' => $chore])
        ->set('chore_instance.user_id', $user->id)
        ->call('save');

    // Assert
    // Chore instance exists with the chore and new user
    $this->assertDatabaseHas((new ChoreInstance())->getTable(), [
        'chore_id' => $chore->id,
        'user_id'  => $user->id,
    ]);
});
