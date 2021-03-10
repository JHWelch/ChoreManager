<?php

if (! function_exists('today')) {
    /**
     * Create a new Carbon instance for the start of the current day, time 00:00:00.
     *
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function today($tz = null)
    {
        return \Carbon\Carbon::now($tz)->startOfDay();
    }
}
