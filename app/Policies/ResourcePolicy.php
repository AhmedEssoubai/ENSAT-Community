<?php

namespace App\Policies;

use App\Resource;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
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
        return $user->isProfessor();
    }

    /**
     * Determine whether the user can delete a resource.
     *
     * @param  \App\User  $user
     * @param  \App\Resource  $resource
     * @return mixed
     */
    public function delete(User $user, Resource $resource)
    {
        return $user->isAdmin() || ($user->isProfessor() && ($resource->professor->id == $user->profile_id || $resource->class->chef_id == $user->profile_id));
    }

    /**
     * Determine whether the user can update a discussion.
     *
     * @param  \App\User  $user
     * @param  \App\Resource  $resource
     * @return mixed
     */
    public function update(User $user, Resource $resource)
    {
        return $user->isProfessor() && $resource->professor->id == $user->profile_id;
    }
}
