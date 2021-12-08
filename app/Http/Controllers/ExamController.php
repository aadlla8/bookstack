<?php

namespace BookStack\Http\Controllers;

use BookStack\Courses;
use BookStack\Exam;
use BookStack\User;
use BookStack\Option;
use BookStack\Question;
use Illuminate\Http\Request;
use Session;
use PDF;
use Illuminate\Support\Facades\DB;


class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("addExam");
    }
    public function editExam($id)
    {
        $course = Courses::find($id);
        $exam = Exam::where('course_id', $course->id)->first();

        Session::put("exam_id", $exam->id);
        Session::put("exam_title", $exam->title);
        $questions = Question::all()->where("exam_id", $exam->id);
        return view("addQuestions")->with('questions', $questions);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $exam = new Exam();
        $exam->title = $request->get('title');
        $duration = $request->get('h') . ":" . $request->get('m');
        $exam->duration = $duration;
        $exam->course_id = Session::get('courseId');
        $exam->save();

        Session::forget('courseId');
        Session::put("exam_id", $exam->id);
        Session::put("exam_title", $exam->title);

        return view("addQuestions");
    }

    public function storeQuestion(Request $request)
    {
        $question = new Question();
        $question->title = $request->get('title');
        $question->mark = $request->get('mark');
        $question->exam_id = Session::get('exam_id');

        $question->save();

        $options = ['A', 'B', 'C', 'D'];

        for ($i = 0; $i < count($options); $i++) {
            if ($request->exists("$options[$i]")) {
                $option = new Option();

                $option->value = $request->get("$options[$i]");
                $option->quest_id = $question->id;
                $option->save();

                if ($request->get('correct-ans') == $i) {
                    $question->correct_ans = $option->id;
                    $question->save();
                }
            } else {
                break;
            }
        }

        $questions = Question::all()->where("exam_id", Session::get('exam_id'));
        return view("addQuestions")->with('questions', $questions);
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);

        $options = Option::all()->where("quest_id", $id);

        foreach ($options as $option) {
            $option->delete();
        }
        $question->delete();

        $questions = Question::all()->where("exam_id", Session::get('exam_id'));
        return view("addQuestions")->with('questions', $questions);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = Exam::all()->where("course_id", $id);
        $data = $exam->first();
        DB::update('update courses_student set created_at = now() where student_id = ?', [user()->id]);
        return view('startExam')->with("exam", $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function showResult(Request $request, $id)
    {
        $exam = Exam::find($id);
        $pivoteTable = $exam->course->students->firstWhere('id', user()->id);

        $pivoteTable->pivot->commulativeGrade = 0.0;
        $totalGrade = 0;
        $totalMark = 0;
        $checkresults = array();
        $correctCount = 0;
        $fail_questions = "";
        $total_question = $exam->questions->count();
        foreach ($exam->questions as $question) {
            $totalGrade += $question->mark;
            $question->userchoose = $request->get("$question->id");
            if (empty($question->correctAnswer)) {
                if (!$request->get("$question->id")) {
                    $pivoteTable->pivot->commulativeGrade += $question->mark;
                    $correctCount++;
                    $totalMark += $question->mark;
                } else {
                    $fail_questions .= $question->id . ",";
                }
            } else  if ($request->get("$question->id") == $question->correctAnswer->id) {
                $pivoteTable->pivot->commulativeGrade += $question->mark;
                $correctCount++;
                $totalMark += $question->mark;
            } else {
                $fail_questions .= $question->id . ",";
            }
            $checkresults[] = $question;
        }
        $pivoteTable->pivot->fail_questions = $fail_questions;
        $pivoteTable->pivot->total_mark = $totalMark;
        $pivoteTable->pivot->commulativeGrade = $pivoteTable->pivot->commulativeGrade / $totalGrade * 100;
        $pivoteTable->pivot->save();
        $student = User::find(user()->id);
        $percent = $pivoteTable->pivot->commulativeGrade;

        $type = $exam->course->type; //1 trac nghiem, 2 on tap
        DB::update('update courses_student set updated_at = now() where student_id = ?', [user()->id]);
        return view('examResult')->with(compact('exam', 'student', 'percent', 'checkresults', 'correctCount', 'type', 'totalMark', 'totalGrade', 'fail_questions', 'total_question'));
    }
    public function resetResult($id, $stdid)
    {
        $course = DB::select('select * from courses_student where course_id=? and student_id = ?', [$id, $stdid]);
        if ($course) {
            $course = DB::select('select * from courses_student where course_id=? and student_id = ?', [$id, $stdid]);
            DB::update('update courses_student set updated_at=null, fail_questions=null, total_mark=0, created_at=null, commulativeGrade=null where course_id=? and student_id = ?', [$id, $stdid]);
        }

        return redirect("/courses/" . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Courses::find($id);
        if (userCan('settings-manage')) {

            $exam = Exam::query()->where('course_id', $id)->first();
            //$exam->delete();
            foreach ($exam->questions as $q) {
                $q->delete();
            }
        }
        return redirect("/courses/" . $id);
    }

    public function certificate($id)
    {
        $exam = Exam::find($id);
        $student = User::find(user()->id);

        $data = [
            'exam' => $exam,
            'student' => $student
        ];

        $pdf = PDF::loadView('certificate', $data);
        return $pdf->download('certification.pdf');
    }
}
