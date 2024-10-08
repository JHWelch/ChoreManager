<?php

namespace App\Livewire\Chores;

use App\Livewire\Concerns\GoesBack;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read array<string, mixed> $userOptions
 */
class Show extends Component
{
    use AuthorizesRequests;
    use GoesBack;

    public Chore $chore;

    public ?ChoreInstance $chore_instance;

    /** @var Collection<int, ChoreInstance> */
    public Collection $past_chore_instances;

    public bool $showDeleteConfirmation = false;

    public bool $showCompleteForUserDialog;

    public ?int $user_id = null;

    public string $completed_date;

    public function mount(): void
    {
        $this->authorize('view', $this->chore);
        $this->completed_date = today()->toDateString();
        $this->loadContent();
        $this->showCompleteForUserDialog = session()->get('complete') ?? false;
    }

    public function complete(?int $for = null, ?Carbon $on = null): void
    {
        $this->chore->complete($for, $on);
        session()->remove('complete');
        $this->showCompleteForUserDialog = false;

        $this->fromCompleteRoute() ? $this->loadContent() : $this->back();
    }

    protected function fromCompleteRoute(): bool
    {
        return $this->previousUrl === route('chores.complete.index', ['chore' => $this->chore]);
    }

    public function customComplete(): void
    {
        $this->complete($this->user_id, Carbon::parse($this->completed_date));
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

    /** @return array<string, mixed> */
    #[Computed()]
    public function userOptions(): array
    {
        $user = Auth::user();

        return Auth::user()
            ->currentTeam
            ->allUsers()
            ->filter(fn ($teamMember) => $teamMember->id !== $user->id)
            ->toOptionsArray();
    }
}
