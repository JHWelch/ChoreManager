<?php

namespace App\Livewire\Chores\Modals;

use App\Models\Chore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

/**
 * @property-read array<string, mixed> $userOptions
 */
class CustomComplete extends ModalComponent
{
    public Chore $chore;

    public ?int $user_id = null;

    public string $completed_date;

    public function mount(): void
    {
        $this->completed_date = today()->toDateString();
    }

    public function customComplete(): void
    {
        $this->chore->complete($this->user_id, Carbon::parse($this->completed_date));
        session()->remove('complete');
        $this->closeModal();
        $this->dispatch('choreCompleted');
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
