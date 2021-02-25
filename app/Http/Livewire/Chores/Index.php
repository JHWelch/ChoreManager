<?php

namespace App\Http\Livewire\Chores;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public Collection $chores;

    public function mount()
    {
        $this->chores = Auth::user()->chores()->with('nextChoreInstance')->get();
    }
}
