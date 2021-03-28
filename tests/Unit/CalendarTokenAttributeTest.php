<?php

namespace Tests\Unit;

use App\Models\CalendarToken;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTokenAttributeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function calendar_token_is_team_calendar()
    {
        // Arrange
        // Make team calendar token.
        $calendar_token = CalendarToken::make([
            'user_id' => 1,
            'team_id' => 1,
        ]);

        // Assert
        // is team token
        $this->assertEquals(true, $calendar_token->is_team_calendar);
        $this->assertEquals(false, $calendar_token->is_user_calendar);
    }

    /** @test */
    public function is_team_calendar_attribute_false()
    {
        // Arrange
        // Make team calendar token.
        $calendar_token = CalendarToken::make([
            'user_id' => 1,
            'team_id' => null,
        ]);

        // Assert
        // is team token
        $this->assertEquals(false, $calendar_token->is_team_calendar);
        $this->assertEquals(true, $calendar_token->is_user_calendar);
    }

    /** @test */
    public function display_name_with_defined_name_is_name()
    {
        // Arrange
        // Make calendar token with name
        $user           = User::factory()->create();
        $calendar_token = CalendarToken::make([
            'user_id' => $user->id,
            'name'    => 'Special Calendar',
        ]);

        // Assert
        // Calendar display name is defined name
        $this->assertEquals('Special Calendar', $calendar_token->display_name);
    }

    /** @test */
    public function user_calendar_without_defined_name_named_after_user()
    {
        // Arrange
        // Make user calendar token without name, but with user name.
        $user = User::factory([
            'name' => 'Steve Smith',
        ])->create();

        $calendar_token = CalendarToken::make([
            'user_id' => $user->id,
        ]);

        // Assert
        // Calendar display name is defined name
        $this->assertEquals('Steve Smith\'s Chores', $calendar_token->display_name);
    }

    /** @test */
    public function team_calendar_without_defined_name_named_after_team()
    {
        // Arrange
        // Make team calendar token without name, but with team name
        $team = Team::factory([
            'name' => 'Smith Family',
        ])->create();

        $calendar_token = CalendarToken::make([
            'team_id' => $team->id,
        ]);

        // Assert
        // Calendar display name is defined name
        $this->assertEquals('Smith Family Chores', $calendar_token->display_name);
    }
}
