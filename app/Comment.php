<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    //public $timestamps = false;
    protected $fillable = ['content', 'discussion_id', 'user_id'];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function can_delete(User $user)
    {
        return $user->id == $this->user_id;
    }

    public function can_edit(User $user)
    {
        return $user->id == $this->user_id;
    }
}
