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
        foreach ($exam->questions as $question) {
            $totalGrade += $question->mark;
            if ($request->get("$question->id") == $question->correctAnswer->id) {
                $pivoteTable->pivot->commulativeGrade += $question->mark;
            }
        }

        $pivoteTable->pivot->commulativeGrade = $pivoteTable->pivot->commulativeGrade / $totalGrade * 100;
        $pivoteTable->pivot->save();
        $student = User::find(user()->id);
        $percent = $pivoteTable->pivot->commulativeGrade;

        DB::update('update courses_student set updated_at = now() where student_id = ?', [user()->id]);
        return view('examResult')->with(compact('exam', 'student', 'percent'));
    }
    public function resetResult($id)
    {
        $course = DB::select('select * from courses_student where student_id = ?', [$id]);

        DB::update('update courses_student set updated_at=null, created_at=null, commulativeGrade=null where student_id=?', [$id]);
        return redirect("/courses/" . $course[0]->id);
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
        if (user()->id == $course->lec_id) {

            $exam = Exam::query()->where('course_id', $id);
            $exam->delete();
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
