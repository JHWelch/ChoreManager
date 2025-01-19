<?php

use App\Enums\FrequencyType;
use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Support\Carbon;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

test('when a user specifies a date while creating a chore a chore instance is created', function () {
    $this->user();
    $date = Carbon::now()->addDays(6);

    livewire(Save::class)
        ->set('form.title', 'Do dishes')
        ->set('form.description', 'Do the dishes every night.')
        ->set('form.frequency_id', FrequencyType::daily->value)
        ->set('form.due_date', $date)
        ->call('save');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => Chore::first()->id,
        'due_date' => $date->format('Y-m-d 00:00:00'),
        'completed_date' => null,
    ]);
});

test('a chore can be created without a date and chore instance', function () {
    $this->user();

    livewire(Save::class)
        ->set('form.title', 'Do dishes')
        ->set('form.description', 'Do the dishes every night.')
        ->set('form.frequency_id', FrequencyType::daily->value)
        ->set('form.due_date', null)
        ->call('save');

    assertDatabaseCount((new ChoreInstance)->getTable(), 0);
});

test('when creating a chore with an owner the chore instance has the same owner', function () {
    $this->user();
    $date = Carbon::now()->addDays(6);
    $user = User::factory()->create();

    livewire(Save::class)
        ->set('form.title', 'Do dishes')
        ->set('form.description', 'Do the dishes every night.')
        ->set('form.frequency_id', FrequencyType::daily->value)
        ->set('form.due_date', $date)
        ->set('form.chore_user_id', $user->id)
        ->call('save');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'due_date' => $date->toDateString(),
        'user_id' => $user->id,
    ]);
});
