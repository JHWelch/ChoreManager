<?php

namespace App\Livewire\Chores;

use App\Livewire\Concerns\GoesBack;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;
    use GoesBack;

    public Chore $chore;

    public ?ChoreInstance $chore_instance;

    /** @var Collection<int, ChoreInstance> */
    public Collection $past_chore_instances;

    public bool $showDeleteConfirmation = false;

    public function mount(): void
    {
        $this->authorize('view', $this->chore);
        $this->loadContent();
        if (session()->get('complete')) {
            $this->dispatch('openModal', 'chores.modals.custom-complete', ['chore' => $this->chore]);
        }
    }

    public function complete(): void
    {
        $this->chore->complete();

        $this->fromCompleteRoute() ? $this->loadContent() : $this->back();
    }

    protected function fromCompleteRoute(): bool
    {
        return $this->previousUrl === route('chores.complete.index', ['chore' => $this->chore]);
    }

    public function loadContent(): void
    {
        $this->chore->load(
            'nextChoreInstance',
            'pastChoreInstances',
            'pastChoreInstances.completedBy'
        );
        $this->chore_instance = $this->chore->nextChoreInstance()->with('user')->first();
        $this->past_chore_instances = $this->chore->pastChoreInstances;
    }

    public function delete(): void
    {
        $this->chore->delete();
        $this->back();
    }
}
