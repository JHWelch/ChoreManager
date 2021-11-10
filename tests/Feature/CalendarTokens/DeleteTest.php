<?php

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Livewire\Livewire;

test('can delete existing calendar tokens', function () {
    // Arrange
    // Create a user with Calendar token
    $user  = $this->testUser()['user'];
    $token = CalendarToken::factory()
        ->for($user)
        ->create();

    // Act
    // Navigate to the livewire page and delete the token
    Livewire::test(Index::class)
        ->call('deleteToken', ['token' => $token->id]);

    // Assert
    // Token is deleted.
    $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
});
