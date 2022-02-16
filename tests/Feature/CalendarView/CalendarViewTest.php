<?php

namespace Tests\Feature\CalendarView;

use App\Http\Livewire\CalendarView;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire;
use Tests\TestCase;

class CalendarViewTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function authed_user_can_see_calendar()
    {
        $this->testUser();

        $response = $this->get(route('calendar'));

        $response->assertOk();
    }

    /** @test */
    public function unauthed_user_cannot_see_calendar()
    {
        $response = $this->get(route('calendar'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function it_has_current_month_for_title()
    {
        $this->testUser();
        $this->travelTo(Carbon::parse('2/1/2021'));

        $component = Livewire::test(CalendarView::class);

        $component->assertSee('February 2021');
    }

    /** @test */
    public function generateCalendar_creates_calendar_for_month_without_extra_days()
    {
        $this->testUser();
        $this->travelTo(Carbon::parse('2/1/2021'));

        $component = Livewire::test(CalendarView::class)
            ->call('generateCalendar')
            ->assertSet('calendar', MockCalendarData::feb2021());
    }
}
