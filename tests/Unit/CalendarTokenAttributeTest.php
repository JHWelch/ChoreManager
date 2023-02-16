<?php

namespace Tests\Unit;

use App\Models\CalendarToken;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CalendarTokenAttributeTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function calendar_token_is_team_calendar(): void
    {
        $calendar_token = CalendarToken::make([
            'user_id' => 1,
            'team_id' => 1,
        ]);

        $this->assertEquals(true, $calendar_token->is_team_calendar);
        $this->assertEquals(false, $calendar_token->is_user_calendar);
    }

    /** @test */
    public function is_team_calendar_attribute_false(): void
    {
        $calendar_token = CalendarToken::make([
            'user_id' => 1,
            'team_id' => null,
        ]);

        $this->assertEquals(false, $calendar_token->is_team_calendar);
        $this->assertEquals(true, $calendar_token->is_user_calendar);
    }

    /** @test */
    public function display_name_with_defined_name_is_name(): void
    {
        $user           = User::factory()->create();
        $calendar_token = CalendarToken::make([
            'user_id' => $user->id,
            'name'    => 'Special Calendar',
        ]);

        $this->assertEquals('Special Calendar', $calendar_token->display_name);
    }

    /** @test */
    public function user_calendar_without_defined_name_named_after_user(): void
    {
        $user = User::factory([
            'name' => 'Steve Smith',
        ])->create();

        $calendar_token = CalendarToken::make([
            'user_id' => $user->id,
        ]);

        $this->assertEquals('Steve Smith\'s Chores', $calendar_token->display_name);
    }

    /** @test */
    public function team_calendar_without_defined_name_named_after_team(): void
    {
        $team = Team::factory([
            'name' => 'Smith Family',
        ])->create();
        $calendar_token = CalendarToken::make([
            'team_id' => $team->id,
        ]);

        $this->assertEquals('Smith Family Chores', $calendar_token->display_name);
    }
}
