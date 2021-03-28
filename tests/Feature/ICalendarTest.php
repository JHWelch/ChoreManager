<?php

namespace Tests\Feature;

use App\Models\CalendarToken;
use App\Models\Chore;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ICalendarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_calendar_tokens_return_users_next_chore_instances()
    {
        $this->withoutExceptionHandling();
        // Arrange
        // Create user with chores and calendar token
        $user           = $this->testUser()['user'];
        CalendarToken::create([
            'user_id' => $user->id,
            'token'   => 'fake_uuid',
        ]);
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Walk the dog'],
                ['title' => 'Clean the dishes'],
                ['title' => 'Do the laundry'],
            )
            ->for($user)
            ->withFirstInstance()
            ->create();

        ray(Chore::all());

        // Act
        // Call ICalendar route with token key
        $response = $this->get(route('icalendar.index', ['token' => 'fake_uuid']));

        // Assert
        // Calendar output has names
        // NOTE: Not testing the ICalendar library, so we are not overyly worried about structure.
        $response->assertStatus(200);
        $response->assertSee('BEGIN:VCALENDAR');
        $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
    }

    /** @test */
    public function team_calendar_tokens_return_team_next_chore_instances()
    {
        $this->withoutExceptionHandling();
        // Arrange
        // Create user with chores and calendar token
        $actingAsUser   = $this->testUser();
        CalendarToken::create([
            'user_id' => $actingAsUser['user']->id,
            'team_id' => $actingAsUser['team']->id,
            'token'   => 'fake_uuid',
        ]);

        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Clean the dishes'],
                ['title' => 'Do the laundry'],
            )
            ->for($actingAsUser['user'])
            ->for($actingAsUser['team'])
            ->withFirstInstance()
            ->create();

        Chore::factory([
            'title' => 'Walk the dog.',
        ])
            ->for(User::factory()->hasAttached($actingAsUser['team']))
            ->for($actingAsUser['team'])
            ->withFirstInstance()
            ->create();

        // Act
        // Call ICalendar route with token key
        $response = $this->get(route('icalendar.index', ['token' => 'fake_uuid']));

        // Assert
        // Calendar output has names
        // NOTE: Not testing the ICalendar library, so we are not worried about structure.
        $response->assertStatus(200);
        $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
    }
}
