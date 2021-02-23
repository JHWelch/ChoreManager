<?php

namespace App\Http\Livewire\Chores;

use App\Models\Chore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Save extends Component
{
    public $title;
    public $description;
    public $frequency;

    public function rules()
    {
        return  [
            'title'        => 'string|required',
            'description'  => 'string',
            'frequency'    => Rule::in(array_keys(Chore::FREQUENCIES)),
        ];
    }

    public function save()
    {
        $this->validate();

        Chore::create([
            'title'       => $this->title,
            'description' => $this->description,
            'frequency'   => $this->frequency,
            'user_id'     => Auth::id(),
        ]);
    }
}
