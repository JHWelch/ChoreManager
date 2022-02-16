<?php

namespace App\Http\Livewire;

use App\Data\Calendar;
use Livewire\Component;

class CalendarView extends Component
{
    public array $calendar = [];

    public function mount()
    {
        $this->generateCalendar();
    }

    public function generateCalendar()
    {
        $today          = today();
        $this->calendar = Calendar::generateCalendarFor(
            $today->month,
            $today->year,
        );
    }
}
