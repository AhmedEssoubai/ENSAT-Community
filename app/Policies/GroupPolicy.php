<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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
     * Determine whether the user can create courses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isProfessor();
    }

    /**
     * Determine whether the user can delete a course.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function delete(User $user, Group $group)
    {
        return $user->isProfessor();
    }

    /**
     * Determine whether the user can update a course.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function update(User $user, Group $group)
    {
        return $user->isProfessor();
    }
}
