<?php

namespace BookStack;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'question_option';
    protected $primaryKey = 'id';
    protected $fillable = ['value', 'quest_id'];
    public function question()
    {
        return $this->belongsTo('BookStack\Question','quest_id');
    }
    public function answerOf()
    {
        return $this->hasOne('BookStack\Question','correct_ans');
    }
}
