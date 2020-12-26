<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    public $timestamps = false;
    protected $fillable = ['id', 'url', 'name', 'container_id', 'container_type'];

    /**
    *   The file container
    */
    public function container()
    {
        return $this->morphTo();
    }

    /**
     * The students that view the resource
     */
    public function views()
    {
        return $this->morphToMany('App\Student', 'seen', 'views')->withPivot('seen_at');
    }

    /**
     * Get file name
     */
    /*public function file_name()
    {
        $parts = explode("/", $this->url);
        return $parts[count($parts) - 1];
    }*/
}
