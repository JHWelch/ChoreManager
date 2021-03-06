<?php

namespace App\Http\Livewire\Chores;

use App\Http\Livewire\Concerns\GoesBack;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use GoesBack;

    public Chore $chore;
    public ?ChoreInstance $chore_instance;
    public Collection $past_chore_instances;

    public $showDeleteConfirmation    = false;
    public $showCompleteForUserDialog = false;
    public $user_id;

    public function mount()
    {
        $this->setGoBackState();
        $this->loadContent();
    }

    public function complete($for = null)
    {
        $this->chore_instance->complete($for);
        $this->chore->refresh();
        $this->loadContent();
    }

    public function completeForUser()
    {
        $this->complete($this->user_id);

        $this->showCompleteForUserDialog = false;
    }

    public function loadContent()
    {
        $this->chore_instance       = $this->chore->nextChoreInstance;
        $this->past_chore_instances = $this->chore->pastChoreInstances;
    }

    public function delete()
    {
        $this->chore->delete();
        $this->back();
    }

    public function getUserOptionsProperty()
    {
        return Auth::user()
            ->currentTeam
            ->allUsers()
            ->toOptionsArray();
    }
}
