<?php

namespace App\Http\Livewire\Chores;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Livewire\Component;

class Show extends Component
{
    public Chore $chore;
    public ?ChoreInstance $chore_instance;

    public function mount()
    {
        $this->chore_instance = $this->chore->nextChoreInstance;
    }

    public function complete()
    {
        $this->chore_instance->complete();
    }
}
