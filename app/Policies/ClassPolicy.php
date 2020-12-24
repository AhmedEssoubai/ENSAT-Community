<?php

namespace App\Policies;

use App\User;
use App\Classe;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassPolicy
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
     * Determine whether the user can create classes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete a class.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function delete(User $user, Classe $class)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update a class.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function update(User $user, Classe $class)
    {
        return $user->isAdmin() || ($user->isProfessor() && $class->isChef($user->profile_id));
    }

    /**
     * Determine whether the user can add a professor to a class.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function add_professor(User $user, Classe $class)
    {
        return $user->isAdmin() || ($user->isProfessor() && $class->isChef($user->profile->id));
    }
}
