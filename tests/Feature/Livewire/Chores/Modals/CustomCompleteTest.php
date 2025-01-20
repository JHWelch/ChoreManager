<?php

use App\Livewire\Chores\Modals\CustomComplete;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('can complete chore for another another team user', function () {
    $this->user();
    $other_user = User::factory()->hasAttached($this->team)->create();
    $chore = Chore::factory()
        ->for($this->team)
        ->for($other_user)
        ->withFirstInstance()
        ->create();

    livewire(CustomComplete::class, [
        'chore' => $chore,
    ])
        ->set('user_id', $other_user->id)
        ->call('customComplete')
        ->assertDispatched('choreCompleted');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'completed_date' => today(),
        'completed_by_id' => $other_user->id,
    ]);
});

it('can complete chore on a past date', function () {
    $user = $this->user()['user'];
    $date = today()->subDays(2);
    $chore = Chore::factory()
        ->for($user)
        ->withFirstInstance()
        ->create();

    livewire(CustomComplete::class, [
        'chore' => $chore,
    ])
        ->set('completed_date', $date)
        ->call('customComplete')
        ->assertDispatched('choreCompleted');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'chore_id' => $chore->id,
        'completed_date' => $date,
        'completed_by_id' => $user->id,
    ]);
});
