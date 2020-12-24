<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can delete a user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $target_user
     * @return mixed
     */
    public function delete(User $user, User $target_user)
    {
        return $user->isAdmin() && !$target_user->isAdmin();
    }

    /**
     * Determine whether the user can update a user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $target_user
     * @return mixed
     */
    public function update(User $user, User $target_user)
    {
        return $user->isAdmin() || $user->id == $target_user->id;
    }
}
