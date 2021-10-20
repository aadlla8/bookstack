<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'id';
    protected $fillable = ['comment','user_id', 'course_id', 'video_id'];
    public function user()
    {
        return $this->belongsTo('BookStack\User','user_id');
    }
    public function course()
    {
        return $this->belongsTo('BookStack\Courses','course_id');
    }
    public function video()
    {
        return $this->belongsTo('BookStack\Videos','video_id');
    }
}
