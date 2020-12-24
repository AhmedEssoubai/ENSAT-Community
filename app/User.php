<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'image', 'status', 'cin', 'profile_id', 'profile_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Is the user a professor
     */
    public function isProfessor()
    {
        return $this->profile_type == 'App\Professor';
    }

    /**
     * Is the user a student
     */
    public function isStudent()
    {
        return $this->profile_type == 'App\Student';
    }

    /**
     * Is the user an admin
     */
    public function isAdmin()
    {
        return $this->status == 'admin';
    }

    /**
     * Is the user in pending list
     */
    public function isPending()
    {
        return $this->status == 'pending';
    }

    /**
     * The user discussions
     * 
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'user_id', 'id');
    }

    /**
    *   The user's profile
    */
    public function profile()
    {
        return $this->morphTo();
    }
}
