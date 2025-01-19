<?php

use App\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Livewire\livewire;

it('can delete existing calendar tokens', function () {
    $user = $this->user()['user'];
    $token = CalendarToken::factory()
        ->for($user)
        ->create();

    livewire(Index::class)
        ->call('deleteToken', ['token' => $token->id]);

    assertDatabaseCount((new CalendarToken)->getTable(), 0);
});
