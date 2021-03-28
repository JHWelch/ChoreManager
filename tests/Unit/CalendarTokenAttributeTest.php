<?php

namespace Tests\Unit;

use App\Models\CalendarToken;
use Tests\TestCase;

class CalendarTokenAttributeTest extends TestCase
{
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
}
