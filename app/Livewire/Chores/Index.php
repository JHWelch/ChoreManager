<?php

namespace App\Livewire\Chores;

use App\Livewire\Concerns\FiltersByTeamOrUser;
use App\Models\Chore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Index extends Component
{
    use FiltersByTeamOrUser;

    /** @var Collection<int, Chore> */
    public Collection $chores;

    public string $sort = 'chore_instances.due_date';

    public bool $desc = false;

    public bool $showDescriptionModal = false;

    public Chore $showDescriptionModalChore;

    /** @var array<string, string> */
    protected $rules = [
        'chores.*.due_date' => 'nullable',
    ];

    /** @var array<string, string> */
    protected $listeners = [
        'filterUpdated' => 'loadChores',
    ];

    public function mount(): void
    {
        $this->loadChores();
    }

    public function loadChores(): void
    {
        $query = $this->choreQueryByTeamOrUser()->withNextInstance();
        $query = $this->sort === 'chore_instances.due_date' && ! $this->desc
            ? $query->nullDueDatesAtEnd()
            : $query->orderBy($this->sort, $this->desc ? 'desc' : 'asc');

        $this->chores = $query->get();
    }

    public function sortBy(string $column): void
    {
        if ($this->sort === $column) {
            $this->desc = ! $this->desc;
        } else {
            $this->sort = $column;
            $this->desc = false;
        }

        $this->loadChores();
    }

    public function setShowDescriptionModal(int $chore): void
    {
        $this->showDescriptionModal = true;
        $this->showDescriptionModalChore = Chore::find($chore);
    }

    public function render(): View
    {
        return view('livewire.chores');
    }
}
