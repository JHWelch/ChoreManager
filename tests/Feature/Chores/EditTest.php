<?php

use App\Enums\FrequencyType;
use App\Livewire\Chores\Save;
use App\Models\Chore;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

test('chore edit page can be reached', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->create();

    $response = $this->get(route('chores.edit', ['chore' => $chore->id]));

    $response->assertOk();
});

test('user cannot edit chores for another user', function () {
    $this->user();
    $chore = Chore::factory()->forUser()->create();

    $response = $this->get(route('chores.edit', ['chore' => $chore]));

    $response->assertForbidden();
});

test('existing chore screen shows its information', function () {
    $user = $this->user()['user'];
    $chore = Chore::create([
        'user_id' => $user->id,
        'title' => 'Do dishes',
        'description' => 'Do dishes every night.',
        'frequency_id' => FrequencyType::daily,
    ]);

    $component = livewire(Save::class, ['chore' => $chore]);

    $component
        ->assertSet('form.title', 'Do dishes')
        ->assertSet('form.description', 'Do dishes every night.')
        ->assertSet('form.frequency_id', FrequencyType::daily);
});

test('a chore can be updated after it is created', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->create();

    livewire(Save::class, ['chore' => $chore])
        ->set('form.title', 'Do dishes')
        ->set('form.description', 'Do the dishes every night.')
        ->set('form.frequency_id', FrequencyType::daily->value)
        ->call('save');

    assertDatabaseHas((new Chore)->getTable(), [
        'title' => 'Do dishes',
        'description' => 'Do the dishes every night.',
        'frequency_id' => FrequencyType::daily,
        'user_id' => $user->id,
    ]);
});
