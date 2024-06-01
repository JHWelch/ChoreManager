<?php

namespace App\Livewire\Forms;

use App\Enums\FrequencyType;
use App\Models\Chore as ChoreModel;
use App\Models\ChoreInstance;
use App\Rules\FrequencyDayOf;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Locked;
use Livewire\Form;

class Chore extends Form
{
    #[Locked]
    public ?int $chore_id = null;

    #[Locked]
    public ?int $instance_id = null;

    public ?string $title = '';

    public ?string $description = null;

    public $frequency_id = FrequencyType::daily->value;

    public int $frequency_interval = 1;

    public ?int $frequency_day_of = null;

    public ?int $chore_user_id = null;

    public ?string $due_date = null;

    public ?int $instance_user_id = null;

    public bool $show_on = false;

    public ?int $team_id = null;

    /** @return array<string, mixed>  */
    protected function rules(): array
    {
        return [
            'title' => 'string|required',
            'description' => 'string|nullable',
            'frequency_id' => new Enum(FrequencyType::class),
            'frequency_interval' => 'min:1',
            'frequency_day_of' => $this->frequencyDayOfRule(),
            'chore_user_id' => 'nullable',
            'due_date' => 'date|nullable|date|after_or_equal:today',
            'instance_user_id' => 'nullable',
        ];
    }

    public function fillFromChore(ChoreModel $chore): void
    {
        $instance = $chore->nextChoreInstance ?? new ChoreInstance();

        $this->fill([
            'chore_id' => $chore->id,
            'chore_user_id' => $chore->id ? $chore->user_id : auth()->id(),
            'instance_id' => $instance->id,
            'instance_user_id' => $instance->user_id,
            'due_date' => $instance->due_date?->format('Y-m-d'),
            ...$chore->only([
                'title',
                'description',
                'frequency_id',
                'frequency_interval',
                'frequency_day_of',
                'team_id',
            ]),
        ]);
    }

    public function save(): void
    {
        $this->validate();
        $this->team_id ??= auth()->user()->currentTeam->id;

        $chore = ChoreModel::updateOrCreate(['id' => $this->chore_id], [
            ...$this->only([
                'title',
                'description',
                'frequency_id',
                'frequency_interval',
                'frequency_day_of',
                'team_id',
            ]),
            'user_id' => $this->chore_user_id,
        ]);

        $this->saveChoreInstance($chore);
    }

    protected function saveChoreInstance(ChoreModel $chore): void
    {
        if ($this->instance_id) {
            $this->saveExistingChoreInstance();
        } else {
            $this->saveNewChoreInstance($chore);
        }
    }

    protected function saveNewChoreInstance(ChoreModel $chore): void
    {
        if ($this->due_date === null) {
            return;
        }

        ChoreInstance::create([
            'chore_id' => $chore->id,
            'due_date' => $this->due_date,
            'user_id' => $chore->next_assigned_id,
        ]);
    }

    protected function saveExistingChoreInstance(): void
    {
        $instance = ChoreInstance::findOrFail($this->instance_id);
        if ($this->due_date === null) {
            $instance->delete();

            return;
        }

        $instance->update([
            'due_date' => $this->due_date,
            'user_id' => $this->instance_user_id,
        ]);
    }

    public function isDoesNotRepeat(): bool
    {
        return $this->frequency_id === FrequencyType::doesNotRepeat;
    }

    public function isWeekly(): bool
    {
        return $this->frequency_id === FrequencyType::weekly;
    }

    public function isYearly(): bool
    {
        return $this->frequency_id === FrequencyType::yearly;
    }

    protected function frequencyDayOfRule(): string|ValidationRule
    {
        return $this->show_on
            ? new FrequencyDayOf($this->frequency())
            : 'nullable';
    }

    public function frequency(): FrequencyType
    {
        return $this->frequency_id instanceof FrequencyType
            ? $this->frequency_id
            : FrequencyType::from($this->frequency_id);
    }
}
