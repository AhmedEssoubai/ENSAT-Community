<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    public $timestamps = true;
    protected $fillable = ['label', 'class_id'];

    /**
     * The class of the group
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id', 'id');
    }

    /**
     * The members of this group
     */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    /**
     * Group have many assignments
     */
    public function assignments()
    {
        return $this->morphToMany('App\Assignment', 'assigned')->orderBy('id', 'DESC');
    }

    /**
     * Group assignment submissions
     */
    public function submissions()
    {
        return $this->morphMany('App\Submission', 'submissions', 'submitter_type', 'submitter_id', 'id');
    }
}
