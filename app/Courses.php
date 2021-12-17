<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Courses extends EloquentModel
{
    protected $table = 'course';
    protected $primaryKey = 'id';
    protected $fillable = ['subject', 'description', 'level', 'cost', 'numOfHours', 'lec_id', 'coursePic', 'start_date', 'end_date'];
    public function lecturer()
    {
        return $this->belongsTo('BookStack\User', 'lec_id');
    }
    public function videos()
    {
        return $this->hasMany('BookStack\Videos', 'course_id');
    }
    public function students()
    {
        //return $this->belongsToMany(Student::class, 'courses_student' ,'course_id')->withPivot('commulativeGrade');
        return $this->belongsToMany(User::class, 'courses_student', 'course_id', 'student_id')->withPivot('commulativeGrade', 'total_mark', 'fail_questions', 'created_at', 'updated_at');
    }
    public function descriptions()
    {
        return $this->hasMany('BookStack\Description', 'course_id');
    }
    public function exam()
    {
        return $this->hasOne('BookStack\Exam', 'course_id');
    }
    public function comments()
    {
        return $this->hasMany('BookStack\Comment', 'course_id');
    }
}
