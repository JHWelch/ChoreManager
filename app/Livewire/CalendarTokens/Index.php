<?php

namespace App\Livewire\CalendarTokens;

use App\Livewire\Forms\CalendarToken;
use App\Models\CalendarToken as ModelsCalendarToken;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    public CalendarToken $form;

    public Collection $calendar_tokens;

    /** @var array<array<string, mixed>> */
    public array $teams;

    /** @var array<array<string, string>> */
    public array $calendar_types = \App\Models\CalendarToken::CALENDAR_TYPES;

    public function mount(): void
    {
        $this->teams = Auth::user()->allTeams()->toOptionsArray();
        $this->loadCalendarTokens();
    }

    public function loadCalendarTokens(): void
    {
        $this->calendar_tokens = Auth::user()->calendarTokens;
    }

    public function addCalendarLink(): void
    {
        $this->form->save();
        $this->form->reset();

        $this->dispatch('calendar-token.created');
        $this->loadCalendarTokens();
    }

    public function deleteToken(ModelsCalendarToken $token): void
    {
        $token->delete();
        $this->loadCalendarTokens();
    }

    public function render(): View
    {
        return view('livewire.calendar-tokens.index');
    }
}
