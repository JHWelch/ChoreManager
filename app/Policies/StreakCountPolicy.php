<?php

namespace App\Policies;

use App\Models\StreakCount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StreakCountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StreakCount  $streakCount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StreakCount $streakCount): bool
    {
        return $user->id === $streakCount->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StreakCount  $streakCount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StreakCount $streakCount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StreakCount  $streakCount
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StreakCount $streakCount): bool
    {
        return false;
    }
}
