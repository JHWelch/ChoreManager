<?php

use App\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Models\Chore;

use function Pest\Livewire\livewire;

test('chore instance index page can be reached', function () {
    $this->user()['user'];

    $response = $this->get(route('chore_instances.index'));

    $response->assertOk();
});

test('chores with chore instances show on index', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore->title);
});

test('chores without chore instances do not show on index', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertDontSee($chore->title);
});

test('when there are no chore instances see empty state', function () {
    $this->user();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee('All done for today');
});

test('future chores do not show by default', function () {
    $user = $this->user()['user'];
    $chore1 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today())
        ->create();
    $chore2 = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()->addDays(4))
        ->create();

    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore1->title);
    $component->assertDontSee($chore2->title);
});

test('user can show future chores', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
            ->addDays(4))->create();

    $component = livewire(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');

    $component->assertSeeInOrder(['Future', $chore->title]);
});

test('show future chores is remembered when revisiting page', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance(today()
            ->addDays(4))->create();

    livewire(ChoreInstancesIndex::class)
        ->call('toggleShowFutureChores');
    $component = livewire(ChoreInstancesIndex::class);

    $component->assertSee($chore->title);
});

test('chore instances are split into groups based on date', function () {
    $this->user();
    Chore::factory(['title' => 'walk dog'])
        ->for($this->user)
        ->withFirstInstance(today()->addDay(), $this->user)
        ->create();
    Chore::factory(['title' => 'do laundry'])
        ->for($this->user)
        ->withFirstInstance(today()->subDay(), $this->user)
        ->create();
    Chore::factory(['title' => 'clean dishes'])
        ->for($this->user)
        ->withFirstInstance(today(), $this->user)
        ->create();

    livewire(ChoreInstancesIndex::class)
        ->assertSeeInOrder([
            'Past due',
            'do laundry',
            'Today',
            'clean dishes',
        ]);
});
