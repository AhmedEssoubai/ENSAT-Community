<?php

namespace App\Providers;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Discussion' => 'App\Policies\DiscussionPolicy',
        'App\Resource' => 'App\Policies\ResourcePolicy',
        'App\Assignment' => 'App\Policies\AssignmentPolicy',
        'App\Submission' => 'App\Policies\SubmissionPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Classe' => 'App\Policies\ClassPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Course' => 'App\Policies\CoursePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('class-member', function ($user, $class) {
            if (empty($class))
                abort(403, 'Unauthorized action.');
            if ($user->isProfessor())
                return $user->isAdmin() || $class->professors()->where('id', $user->profile_id)->exists()
                        ? Response::allow()
                        : Response::deny('You are not a class member');
            else
                return $class->students()->where('id', $user->profile_id)->exists()
                        ? Response::allow()
                        : Response::deny('You are not a class member');
        });
    }
}
