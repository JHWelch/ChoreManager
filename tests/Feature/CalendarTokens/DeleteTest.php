<?php

namespace Tests\Feature\CalendarTokens;

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_existing_calendar_tokens()
    {
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
    }
}
