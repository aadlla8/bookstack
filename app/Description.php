<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $table = 'description';
    protected $primaryKey = 'id';
    protected $fillable = ['description','course_id'];
    public function course()
    {
        return $this->belongsTo('BookStack\Courses','course_id');
    }
}
