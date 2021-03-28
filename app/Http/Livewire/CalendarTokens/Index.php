<?php

namespace App\Http\Livewire\CalendarTokens;

use App\Models\CalendarToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Index extends Component
{
    public $is_team_calendar = false; // Alternative is User Calendar.
    public $team_id;

    protected $rules = [
        'is_team_calendar' => 'boolean',
        'team_id'          => 'required_if:is_team_calendar,true',
    ];

    public function add()
    {
        $this->validate();

        CalendarToken::create([
            'user_id' => Auth::id(),
            'team_id' => $this->team_id,
            'token'   => Str::uuid(),
        ]);
    }
}
