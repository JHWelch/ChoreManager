<?php

namespace App\Http\Livewire\Chores;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Save extends Component
{
    public Chore $chore;
    public ChoreInstance $chore_instance;

    public $frequencies = [];
    public $due_date;

    protected function rules()
    {
        return  [
            'chore.title'             => 'string|required',
            'chore.description'       => 'string|nullable',
            'chore.frequency_id'      => Rule::in(array_keys(Chore::FREQUENCIES)),
            'chore_instance.due_date' => 'date|nullable|date|after_or_equal:today',
        ];
    }

    public function mount(Chore $chore)
    {
        $this->chore          = $chore                      ?? Chore::make();
        $this->chore_instance = $chore->next_chore_instance ?? ChoreInstance::make();
        $this->frequencies    = Chore::frequenciesAsSelectOptions();
    }

    public function save()
    {
        $this->validate();

        $this->chore->user_id = Auth::id();
        $this->chore->save();

        if (! $this->chore_instance->exists) {
            if ($this->chore_instance->due_date !== null) {
                $this->chore_instance->chore_id = $this->chore->id;
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

        return $this->redirect(route('chores.index'));
    }
}
