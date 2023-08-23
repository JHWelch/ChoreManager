<?php

namespace Tests\Feature\CalendarTokens;

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function can_create_a_calendar_token_to_display_only_their_chores(): void
    {
        $user = $this->testUser()['user'];

        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->call('addCalendarLink');

        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $user->id,
            'team_id' => null,
        ]);
    }

    /** @test */
    public function calendar_token_has_generated_uuid(): void
    {
        $this->testUser();

        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->call('addCalendarLink');

        $this->assertEquals(36, strlen(CalendarToken::first()->token));
    }

    /** @test */
    public function can_create_a_calendar_token_to_display_their_teams_chores(): void
    {
        $userAndTeam = $this->testUser();

        Livewire::test(Index::class)
            ->set('calendar_type', 'team')
            ->set('calendar_token.team_id', $userAndTeam['team']->id)
            ->call('addCalendarLink');

        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $userAndTeam['user']->id,
            'team_id' => $userAndTeam['team']->id,
        ]);
    }

    /** @test */
    public function when_team_calendar_is_selected_user_must_pick_team(): void
    {
        $this->testUser();

        $component = Livewire::test(Index::class)
            ->set('calendar_type', 'team')
            ->call('addCalendarLink');

        $component->assertHasErrors(['calendar_token.team_id' => 'required_if']);
        $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
    }

    /** @test */
    public function if_user_calendar_selected_will_not_have_team_even_if_specified(): void
    {
        $userAndTeam = $this->testUser();

        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->set('calendar_token.team_id', $userAndTeam['team']->id)
            ->call('addCalendarLink');

        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $userAndTeam['user']->id,
            'team_id' => null,
        ]);
    }

    /** @test */
    public function calendars_can_be_created_with_names(): void
    {
        $user = $this->testUser()['user'];

        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->set('calendar_token.name', 'Chore Calendar')
            ->call('addCalendarLink');

        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'name' => 'Chore Calendar',
            'user_id' => $user->id,
            'team_id' => null,
        ]);
    }
}
