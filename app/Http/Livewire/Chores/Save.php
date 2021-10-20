<?php

namespace App\Http\Livewire\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Concerns\GoesBack;
use App\Http\Livewire\Concerns\TrimAndNullEmptyStrings;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Rules\FrequencyDayOf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Save extends Component
{
    use GoesBack;
    use TrimAndNullEmptyStrings;

    public Chore $chore;
    public ChoreInstance $chore_instance;

    public $due_date;

    public $user_options;
    public string $team;

    public $show_on = false;

    protected function rules()
    {
        return  [
            'chore.title'              => 'string|required',
            'chore.description'        => 'string|nullable',
            'chore.frequency_id'       => Rule::in(Frequency::FREQUENCIES),
            'chore.frequency_interval' => 'min:1',
            'chore.frequency_day_of'   => $this->show_on
                ? new FrequencyDayOf($this->chore->frequency_id)
                : 'nullable',
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
        $this->user_options   = array_values(
            Auth::user()
                ->currentTeam
                ->allUsers()
                ->sortBy(fn ($user) => $user->name)
                ->toOptionsArray()
        );

        $this->team = Auth::user()->currentTeam()->select('name')->first()->name;

        $this->show_on = $this->chore->frequency_day_of !== null;
    }

    public function save()
    {
        $this->validate();
        $this->chore->team_id = Auth::user()->currentTeam->id;
        $this->chore->save();

        if (! $this->chore_instance->exists && $this->chore_instance->due_date !== null) {
            $this->chore_instance->chore_id = $this->chore->id;
            $this->chore_instance->user_id  = $this->chore->next_assigned_id;
            $this->chore_instance->save();
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

    public function getFrequenciesProperty()
    {
        return $this->chore->frequency_id == 0
            ? Frequency::adjectivesAsSelectOptions()
            : Frequency::nounsAsSelectOptions();
    }

    public function getWeeklyDayOfProperty()
    {
        return Frequency::DAYS_OF_THE_WEEK_AS_SELECT_OPTIONS;
    }

    public function isShowOnButton()
    {
        return (! $this->show_on)                                       &&
            $this->chore->frequency_id !== Frequency::DOES_NOT_REPEAT   &&
            $this->chore->frequency_id !== Frequency::DAILY;
    }

    public function showDayOfSection()
    {
        $this->chore->frequency_day_of = 1;
        $this->show_on                 = true;
    }

    public function hideDayOfSection()
    {
        $this->chore->frequency_day_of = null;
        $this->show_on                 = false;
    }

    public function getMaxDayOfProperty()
    {
        return match (intval($this->chore->frequency_id)) {
            Frequency::MONTHLY   => '31',
            Frequency::QUARTERLY => '92',
            Frequency::YEARLY    => '365',
            default              => '0',
        };
    }

    public function updatedChoreFrequencyId(int $frequency_id)
    {
        if ($frequency_id === Frequency::DOES_NOT_REPEAT
            || $frequency_id === Frequency::DAILY
        ) {
            $this->hideDayOfSection();
        } else {
            $this->chore->frequency_day_of = 1;
        }
    }
}
