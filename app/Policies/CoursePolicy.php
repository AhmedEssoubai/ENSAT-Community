<?php

namespace App\Policies;

use App\Classe;
use App\Course;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class CoursePolicy
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
    public function delete(User $user, Course $course)
    {
        return $user->isAdmin() || ($user->isProfessor() && ($course->professor_id == $user->profile_id || $course->classe->chef_id == $user->profile_id));
    }

    /**
     * Determine whether the user can update a course.
     *
     * @param  \App\User  $user
     * @param  \App\Classe  $class
     * @return mixed
     */
    public function update(User $user, Course $course)
    {
        return $this->delete($user, $course);
    }
}
