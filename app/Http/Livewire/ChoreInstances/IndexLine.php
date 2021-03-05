<?php

namespace App\Http\Livewire\ChoreInstances;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Livewire\Component;

class IndexLine extends Component
{
    public Chore $chore;
    public ChoreInstance $chore_instance;

    public function mount(Chore $chore)
    {
        $this->chore          = $chore;
        $this->chore_instance = $chore->nextChoreInstance;
    }

    public function complete()
    {
        $this->chore_instance->complete();
        $this->chore_instance->refresh();
    }
}
