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
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read array<int, array<string, mixed>> $frequencies
 * @property-read array<array<string, mixed>> $weeklyDayOf
 * @property-read string $maxDayOf
 */
class Save extends Component
{
    use AuthorizesRequests;
    use GoesBack;
    use TrimAndNullEmptyStrings;

    public FormsChore $form;

    /** @var array<string> */
    public array $user_options;

    public string $team;

    public bool $show_on = false;

    public function mount(Chore $chore): void
    {
        $this->defaultBackUrl = route('chores.index');

        $this->authorizePage($chore);

        $this->form->fillFromChore($chore);

        $this->setupUserOptions();

        /** @var \App\Models\Team $team */
        $team = Auth::user()->currentTeam()->select('name')->first();
        $this->team = $team->name;

        $this->form->show_on = $this->form->frequency_day_of !== null;
        $this->show_on = $this->form->frequency_day_of !== null;
    }

    public function save(): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $this->form->save();

        return $this->back();
    }

    protected function authorizePage(Chore $chore): void
    {
        $chore->exists
            ? $this->authorize('update', $chore)
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

    public function isShowOnButton(): bool
    {
        return (! $this->show_on) &&
            $this->form->frequency_id !== FrequencyType::doesNotRepeat &&
            $this->form->frequency_id !== FrequencyType::daily;
    }

    public function showDayOfSection(): void
    {
        $this->form->frequency_day_of = 1;
        $this->form->show_on = true;
        $this->show_on = true;
    }

    public function hideDayOfSection(): void
    {
        $this->form->frequency_day_of = null;
        $this->show_on = false;
    }

    /** @return array<int, array<string, mixed>> */
    #[Computed]
    public function frequencies(): array
    {
        return $this->form->frequency_id == FrequencyType::doesNotRepeat
            ? FrequencyType::adjectivesAsSelectOptions()
            : FrequencyType::nounsAsSelectOptions();
    }

    /** @return array<array<string, mixed>> */
    #[Computed]
    public function weeklyDayOf(): array
    {
        return Frequency::DAYS_OF_THE_WEEK_AS_SELECT_OPTIONS;
    }

    #[Computed]
    public function maxDayOf(): string
    {
        return match ($this->form->frequency_id) {
            FrequencyType::monthly => '31',
            FrequencyType::quarterly => '92',
            FrequencyType::yearly => '365',
            default => '0',
        };
    }

    public function updatedFormFrequencyId(FrequencyType|string $frequencyType): void
    {
        if (! $frequencyType instanceof FrequencyType) {
            $frequencyType = FrequencyType::from(intval($frequencyType));
        }
        if ($frequencyType === FrequencyType::doesNotRepeat
            || $frequencyType === FrequencyType::daily
        ) {
            $this->hideDayOfSection();
        } else {
            $this->form->frequency_day_of = 1;
        }
    }
}
