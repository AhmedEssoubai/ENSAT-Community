<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    protected $table = 'professors';
    public $timestamps = true;
    protected $fillable = ['user_id'];

    /**
     * The classes the professor is a chef on
     */
    public function managed_classes()
    {
        return $this->hasMany(Classe::class, 'chef_id', 'id');
    }

    /**
     * The user account of the professor
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
     * The classes of this professor
     */
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'class_professor', 'professor_id', 'class_id');
    }

    /**
     * The courses of this professor
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
