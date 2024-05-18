<?php

namespace App\Livewire\Chores;

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use App\Livewire\Concerns\GoesBack;
use App\Livewire\Concerns\TrimAndNullEmptyStrings;
use App\Livewire\Forms\Chore as FormsChore;
use App\Models\Chore;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Save extends Component
{
    use AuthorizesRequests;
    use GoesBack;
    use TrimAndNullEmptyStrings;

    public FormsChore $chore;

    /** @var array<string> */
    public array $user_options;

    public string $team;

    public bool $show_on = false;

    public function mount(Chore $chore): void
    {
        $this->defaultBackUrl = route('chores.index');

        $this->authorizePage($chore);

        $this->chore->fillFromChore($chore);

        $this->setupUserOptions();

        /** @var \App\Models\Team $team */
        $team = Auth::user()->currentTeam()->select('name')->first();
        $this->team = $team->name;

        $this->chore->show_on = $this->chore->frequency_day_of !== null;
        $this->show_on = $this->chore->frequency_day_of !== null;
    }

    public function save(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $this->chore->save();

        return $this->back();
    }

    protected function authorizePage(Chore $chore): void
    {
        $chore->exists
            ? $this->authorize('update', $this->chore)
            : $this->authorize('create', Chore::class);
    }

    protected function setupUserOptions(): void
    {
        $this->user_options = array_values(
            Auth::user()
                ->currentTeam
                ->allUsers()
                ->sortBy(fn ($user) => $user->name)
                ->toOptionsArray()
        );
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
        return (! $this->show_on) &&
            $this->chore->frequency_id !== FrequencyType::doesNotRepeat &&
            $this->chore->frequency_id !== FrequencyType::daily;
    }

    public function showDayOfSection(): void
    {
        $this->chore->frequency_day_of = 1;
        $this->chore->show_on = true;
        $this->show_on = true;
    }

    public function hideDayOfSection(): void
    {
        $this->chore->frequency_day_of = null;
        $this->show_on = false;
    }

    public function getMaxDayOfProperty(): string
    {
        return match ($this->chore->frequency_id) {
            FrequencyType::monthly => '31',
            FrequencyType::quarterly => '92',
            FrequencyType::yearly => '365',
            default => '0',
        };
    }

    public function updatedChoreFrequencyId(FrequencyType|string $frequencyType): void
    {
        if (! $frequencyType instanceof FrequencyType) {
            $frequencyType = FrequencyType::from(intval($frequencyType));
        }
        if ($frequencyType === FrequencyType::doesNotRepeat
            || $frequencyType === FrequencyType::daily
        ) {
            $this->hideDayOfSection();
        } else {
            $this->chore->frequency_day_of = 1;
        }
    }
}
