<?php

namespace Tests\Feature\CalendarView;

use App\Data\Calendar;

class MockCalendarData
{
    public static function feb2021()
    {
        return [
            Calendar::week(1),
            Calendar::week(8),
            Calendar::week(15),
            Calendar::week(22),
        ];
    }
}
