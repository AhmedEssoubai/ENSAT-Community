<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Resource extends Model
{
    protected $table = 'resources';
    //public $timestamps = false;
    protected $fillable = ['title', 'content', 'course_id'];

    /**
     * The course the resource blongs to
    */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * The resource class
     */
    public function class()
    {
        return $this->hasOneThrough('App\Classe', 'App\Course', 'id', 'id', 'course_id', 'class_id');
    }

    /**
     * The resource professor
     */
    public function professor()
    {
        return $this->hasOneThrough('App\Professor', 'App\Course', 'id', 'id', 'course_id', 'professor_id');
    }

    /**
     * Resource files
     */
    public function files()
    {
        return $this->morphMany('App\File', 'container');
    }

    /**
     * The students that view the resource
     */
    public function views()
    {
        return $this->morphToMany('App\Student', 'seen', 'views')->withPivot('seen_at');
    }

    public function can_delete(User $user)
    {
        return true;
    }

    public function can_edit(User $user)
    {
        return true;
    }
}
