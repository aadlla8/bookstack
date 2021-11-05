<?php

namespace BookStack\Http\Controllers;

use BookStack\QuestionImport;
use BookStack\Courses;
use BookStack\Exam;
use BookStack\Question;
use BookStack\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use BookStack\Imports\ImportQuestionOption;
use BookStack\Imports\ImportQuestionOptionPersistent;

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

        $topics = QuestionImport::where('is_persistent', 1)->select('topic')->distinct()->get();


        return view('review.choose-question', ['topics' => $topics]);
    }
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {

            $picNameWithExt = $request->file('file')->getClientOriginalName();
            $picName = pathinfo($picNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $picNameToStore = $picName . time() . "." . $extension;
            $request->file('file')->move(base_path() . '/public/coursePic/', $picNameToStore);

            DB::table('question_import')->where('is_persistent', '0')->delete();
            Excel::import(new ImportQuestionOptionPersistent, base_path() . '/public/coursePic/' . $picNameToStore);
        }

        return redirect('/review');
    }
    public function beginAnswerQuestion(Request $request)
    {

        $courses = new Courses();

        $courses->subject = "Bài ôn tập tạo bởi: " . user()->name . "- chủ đề: " . $request->input('topic');
        $courses->level = "Beginer";
        $courses->cost = 0;
        $courses->numOfHours = 2;
        $courses->lec_id = user()->id;
        $courses->coursePic = "default.jpg";
        $courses->description = "";
        $courses->type = 2; // review
        $courses->save();

        $courses->students()->attach(user()->id);

        $exam = new Exam();
        $exam->title = "Bài thi của $courses->subject";
        $duration = 2 . ":" . 0;
        $exam->duration = $duration;
        $exam->course_id = $courses->id;
        $exam->save();

        $limit = $request->get('noQuestion');
        $questions = QuestionImport::where('is_persistent', 1)->where('topic', $request->input('topic'))->get();
        if ($limit > 0) {
            $questions = QuestionImport::where('is_persistent', 1)->where('topic', $request->input('topic'))->limit($limit)->get();
        }

        foreach ($questions as $question) {
            # code...

            $q = new Question();
            $q->title = $question->question;
            $q->mark = 1;
            $q->exam_id = $exam->id;
            $q->save();

            $options = ['A', 'B', 'C', 'D'];

            for ($i = 1; $i < 5; $i++) {
                $ptyn = "option$i";

                if ($question->$ptyn && !empty($question->$ptyn)) {
                    $option = new Option();
                    $option->value = $question->$ptyn;
                    $option->quest_id = $q->id;
                    $option->save();

                    if (strtolower($options[$i - 1]) == strtolower($question->correct_ans)) {
                        $q->correct_ans = $option->id;
                        $q->save();
                    }
                } else {
                    break;
                }
            }
        }

        return redirect('/startExam/' . $courses->id);
    }
}
