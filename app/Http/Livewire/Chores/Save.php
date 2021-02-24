<?php

namespace App\Http\Livewire\Chores;

use App\Models\Chore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Save extends Component
{
    public $chore;

    protected function rules()
    {
        return  [
            'chore.title'        => 'string|required',
            'chore.description'  => 'string',
            'chore.frequency'    => Rule::in(array_keys(Chore::FREQUENCIES)),
        ];
    }

    public function mount(Chore $chore)
    {
        $this->chore = $chore ?? Chore::make();
    }

    public function save()
    {
        $this->validate();

        $this->chore->user_id = Auth::id();
        $this->chore->save();
    }
}
