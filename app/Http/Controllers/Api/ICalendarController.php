<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarToken;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class ICalendarController extends Controller
{
    public function show(Request $request, $token)
    {
        $calendar_token = CalendarToken::getToken($token);

        $cal = Calendar::create($calendar_token->name);

        $calendar_token
            ->chores()
            ->onlyWithNextInstance()
            ->orderBy('chore_instances.due_date')
            ->each(function ($chore) use ($cal) {
                $cal->event(Event::create($chore->title)
                    ->startsAt($chore->due_date)
                    ->endsAt($chore->due_date)
                    ->fullDay()
                );
            });

        $filename = preg_replace("/[^a-z0-9\.]/", '', strtolower($calendar_token->name));

        return response($cal->get(), 200, [
            'Content-Type'        => 'text/calendar',
            'Content-Disposition' => "attachment; filename=\"{$filename}.ics\"",
            'charset'             => 'utf-8',
         ]);
    }
}
