<?php

namespace Tests\Feature;

use App\Models\CalendarToken;
use App\Models\Chore;
use App\Models\User;
use Tests\TestCase;

class ICalendarTest extends TestCase
{
    /** @test */
    public function user_calendar_tokens_return_users_next_chore_instances(): void
    {
        $this->testUser();
        CalendarToken::create([
            'user_id' => $this->user->id,
            'token'   => 'fake_uuid',
        ]);
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Walk the dog'],
                ['title' => 'Clean the dishes'],
                ['title' => 'Do the laundry'],
            )
            ->for($this->user)
            ->withFirstInstance()
            ->create();

        $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

        // NOTE: Not testing the ICalendar library, so we are not overyly worried about structure.
        $response->assertOk();
        $response->assertSee('BEGIN:VCALENDAR');
        $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
    }

    /** @test */
    public function team_calendar_tokens_return_team_next_chore_instances(): void
    {
        $this->testUser();
        CalendarToken::create([
            'user_id' => $this->user->id,
            'team_id' => $this->team->id,
            'token'   => 'fake_uuid',
        ]);
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Clean the dishes'],
                ['title' => 'Do the laundry'],
            )
            ->for($this->user)
            ->for($this->team)
            ->withFirstInstance()
            ->create();
        Chore::factory([
            'title' => 'Walk the dog.',
        ])
            ->for(User::factory()->hasAttached($this->team))
            ->for($this->team)
            ->withFirstInstance()
            ->create();

        $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

        $response->assertOk();
        $response->assertSee('Walk the dog', 'Clean the dishes', 'Do the laundry');
    }

    /** @test */
    public function chore_calendar_shows_chores_assigned_to_team_but_instance_assigned_to_user(): void
    {
        $this->testUser();
        CalendarToken::create([
            'user_id' => $this->user->id,
            'token'   => 'fake_uuid',
        ]);
        Chore::factory(
            ['title' => 'Clean the dishes']
        )
            ->assignedToTeam()
            ->for($this->team)
            ->withFirstInstance(null, $this->user->id)
            ->create();

        $response = $this->get(route('icalendar.show', ['token' => 'fake_uuid']));

        $response->assertOk();
        $response->assertSee('Clean the dishes');
    }
}
