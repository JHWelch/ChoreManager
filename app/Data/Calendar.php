<?php

namespace App\Data;

use Illuminate\Support\Carbon;

class Calendar
{
    public static array $days_of_week = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    public static function generateCalendarFor($month, $year)
    {
        $calendar = [];
        $date     = Carbon::createFromDate($year, $month, 1);
        $end      = $date->clone()->endOfMonth();

        self::firstWeek($calendar, $date);

        $date->addWeek();

        while ($date->clone()->endOfWeek() <= $end) {
            $calendar[] = self::week($date->clone()->startOfWeek()->day);
            $date->addWeek();
        }

        if ($date->clone()->endOfWeek()->day == 7) {
            return $calendar;
        }

        self::lastWeek($calendar, $date);

        return $calendar;
    }

    protected static function firstWeek(&$calendar, $date)
    {
        $empty_at_start = array_fill(
            0,
            $date->dayOfWeekIso - 1,
            null
        );
        $calendar[] = self::week(1, $empty_at_start);
    }

    protected static function lastWeek(&$calendar, $date)
    {
        $empty_at_end = array_fill(
            0,
            $date->clone()->endOfWeek()->day,
            null
        );

        $calendar[] = self::week(1, [], $empty_at_end);
    }

    public static function week($start_number, $add_in_front = [], $add_at_end = [])
    {
        $week = [];

        $end =  7 - count($add_in_front) - count($add_at_end);

        for ($i = 0; $i < $end; $i++) {
            $week[] = self::day(
                $i + $start_number,
                self::$days_of_week[$i + count($add_in_front)]
            );
        }

        return array_merge(
            $add_in_front,
            $week,
            $add_at_end,
        );
    }

    public static function day($day, $day_of_week)
    {
        return [
            'day'         => $day,
            'day_of_week' => $day_of_week,
        ];
    }
}
