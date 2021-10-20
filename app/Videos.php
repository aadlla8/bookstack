<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    protected $table = "videos";
    protected $primaryKey = "id";
    protected $fillable = ['name', 'video', 'extension', 'course_id'];
    public function course(){
        return $this->belongsTo('BookStack\Courses', 'course_id');
    }
    public function comments()
    {
        return $this->hasMany('BookStack\Comment','video_id');
    }
}
