<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exam';
    protected $primaryKey = 'id';
    protected $fillable = ['duration', 'course_id'];
    public function course()
    {
        return $this->belongsTo('BookStack\Courses','course_id');
    }
    public function questions()
    {
        return $this->hasMany('BookStack\Question', 'exam_id');
    }
}
