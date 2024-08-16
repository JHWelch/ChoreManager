<?php

use App\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Livewire\Livewire;

test('can delete existing calendar tokens', function () {
    $user = $this->testUser()['user'];
    $token = CalendarToken::factory()
        ->for($user)
        ->create();

    Livewire::test(Index::class)
        ->call('deleteToken', ['token' => $token->id]);

    $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
});
