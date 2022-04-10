<?php

namespace Tests\Feature\CalendarTokens;

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function can_delete_existing_calendar_tokens()
    {
        $user  = $this->testUser()['user'];
        $token = CalendarToken::factory()
            ->for($user)
            ->create();

        Livewire::test(Index::class)
            ->call('deleteToken', ['token' => $token->id]);

        $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
    }
}
