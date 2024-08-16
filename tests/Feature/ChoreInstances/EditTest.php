<?php

use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\WithFaker::class);

test('when updating chore instance with null date create chore instance', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()
        ->for($user)
        ->create();
    $date = $this->faker->dateTimeBetween('+0 days', '+1 year');

    Livewire::test(Save::class, ['chore' => $chore])
        ->set('form.due_date', Carbon::parse($date))
        ->call('save');

    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'due_date' => $date->format('Y-m-d 00:00:00'),
    ]);
});

test('when removing the due date from a chore it will delete the chore instance', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance()->create();

    $livewire = Livewire::test(Save::class, ['chore' => $chore])
        ->set('form.due_date', null)
        ->assertSet('form.due_date', null)
        ->call('save');

    $this->assertDatabaseEmpty((new ChoreInstance)->getTable());
});

test('when opening chore edit due date is populated', function () {
    $this->testUser();
    $date = today()->addDays(5);
    $chore = Chore::factory()->for($this->user)->withFirstInstance($date)->create();

    $component = Livewire::test(Save::class, ['chore' => $chore]);

    $component->assertSet('form.due_date', $date->startOfDay()->format('Y-m-d'));
});

test('after completing a chore you can see next chore instance date', function () {
    $this->testUser();
    $date = Carbon::now();
    $chore = Chore::factory()
        ->withFirstInstance($date)
        ->for($this->user)
        ->daily()
        ->create();
    $chore->complete();
    $chore->refresh();

    $component = Livewire::test(Save::class, ['chore' => $chore]);

    $component->assertSet('form.due_date', $date->addDay()->startOfDay()->format('Y-m-d'));
});

test('a chore instance can be assigned to a new user', function () {
    $this->testUser();
    $user = User::factory()->hasAttached($this->team)->create();
    $chore = Chore::factory()
        ->for($user)
        ->for($this->team)
        ->withFirstInstance()
        ->create();

    Livewire::test(Save::class, ['chore' => $chore])
        ->set('form.instance_user_id', $user->id)
        ->call('save');

    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'user_id' => $user->id,
    ]);
});
