<?php

namespace App\Http\Livewire\Chores;

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use App\Http\Livewire\Chores\Concerns\DisplaysUserList;
use App\Http\Livewire\Concerns\GoesBack;
use App\Http\Livewire\Concerns\TrimAndNullEmptyStrings;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Rules\FrequencyDayOf;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class Save extends Component
{
    use AuthorizesRequests;
    use DisplaysUserList;
    use GoesBack;
    use TrimAndNullEmptyStrings;

    public Chore $chore;
    public ChoreInstance $chore_instance;

    /** @var array<string> */
    public array $user_options;
    public string $team;

    public bool $show_on = false;

    /** @return array<string, mixed>  */
    protected function rules(): array
    {
        return  [
            'chore.title'              => 'string|required',
            'chore.description'        => 'string|nullable',
            'chore.frequency_id'       => new Enum(FrequencyType::class),
            'chore.frequency_interval' => 'min:1',
            'chore.frequency_day_of'   => $this->frequencyDayOfRule(),
            'chore.user_id'            => 'nullable',
            'chore_instance.due_date'  => 'date|nullable|date|after_or_equal:today',
            'chore_instance.user_id'   => 'nullable',
        ];
    }

    protected function frequencyDayOfRule(): string|ValidationRule
    {
        return $this->show_on
            ? new FrequencyDayOf($this->chore->frequency_id)
            : 'nullable';
    }

    public function mount(Chore $chore): void
    {
        $this->defaultBackUrl = route('chores.index');

        $this->chore = $chore;

        $this->authorizePage();

        if ($this->chore->id === null) {
            $this->chore->user_id = Auth::id();
        }
        $this->chore_instance = $chore->nextChoreInstance ?? new ChoreInstance();

        /** @var \App\Models\Team $team */
        $team       = Auth::user()->currentTeam()->select('name')->first();
        $this->team = $team->name;

        $this->show_on = $this->chore->frequency_day_of !== null;
    }

    public function save() : \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $this->validate();
        $this->chore->team_id = Auth::user()->currentTeam->id;
        $this->chore->save();

        $this->saveChoreInstance();

        return $this->back();
    }

    protected function authorizePage(): void
    {
        $this->chore->exists
            ? $this->authorize('update', $this->chore)
            : $this->authorize('create', Chore::class);
    }

    protected function saveChoreInstance(): void
    {
        if ($this->chore_instance->exists) {
            $this->saveExistingChoreInstance();
        } else {
            $this->saveNewChoreInstance();
        }
    }

    protected function saveNewChoreInstance(): void
    {
        if ($this->chore_instance->due_date === null) {
            return;
        }

        $this->chore_instance->chore_id = $this->chore->id;
        $this->chore_instance->user_id  = $this->chore->next_assigned_id;
        $this->chore_instance->save();
    }

    protected function saveExistingChoreInstance(): void
    {
        if (! $this->chore_instance->isDirty()) {
            return;
        }

        if ($this->chore_instance->due_date !== null) {
            $this->chore_instance->save();
        } else {
            $this->chore_instance->delete();
        }
    }

    /** @return array<int, array<string, mixed>> */
    public function getFrequenciesProperty(): array
    {
        return $this->chore->frequency_id == FrequencyType::doesNotRepeat
            ? FrequencyType::adjectivesAsSelectOptions()
            : FrequencyType::nounsAsSelectOptions();
    }

    /** @return array<array<string, mixed>> */
    public function getWeeklyDayOfProperty(): array
    {
        return Frequency::DAYS_OF_THE_WEEK_AS_SELECT_OPTIONS;
    }

    public function isShowOnButton(): bool
    {
        return (! $this->show_on)                                       &&
            $this->chore->frequency_id !== FrequencyType::doesNotRepeat &&
            $this->chore->frequency_id !== FrequencyType::daily;
    }

    public function showDayOfSection(): void
    {
        $this->chore->frequency_day_of = 1;
        $this->show_on                 = true;
    }

    public function hideDayOfSection(): void
    {
        $this->chore->frequency_day_of = null;
        $this->show_on                 = false;
    }

    public function getMaxDayOfProperty(): string
    {
        return match ($this->chore->frequency_id) {
            FrequencyType::monthly   => '31',
            FrequencyType::quarterly => '92',
            FrequencyType::yearly    => '365',
            default                  => '0',
        };
    }

    public function updatedChoreFrequencyId(FrequencyType|string $frequencyType): void
    {
        if (! $frequencyType instanceof FrequencyType) {
            $frequencyType = FrequencyType::from(intval($frequencyType));
        }
        if ($frequencyType    === FrequencyType::doesNotRepeat
            || $frequencyType === FrequencyType::daily
        ) {
            $this->hideDayOfSection();
        } else {
            $this->chore->frequency_day_of = 1;
        }
    }
}
