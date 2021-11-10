<?php

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Index;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

test('a user can navigate to chores index', function () {
    // Arrange
    // Create and act as a user
    $this->testUser();

    // Act
    // Navigate to the Chores index page
    $response = $this->get(route('chores.index'));

    // Assert
    // Assert that it is returns a 200
    $response->assertStatus(200);
});

test('chores display on index page', function () {
    // Arrange
    // create a user and some chores
    $user = $this->testUser()['user'];
    Chore::factory()
        ->count(3)
        ->state(new Sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Do laundry'],
        ))
        ->for($user)
        ->create();

    // Act
    // Navigate to the chores index page
    $component = Livewire::test(Index::class);

    // Assert
    // Assert we can see all the chore titles
    $component->assertSee('Do dishes')
        ->assertSee('Walk dog')
        ->assertSee('Do laundry');
});

test('chores can be sorted by title', function () {
    // Arrange
    // Create chores with known titles'
    $user = $this->testUser()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Clean car'],
        )
        ->for($user)
        ->create();

    // Act
    // Navigate to index and sort by title
    $component = Livewire::test(Index::class)
        ->call('sortBy', 'chores.title');

    // Assert
    // The titles are in the sorted order
    $component->assertSeeInOrder(['Clean car', 'Do dishes', 'Walk dog']);
});

test('chores can be sorted by frequency', function () {
    // Arrange
    // Create chores with known titles'
    $user = $this->testUser()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes', 'frequency_id' => Frequency::MONTHLY],
            ['title' => 'Walk dog', 'frequency_id'  => Frequency::DAILY],
            ['title' => 'Clean car', 'frequency_id' => Frequency::WEEKLY],
        )
        ->for($user)
        ->create();

    // Act
    // Navigate to index and sort by title
    $component = Livewire::test(Index::class)
        ->call('sortBy', 'chores.frequency_id');

    // Assert
    // The titles are in the sorted order
    $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
});

test('chores can be sorted by next due date', function () {
    // Arrange
    // Create chores with known titles'
    $date1  = today()->addDays(1);
    $date2  = today()->addDays(2);
    $date3  = today()->addDays(3);
    $user   = $this->testUser()['user'];
    $chores = Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes', 'frequency_id' => Frequency::DAILY],
            ['title' => 'Walk dog', 'frequency_id'  => Frequency::DAILY],
            ['title' => 'Clean car', 'frequency_id' => Frequency::DAILY],
        )
        ->for($user)
        ->create();
    ChoreInstance::factory(['due_date' => $date3])->for($user)->for($chores[0])->create();
    ChoreInstance::factory(['due_date' => $date1])->for($user)->for($chores[1])->create();
    ChoreInstance::factory(['due_date' => $date2])->for($user)->for($chores[2])->create();

    // Act
    // Navigate to index and sort by title
    $component = Livewire::test(Index::class)
        ->set('sort', 'chores.title') // Default is due date.
        ->call('sortBy', 'chore_instances.due_date');

    // Assert
    // The titles are in the sorted order
    $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
});

test('chores can be sorted by descending title', function () {
    $this->withoutExceptionHandling();
    // Arrange
    // Create chores with known titles'
    $user = $this->testUser()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Clean car'],
        )
        ->for($user)
        ->create();

    // Act
    // Navigate to index and sort by title
    $component = Livewire::test(Index::class)
        ->call('sortBy', 'chores.title')
        ->call('sortBy', 'chores.title');

    // Assert
    // The titles are in the sorted order
    $component->assertSeeInOrder(['Walk dog', 'Do dishes', 'Clean car']);
});
