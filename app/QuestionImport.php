<?php

namespace Bookstack;

use BookStack\Question;
use BookStack\Option;

use Illuminate\Database\Eloquent\Model;

class QuestionImport extends Model
{
    protected $table = 'question_import';
    protected $primaryKey = 'id';
    protected $fillable = ['stt', 'topic', 'title', 'question', 'correct_ans', 'option1', 'option2', 'option3', 'option4'];

    
}
