<?php

namespace App\Http\Livewire\CalendarTokens;

use App\Models\CalendarToken;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public CalendarToken $calendar_token;
    public Collection $calendar_tokens;

    public $calendar_type = 'user';

    public $teams;
    public $calendar_types = \App\Models\CalendarToken::CALENDAR_TYPES;

    protected $rules = [
        'calendar_type'          => 'in:user,team',
        'calendar_token.team_id' => 'required_if:calendar_type,team',
        'calendar_token.name'    => 'nullable',
    ];

    public function mount()
    {
        $this->teams          = Auth::user()->allTeams()->toOptionsArray();
        $this->calendar_token = new CalendarToken();
        $this->loadCalendarTokens();
    }

    public function loadCalendarTokens()
    {
        $this->calendar_tokens = Auth::user()->calendarTokens;
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
        $this->emit('calendar-token.created');
        $this->loadCalendarTokens();

        $this->calendar_token = new CalendarToken();
    }

    public function deleteToken(CalendarToken $token)
    {
        $token->delete();
        $this->loadCalendarTokens();
    }
}
