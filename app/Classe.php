<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $table = 'classes';
    public $timestamps = true;
    protected $fillable = ['label', 'chef_id', 'image'];

    /**
     * Class has chef
     */
    public function chef()
    {
        return $this->belongsTo(Professor::class, 'chef_id', 'id');
    }

    /**
     * The professors of this class
     */
    public function professors()
    {
        return $this->belongsToMany(Professor::class, 'class_professor', 'class_id', 'professor_id');
    }

    /**
     * The students of this class
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'id');
    }

    /**
     * The class students that are not in pending list
     */
    public function activeStudents()
    {
        return $this->students()->whereHas('user', function (Builder $query){
            $query->where('status', 'membre');
        });
    }

    /**
     * The groups of this class
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'class_id', 'id');
    }

    /**
     * The groups ids of a student in this class
     */
    public function studentGroups($student_id)
    {
        global $id;
        $id = $student_id;
        $groups = $this->groups()->whereHas('students', function (Builder $query) {
            global $id;
            $query->where('id', $id);
        })->select('id', 'class_id')->get();
        $groups_ids = array();
        foreach($groups as $group)
            array_push($groups_ids, $group->id);
        return $groups_ids;
    }

    /**
     * The courses of this class
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'class_id', 'id');
    }

    /**
     * Class have many discussions
     */
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'class_id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * Class have many course resources
     */
    public function resources()
    {
        return $this->hasManyThrough('App\Resource', 'App\Course', 'class_id', 'course_id', 'id', 'id');
    }

    /**
     * Class have many course assignments
     */
    public function assignments()
    {
        return $this->hasManyThrough('App\Assignment', 'App\Course', 'class_id', 'course_id', 'id', 'id')->orderBy('id', 'DESC');
    }

    /**
     * The courses of a professor in this class
     */
    public function professorCourses($professor)
    {
        return $this->courses->where('professor_id', $professor);
    }

    /**
     * Is a professor given the chef of the class
     */
    public function isChef($professor_id)
    {
        return $this->chef_id == $professor_id;
    }
}
