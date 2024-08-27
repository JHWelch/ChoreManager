<?php

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the start of the current day, time 00:00:00.
     */
    function today(\DateTimeZone|string|null $tz = null): \Illuminate\Support\Carbon
    {
        return \Illuminate\Support\Carbon::now($tz)->startOfDay();
    }
}
