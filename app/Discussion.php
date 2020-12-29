<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Discussion extends Model
{
    protected $table = 'discussions';
    //public $timestamps = false;
    protected $fillable = ['title', 'content', 'image', 'user_id', 'class_id', 'course_id'];

    /**
     * The class the discussion have
     */
    public function class()
    {
        return $this->belongsTo(Classe::class, 'class_id', 'id');
    }

    /**
     * The course the discussion have
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * The post can be published by one user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorited_users()
    {
        return $this->belongsToMany(User::class, 'favorises', 'discussion_id', 'user_id');
    }

    public function bookmarked_users()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'discussion_id', 'user_id');
    }

    public function is_liked()
    {
        return $this->favorited_users->contains(Auth::id());
    }

    public function is_bookmarked()
    {
        return $this->bookmarked_users->contains(Auth::id());
    }
}
