<?php

namespace BookStack\Http\Controllers;

use BookStack\QuestionImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        return view('review.home');
    }
    public function chooseQuestion()
    {
        $topics = QuestionImport::where('is_persistent',1)->select('topic')->distinct()->get();
        

        return view('review.choose-question',['topics'=>$topics]);
    }

    public function beginAnswerQuestion(Request $request)
    {

    }
}
