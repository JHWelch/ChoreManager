<?php

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;


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
