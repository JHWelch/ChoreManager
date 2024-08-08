<?php

namespace App\Livewire\Forms;

use App\Models\CalendarToken as ModelsCalendarToken;
use Livewire\Form;

class CalendarToken extends Form
{
    public string $type = 'user';

    public ?int $team_id = null;

    public ?string $name = null;

    /** @var array<string, string> */
    protected $rules = [
        'type' => 'in:user,team',
        'team_id' => 'required_if:type,team',
        'name' => 'nullable',
    ];

    public function save(): void
    {
        $this->validate();
        if ($this->type !== 'team') {
            $this->team_id = null;
        }

        ModelsCalendarToken::create([
            ...$this->only([
                'name',
                'team_id',
            ]),
            'user_id' => auth()->id(),
        ]);
    }
}
