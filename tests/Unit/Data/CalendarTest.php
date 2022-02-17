<?php

namespace Tests\Unit\Data;

use App\Data\Calendar;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function day_formats_day_into_array()
    {
        $this->assertEquals(
            [
                'day'         => 4,
                'day_of_week' => 'wednesday',
            ],
            Calendar::day(4, 'wednesday'),
        );
    }

    /** @test */
    public function week_can_format_without_anything_on_either_side()
    {
        $expected = [
            Calendar::day(2, 'monday'),
            Calendar::day(3, 'tuesday'),
            Calendar::day(4, 'wednesday'),
            Calendar::day(5, 'thursday'),
            Calendar::day(6, 'friday'),
            Calendar::day(7, 'saturday'),
            Calendar::day(8, 'sunday'),
        ];

        $this->assertEquals($expected, Calendar::week(2));
    }

    /** @test */
    public function week_can_add_items_on_front()
    {
        $expected = [
            null,
            null,
            Calendar::day(1, 'wednesday'),
            Calendar::day(2, 'thursday'),
            Calendar::day(3, 'friday'),
            Calendar::day(4, 'saturday'),
            Calendar::day(5, 'sunday'),
        ];

        $this->assertEquals(
            $expected,
            Calendar::week(1, [null, null])
        );
    }

    /** @test */
    public function week_can_add_items_on_end()
    {
        $expected = [
            Calendar::day(27, 'monday'),
            Calendar::day(28, 'tuesday'),
            Calendar::day(29, 'wednesday'),
            Calendar::day(30, 'thursday'),
            Calendar::day(31, 'friday'),
            null,
            null,
        ];

        $this->assertEquals(
            $expected,
            Calendar::week(27, [], [null, null])
        );
    }

    /** @test */
    public function generateCalendarFor_creates_calendar_for_month_without_extra_days()
    {
        $actual = Calendar::generateCalendarFor(2, 2021);

        $this->assertEquals(MockCalendarData::february2021(), $actual);
    }

    /** @test */
    public function generateCalendarFor_creates_calendar_for_month_without_blanks_at_end()
    {
        $actual = Calendar::generateCalendarFor(6, 2020);

        $this->assertEquals(MockCalendarData::june2020(), $actual);
    }

    /** @test */
    public function generateCalendarFor_creates_calendar_for_month_without_blanks_at_start()
    {
        $actual = Calendar::generateCalendarFor(3, 2019);

        $this->assertEquals(MockCalendarData::march2019(), $actual);
    }
}
