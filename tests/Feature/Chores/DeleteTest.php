<?php

use App\Livewire\Chores\Show;
use App\Models\Chore;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Livewire\livewire;

test('user can delete chore from show', function () {
    $chore = Chore::factory()->for($this->testUser()['user'])->create();

    $component = livewire(Show::class, ['chore' => $chore])
        ->call('delete');

    assertDatabaseCount((new Chore)->getTable(), 0);
    $component->assertRedirect('/');
});
