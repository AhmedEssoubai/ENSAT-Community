<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'assignments';
    //public $timestamps = false;
    protected $fillable = ['title', 'objectif', 'deadline', 'course_id', 'all', 'to_groups', 'assigned_to_count'];

    /**
     * The course the assignment blongs to
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
     * Assignment files
     */
    public function files()
    {
        return $this->morphMany('App\File', 'container');
    }

    /**
     * Get all of the students that are assigned to this assignment.
     */
    public function students()
    {
        return $this->morphedByMany('App\Student', 'assigned');
    }

    /**
     * Get all of the groups that are assigned to this assignment.
     */
    public function groups()
    {
        return $this->morphedByMany('App\Group', 'assigned');
    }

    /**
    *   The assignment submission
    */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the submission of a student if exists
     */
    public function getSubmission($user)
    {
        if ($user->isStudent())
            return $this->submissions()
            ->select('id', 'assignment_id', 'submitter_id', 'submitter_type')
            ->with('files')
            ->where('submitter_id', $user->profile_id)->first();
        return null;
    }

    /**
     * Is the assignment past it's deadline
     */
    public function isClosed()
    {
        return ((new Carbon()) >= $this->deadline);//diffForHumans
    }

    /**
     * Is the assignment past it's deadline
     */
    public function isAllSubmitted()
    {
        return $this->submissions_count == $this->assigned_to_count;
    }

    /**
     * Is the assignment assigned to a student
     */
    public function isAssignedTo($student_id)
    {
        global $id;
        $id = $student_id;
        if ($this->all)
        {
            if ($this->to_groups)
                return $this->class()->whereHas('groups', function (Builder $query) {
                    $query->whereHas('students', function (Builder $query) {
                        global $id;
                        $query->where('id', $id);
                    });
                })->exists();
            else
                return $this->class()->whereHas('students', function (Builder $query) {
                    global $id;
                    $query->where('id', $id);
                })->exists();
        }
        else
        {
            if ($this->to_groups)
                return $this->groups()->whereHas('students', function (Builder $query) {
                    global $id;
                    $query->where('id', $id);
                })->exists();
            else
                return $this->students()->where('id', $id)->exists();
        }
    }

    /**
     * Get the assignment status for a student 
     */
    public function getStatus($user)
    {
        if ($user->isStudent())
        {
            if ($this->submissions_count > 0)
                return 'submitted';
            if ($this->isClosed())
                return 'closed';
            if ($this->deadline->diffInDays() <= 2)
                return 'near';
        }
        else if ($this->isAllSubmitted())
            return 'all submitted';
        return 'normal';
    }

    /**
     * The students that view the assignment
     */
    public function views()
    {
        return $this->morphToMany('App\Student', 'seen', 'views')->withPivot('seen_at');
    }

    public function getDates()
    {
        return array('created_at', 'updated_at', 'deadline');
    }
}
