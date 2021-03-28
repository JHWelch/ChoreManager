<?php

namespace App\Http\Livewire\CalendarTokens;

use App\Models\CalendarToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public CalendarToken $calendar_token;

    public $calendar_type = 'user';

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
        'calendar_type'          => 'in:user,team',
        'calendar_token.team_id' => 'required_if:calendar_type,team',
        'calendar_token.name'    => 'nullable',
    ];

    public function mount()
    {
        $this->teams          = Auth::user()->allTeams()->toOptionsArray();
        $this->calendar_token = CalendarToken::make();
    }

    public function addCalendarLink()
    {
        $this->validate();

        if ($this->calendar_type !== 'team') {
            $this->calendar_token->team_id = null;
        }

        $this->calendar_token->user_id = Auth::id();
        $this->calendar_token->token   = Str::uuid();

        $this->calendar_token->save();

        $this->calendar_token = CalendarToken::make();
    }
}
