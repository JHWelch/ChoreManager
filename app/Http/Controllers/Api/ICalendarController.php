<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarToken;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class ICalendarController extends Controller
{
    public function show(Request $request, $token)
    {
        $calendar_token = CalendarToken::getToken($token);

        $cal = Calendar::create($calendar_token->name);

        $calendar_token
            ->choreInstances()
            ->each(function ($chore_instance) use ($cal) {
                $cal->event(
                    Event::create($chore_instance->chore->title)
                    ->startsAt($chore_instance->due_date)
                    ->endsAt($chore_instance->due_date)
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
