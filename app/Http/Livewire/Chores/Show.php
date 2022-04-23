<?php

namespace App\Http\Livewire\Chores;

use App\Http\Livewire\Concerns\GoesBack;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use GoesBack;
    use AuthorizesRequests;

    public Chore $chore;
    public ?ChoreInstance $chore_instance;
    public Collection $past_chore_instances;

    public $showDeleteConfirmation    = false;

    public $showCompleteForUserDialog = false;
    public $user_id;
    public $completed_date;

    public function mount()
    {
        $this->authorize('view', $this->chore);
        $this->setGoBackState();
        $this->completed_date = today()->toDateString();
        $this->loadContent();
    }

    public function complete($for = null, $on = null)
    {
        $this->chore_instance->complete($for, $on);
        $this->back();
    }

    public function customComplete()
    {
        $this->complete($this->user_id, Carbon::parse($this->completed_date));
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
        $user = Auth::user();

        return $user
            ->currentTeam
            ->allUsers()
            ->filter(fn ($teamMember) => $teamMember->id !== $user->id)
            ->toOptionsArray();
    }
}
