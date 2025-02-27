<?php

use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('when updating chore instance with null date create chore instance', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->create();
    $date = $this->faker->dateTimeBetween('+0 days', '+1 year');

    livewire(Save::class, ['chore' => $chore])
        ->set('form.due_date', Carbon::parse($date))
        ->call('save');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'due_date' => $date->format('Y-m-d 00:00:00'),
    ]);
});

test('when removing the due date from a chore it will delete the chore instance', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance()->create();

    livewire(Save::class, ['chore' => $chore])
        ->set('form.due_date', null)
        ->assertSet('form.due_date', null)
        ->call('save');

    assertDatabaseEmpty((new ChoreInstance)->getTable());
});

test('when opening chore edit due date is populated', function () {
    $this->user();
    $date = today()->addDays(5);
    $chore = Chore::factory()->for($this->user)->withFirstInstance($date)->create();

    $component = livewire(Save::class, ['chore' => $chore]);

    $component->assertSet('form.due_date', $date->startOfDay()->format('Y-m-d'));
});

test('after completing a chore you can see next chore instance date', function () {
    $this->user();
    $date = Carbon::now();
    $chore = Chore::factory()
        ->withFirstInstance($date)
        ->for($this->user)
        ->daily()
        ->create();
    $chore->complete();
    $chore->refresh();

    $component = livewire(Save::class, ['chore' => $chore]);

    $component->assertSet('form.due_date', $date->addDay()->startOfDay()->format('Y-m-d'));
});

test('a chore instance can be assigned to a new user', function () {
    $this->user();
    $user = User::factory()->hasAttached($this->team)->create();
    $chore = Chore::factory()
        ->for($user)
        ->for($this->team)
        ->withFirstInstance()
        ->create();

    livewire(Save::class, ['chore' => $chore])
        ->set('form.instance_user_id', $user->id)
        ->call('save');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'user_id' => $user->id,
    ]);
});
