<?php

namespace Tests\Feature\CalendarTokens;

use App\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function can_delete_existing_calendar_tokens(): void
    {
        $user = $this->testUser()['user'];
        $token = CalendarToken::factory()
            ->for($user)
            ->create();

        Livewire::test(Index::class)
            ->call('deleteToken', ['token' => $token->id]);

        $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
    }
}
