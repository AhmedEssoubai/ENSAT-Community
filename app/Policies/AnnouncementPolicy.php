<?php

namespace App\Policies;

use App\Announcement;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
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
     * Determine whether the user can create announcements.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isProfessor();
    }

    /**
     * Determine whether the user can delete a announcement.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function delete(User $user, Announcement $announcement)
    {
        return $user->isProfessor() && ($announcement->professor_id == $user->profile_id || $user->isAdmin());
    }

    /**
     * Determine whether the user can update a announcement.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function update(User $user, Announcement $announcement)
    {
        return $this->delete($user, $announcement);
    }
}
