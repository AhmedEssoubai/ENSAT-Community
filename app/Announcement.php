<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $fillable = ['title', 'content', 'professor_id'];

    /**
     * The announcement classes
     */
    public function classes()
    {
        return $this->belongsToMany('App\Classe', 'announcement_class', 'announcement_id', 'class_id')->take(5);
    }

    /**
     * The announcement publisher
     */
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }
}
