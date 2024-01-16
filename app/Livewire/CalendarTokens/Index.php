<?php

namespace App\Livewire\CalendarTokens;

use App\Models\CalendarToken;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public CalendarToken $calendar_token;

    public Collection $calendar_tokens;

    public string $calendar_type = 'user';

    /** @var array<array<string, mixed>> */
    public array $teams;

    /** @var array<array<string, string>> */
    public array $calendar_types = \App\Models\CalendarToken::CALENDAR_TYPES;

    /** @var array<string, string> */
    protected $rules = [
        'calendar_type' => 'in:user,team',
        'calendar_token.team_id' => 'required_if:calendar_type,team',
        'calendar_token.name' => 'nullable',
    ];

    public function mount(): void
    {
        $this->teams = Auth::user()->allTeams()->toOptionsArray();
        $this->calendar_token = new CalendarToken();
        $this->loadCalendarTokens();
    }

    public function loadCalendarTokens(): void
    {
        $this->calendar_tokens = Auth::user()->calendarTokens;
    }

    public function addCalendarLink(): void
    {
        $this->validate();

        if ($this->calendar_type !== 'team') {
            $this->calendar_token->team_id = null;
        }

        $this->calendar_token->user_id = Auth::id();

        $this->calendar_token->save();
        $this->dispatch('calendar-token.created');
        $this->loadCalendarTokens();

        $this->calendar_token = new CalendarToken();
    }

    public function deleteToken(CalendarToken $token): void
    {
        $token->delete();
        $this->loadCalendarTokens();
    }
}
