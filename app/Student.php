<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    public $timestamps = true;
    protected $fillable = ['class_id', 'user_id'];

    /**
     * The class of the student
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id', 'id');
    }

    /**
     * The user account of the student
     */
    /*public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }*/
    public function user()
    {
        return $this->morphOne('App\User', 'profile');
    }

    /**
     * Student groups
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * Student have many assignments
     */
    public function assignments()
    {
        return $this->morphToMany('App\Assignment', 'assigned')->orderBy('id', 'DESC');
    }

    /**
     * Student assignment submissions
     */
    public function submissions()
    {
        return $this->morphMany('App\Submission', 'submissions', 'submitter_type', 'submitter_id', 'id');
    }

    /**
     * Get all of the students that are assigned to this assignment.
     */
    public function resourcesViews()
    {
        return $this->morphedByMany('App\Resource', 'seen', 'views')->withPivot('seen_at');
    }
}
