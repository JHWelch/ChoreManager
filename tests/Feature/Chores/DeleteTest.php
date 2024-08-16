<?php

use App\Livewire\Chores\Show;
use App\Models\Chore;

test('user can delete chore from show', function () {
    $chore = Chore::factory()->for($this->testUser()['user'])->create();

    $component = Livewire::test(Show::class, ['chore' => $chore])
        ->call('delete');

    $this->assertDatabaseCount((new Chore)->getTable(), 0);
    $component->assertRedirect('/');
});
