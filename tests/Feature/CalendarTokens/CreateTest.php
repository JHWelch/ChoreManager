<?php

namespace Tests\Feature\CalendarTokens;

use App\Http\Livewire\CalendarTokens\Index;
use App\Models\CalendarToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_calendar_token_to_display_only_their_chores()
    {
        // Arrange
        // Create a user
        $user = $this->testUser()['user'];

        // Act
        // Navigate to calendar page, set to user calendar and add.
        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->call('addCalendarLink');

        // Assert
        // Token exists.
        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $user->id,
            'team_id' => null,
        ]);
    }

    /** @test */
    public function can_create_a_calendar_token_to_display_their_teams_chores()
    {
        $this->withoutExceptionHandling();
        // Arrange
        // Create a user with a team.
        $userAndTeam = $this->testUser();

        // Act
        // Navigate to calendar page, set team to their team, and add.
        Livewire::test(Index::class)
            ->set('calendar_type', 'team')
            ->set('calendar_token.team_id', $userAndTeam['team']->id)
            ->call('addCalendarLink');

        // Assert
        // Token has been created with the user and team.
        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $userAndTeam['user']->id,
            'team_id' => $userAndTeam['team']->id,
        ]);
    }

    /** @test */
    public function when_team_calendar_is_selected_user_must_pick_team()
    {
        // Arrange
        // Create a user with a team
        $this->testUser();

        // Act
        // Navigate to calendar page, set team calendar and then add
        $component = Livewire::test(Index::class)
            ->set('calendar_type', 'team')
            ->call('addCalendarLink');

        // Assert
        // There is an error, nothing was created in database.
        $component->assertHasErrors(['calendar_token.team_id' => 'required_if']);
        $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
    }

    /** @test */
    public function if_user_calendar_selected_will_not_have_team_even_if_specified()
    {
        // Arrange
        // Create a user with a team.
        $userAndTeam = $this->testUser();

        // Act
        // Navigate to calendar page, set team to their team, but calendar type to user.
        Livewire::test(Index::class)
            ->set('calendar_type', 'user')
            ->set('calendar_token.team_id', $userAndTeam['team']->id)
            ->call('addCalendarLink');

        // Assert
        // Token has been created with the user, but not the team
        $this->assertDatabaseHas((new CalendarToken)->getTable(), [
            'user_id' => $userAndTeam['user']->id,
            'team_id' => null,
        ]);
    }
}
