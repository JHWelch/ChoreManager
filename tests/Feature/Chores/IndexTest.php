<?php

use App\Enums\FrequencyType;
use App\Livewire\Chores\Index;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Factories\Sequence;

use function Pest\Livewire\livewire;

test('a user can navigate to chores index', function () {
    $this->user();

    $response = $this->get(route('chores.index'));

    $response->assertOk();
});

test('chores display on index page', function () {
    $user = $this->user()['user'];
    Chore::factory()
        ->count(3)
        ->state(new Sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Do laundry'],
        ))
        ->for($user)
        ->create();

    $component = livewire(Index::class);

    // Assert
    // Assert we can see all the chore titles
    $component->assertSee('Do dishes')
        ->assertSee('Walk dog')
        ->assertSee('Do laundry');
});

test('chores can be sorted by title', function () {
    $user = $this->user()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Clean car'],
        )
        ->for($user)
        ->create();

    $component = livewire(Index::class)
        ->call('sortBy', 'chores.title');

    $component->assertSeeInOrder(['Clean car', 'Do dishes', 'Walk dog']);
});

test('chores can be sorted by frequency', function () {
    $user = $this->user()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes', 'frequency_id' => FrequencyType::monthly],
            ['title' => 'Walk dog', 'frequency_id' => FrequencyType::daily],
            ['title' => 'Clean car', 'frequency_id' => FrequencyType::weekly],
        )
        ->for($user)
        ->create();

    $component = livewire(Index::class)
        ->call('sortBy', 'chores.frequency_id');

    $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
});

test('chores can be sorted by next due date', function () {
    $date1 = today()->addDays(1);
    $date2 = today()->addDays(2);
    $date3 = today()->addDays(3);
    $user = $this->user()['user'];
    $chores = Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes', 'frequency_id' => FrequencyType::daily],
            ['title' => 'Walk dog', 'frequency_id' => FrequencyType::daily],
            ['title' => 'Clean car', 'frequency_id' => FrequencyType::daily],
        )
        ->for($user)
        ->create();
    ChoreInstance::factory(['due_date' => $date3])->for($user)->for($chores[0])->create();
    ChoreInstance::factory(['due_date' => $date1])->for($user)->for($chores[1])->create();
    ChoreInstance::factory(['due_date' => $date2])->for($user)->for($chores[2])->create();

    $component = livewire(Index::class)
        ->set('sort', 'chores.title') // Default is due date.
        ->call('sortBy', 'chore_instances.due_date');

    $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
});

test('chores can be sorted by descending title', function () {
    $user = $this->user()['user'];
    Chore::factory()
        ->count(3)
        ->sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Clean car'],
        )
        ->for($user)
        ->create();

    $component = livewire(Index::class)
        ->call('sortBy', 'chores.title')
        ->call('sortBy', 'chores.title');

    $component->assertSeeInOrder(['Walk dog', 'Do dishes', 'Clean car']);
});
