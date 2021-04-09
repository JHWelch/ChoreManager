<?php

namespace App\Http\Livewire\Chores;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Show extends Component
{
    public Chore $chore;
    public ?ChoreInstance $chore_instance;
    public Collection $past_chore_instances;

    public function mount()
    {
        $this->loadContent();
    }

    public function complete()
    {
        $this->chore_instance->complete();
        $this->chore->refresh();
        $this->loadContent();
    }

    public function loadContent()
    {
        $this->chore_instance       = $this->chore->nextChoreInstance;
        $this->past_chore_instances = $this->chore->pastChoreInstances;
    }
}
