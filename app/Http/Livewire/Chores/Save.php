<?php

namespace App\Http\Livewire\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Concerns\GoesBack;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Save extends Component
{
    use GoesBack;

    public Chore $chore;
    public ChoreInstance $chore_instance;

    public $frequencies = [];
    public $due_date;

    public $user_options;

    protected function rules()
    {
        return  [
            'chore.title'              => 'string|required',
            'chore.description'        => 'string|nullable',
            'chore.frequency_id'       => Rule::in(Frequency::FREQUENCIES),
            'chore.frequency_interval' => 'min:1',
            'chore.user_id'            => 'nullable',
            'chore_instance.due_date'  => 'date|nullable|date|after_or_equal:today',
            'chore_instance.user_id'   => 'nullable',
        ];
    }

    public function mount(Chore $chore)
    {
        $this->setGoBackState(route('chores.index'));
        $this->chore = $chore;

        if ($this->chore->id === null) {
            $this->chore->user_id = Auth::id();
        }
        $this->chore_instance = $chore->nextChoreInstance ?? ChoreInstance::make();
        $this->setFrequencies();
        $this->user_options   = array_values(
            Auth::user()
                ->currentTeam
                ->allUsers()
                ->sortBy(fn ($user) => $user->name)
                ->toOptionsArray()
        );
    }

    public function save()
    {
        $this->validate();
        $this->chore->team_id = Auth::user()->currentTeam->id;
        $this->chore->save();

        if (! $this->chore_instance->exists) {
            if ($this->chore_instance->due_date !== null) {
                $this->chore_instance->chore_id = $this->chore->id;
                $this->chore_instance->user_id  = $this->chore->user_id;
                $this->chore_instance->save();
            }
        } else {
            if ($this->chore_instance->isDirty()) {
                if ($this->chore_instance->due_date !== null) {
                    $this->chore_instance->save();
                } else {
                    $this->chore_instance->delete();
                }
            }
        }

        return $this->back();
    }

    public function updatedChoreFrequencyId()
    {
        $this->setFrequencies();
    }

    public function setFrequencies()
    {
        $this->frequencies = $this->chore->frequency_id == 0
            ? Frequency::adjectivesAsSelectOptions()
            : Frequency::nounsAsSelectOptions();
    }
}
