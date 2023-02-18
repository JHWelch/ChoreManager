<?php

namespace App\Policies;

use App\Models\Chore;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ChorePolicy
{
    use HandlesAuthorization;

    protected function userOwnsChore(User $user, Chore $chore) : bool
    {
        return $chore->user()->is($user) ||
            $this->usersTeamOwnsChore($user, $chore);
    }

    protected function usersTeamOwnsChore(User $user, Chore $chore) : bool
    {
        return $user->allTeams()->map->id->contains($chore->team_id);
    }

    public function viewAny(User $user) : Response|bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chore $chore) : Response|bool
    {
        return $this->userOwnsChore($user, $chore);
    }

    public function create(User $user) : Response|bool
    {
        return true;
    }

    public function update(User $user, Chore $chore) : Response|bool
    {
        return $this->userOwnsChore($user, $chore);
    }

    public function delete(User $user, Chore $chore) : Response|bool
    {
        return $this->userOwnsChore($user, $chore);
    }

    public function restore(User $user, Chore $chore) : Response|bool
    {
        return $this->userOwnsChore($user, $chore);
    }

    public function forceDelete(User $user, Chore $chore) : Response|bool
    {
        return $this->userOwnsChore($user, $chore);
    }
}
