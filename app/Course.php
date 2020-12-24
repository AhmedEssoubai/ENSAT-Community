<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    public $timestamps = true;
    protected $fillable = ['title', 'short_title', 'description', 'color', 'class_id', 'professor_id'];

    /**
     * The class of the cours
     */
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id', 'id');
    }

    /**
     * The professor of the cours
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * Course have many resources
     */
    public function resources()
    {
        return $this->hasMany(Resource::class)->orderBy('id', 'DESC');
    }

    /**
     * Course have many resources
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class)->orderBy('id', 'DESC');
    }
}
