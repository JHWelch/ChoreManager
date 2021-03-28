<?php

namespace App\Http\Livewire\CalendarTokens;

use App\Models\CalendarToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public $calendar_type = 'user';
    public $team_id;
    public $teams;
    public $calendar_types = [
        [
            'label'       => 'User Calendar',
            'value'       => 'user',
            'description' => 'This calendar will include upcoming chores assigned to you, across Teams.',
        ],
        [
            'label'       => 'Team Calendar',
            'value'       => 'team',
            'description' => 'This calendar will include upcoming chores for everyone in a given Team.',
        ],
    ];

    protected $rules = [
        'calendar_type' => 'in:user,team',
        'team_id'       => 'required_if:calendar_type,team',
    ];

    public function mount()
    {
        $this->teams = Auth::user()->allTeams()->toOptionsArray();
    }

    public function addCalendarLink()
    {
        $this->validate();

        CalendarToken::create([
            'user_id' => Auth::id(),
            'team_id' => $this->calendar_type === 'team' ? $this->team_id : null,
            'token'   => Str::uuid(),
        ]);
    }
}
