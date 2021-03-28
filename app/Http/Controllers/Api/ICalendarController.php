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
    public function index(Request $request, $token)
    {
        $calendar_token = CalendarToken::getToken($token);

        $cal = Calendar::create('Your Chores');

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

        return response($cal->get(), 200, [
            'Content-Type'        => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="my-awesome-calendar.ics"',
            'charset'             => 'utf-8',
         ]);
    }
}
