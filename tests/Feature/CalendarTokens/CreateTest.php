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
        Livewire::test(Index::class, [
            'is_team_calendar' => false,
        ])
            ->call('add');

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
        // Arrange
        // Create a user with a team.
        $userAndTeam = $this->testUser();

        // Act
        // Navigate to calendar page, set team to their team, and add.
        Livewire::test(Index::class, [
            'is_team_calendar' => true,
            'team_id'          => $userAndTeam['team']->id,
        ])
            ->call('add');

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
        $component = Livewire::test(Index::class, [
            'is_team_calendar' => true,
        ])
            ->call('add');

        // Assert
        // There is an error, nothing was created in database.
        $component->assertHasErrors(['team_id' => 'required_if']);
        $this->assertDatabaseCount((new CalendarToken)->getTable(), 0);
    }
}
