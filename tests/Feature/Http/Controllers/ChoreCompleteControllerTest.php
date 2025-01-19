<?php

use App\Models\Chore;

test('chore complete redirects user to chore show with flag', function () {
    $this->user();
    $chore = Chore::factory()->create();
    $response = $this->get(route('chores.complete.index', ['chore' => $chore]));

    $response
        ->assertSessionHas('complete', true)
        ->assertRedirect(route('chores.show', ['chore' => $chore]));
});
