<?php

namespace Tests\Unit\Data;

use App\Data\Calendar;

class MockCalendarData
{
    public static function february2021()
    {
        return [
            Calendar::week(1),
            Calendar::week(8),
            Calendar::week(15),
            Calendar::week(22),
        ];
    }

    public static function june2020()
    {
        return [
            Calendar::week(1),
            Calendar::week(8),
            Calendar::week(15),
            Calendar::week(22),
            Calendar::week(29, [], [null, null, null, null, null]),
        ];
    }

    public static function march2019()
    {
        return [
            Calendar::week(1, [null, null, null, null]),
            Calendar::week(4),
            Calendar::week(11),
            Calendar::week(18),
            Calendar::week(25),
        ];
    }
}
