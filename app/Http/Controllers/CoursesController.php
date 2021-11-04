<?php

namespace BookStack\Http\Controllers;

use Illuminate\Support\Facades\DB;
use BookStack\Exam;
use BookStack\Courses;
use BookStack\Student;
use BookStack\Videos;
use BookStack\Question;
use BookStack\Option;

use BookStack\Lecturers;
use Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use BookStack\Imports\ImportQuestionOption;
use Bookstack\QuestionImport;

use function GuzzleHttp\Promise\queue;

class CoursesController extends Controller
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
        // return view('Courses.create',['Lecturers'=>Lecturers::all()] );
        return view('Courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'Subject' => 'required',
            'level' => 'required|alpha',
            'cost' => 'required|max:1000|regex:/^[0-9]+(?:\.[0-9]{1,2})?$/',
            'NumberOfHours' => 'required|integer|min:1',
            // 'lectureID'=>'required',

        ]);

        $courses = new Courses();

        $courses->subject = $request->input('Subject');
        $courses->level = $request->input('level');
        $courses->cost = $request->input('cost');
        $courses->numOfHours = $request->input('NumberOfHours');
        $courses->lec_id = user()->id;
        $courses->coursePic = "default.jpg";
        $courses->description = "";
        $courses->save();
        return redirect('/homeStudent');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Courses::find($id);
        $videos = Videos::all()->where('course_id', $id);
        $enrolled = false;
        $examFinished = false;
        foreach ($course->students as $student) {
            if (!empty(user()) && $student->id == user()->id) {
                $enrolled = true;
                if (!empty($student->pivot->commulativeGrade)) {
                    $examFinished = true;
                }
                break;
            }
        }
        //dd($course->students[0]->pivot->commulativeGrade);
        return view("courseProfile")->with(compact('course', 'videos', 'enrolled', 'examFinished'));
    }

    public function enroll($id)
    {
        $course = Courses::find($id);
        $videos = Videos::all()->where('course_id', $id);
        $enrolled = false;

        $course->students()->attach(user()->id);

        $examFinished = false;
        foreach ($course->students as $student) {
            if (!empty(Session::get('frontSession')) && $student->id == user()->id) {
                $enrolled = true;
                if (!empty($student->pivot->commulativeGrade)) {
                    $examFinished = true;
                }
                break;
            }
        }
        return redirect('/courses/' . $id);
        // we should make the enrollment here
        //return view("courseProfile")->with(compact('course', 'videos','enrolled','examFinished'));
        //return view("enrollCourse")->with("course", $course);
    }

    //===========================================================================

    public function edit($id)
    {
        $course = Courses::find($id);
        return view('Courses.update')->with('course', $course);
    }

    //===========================================================================

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'Subject' => 'required',
            'level' => 'required|alpha',
            'cost' => 'required|max:1000|regex:/^[0-9]+(?:\.[0-9]{1,2})?$/',
            'NumberOfHours' => 'required|integer|min:1',

        ]);
        //        dd($request->input('coursePic'));
        $courses = Courses::find($id);
        $courses->subject = $request->input('Subject');
        $courses->level = $request->input('level');
        $courses->cost = $request->input('cost');
        $courses->numOfHours = $request->input('NumberOfHours');
        //------------------------------------
        if ($request->hasFile('coursePic')) {
            
            $picNameWithExt = $request->file('coursePic')->getClientOriginalName();
            $picName = pathinfo($picNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('coursePic')->getClientOriginalExtension();
            $picNameToStore = $picName . time() . "." . $extension;
            $request->file('coursePic')->move(base_path() . '/public/coursePic/', $picNameToStore);

            DB::table('question_import')->where('is_persistent','0')->delete();
            Excel::import(new ImportQuestionOption, base_path() . '/public/coursePic/' . $picNameToStore);
            $exam = Exam::all()->where("course_id", $courses->id)->first();

            if (!$exam || empty($exam)) {
                $exam = new Exam();
                $exam->title = "test exam of $courses->subject";
                $duration = 2 . ":" . 0;
                $exam->duration = $duration;
                $exam->course_id = $courses->id;
                $exam->save();
            } else {
                DB::table('exam')->where('id', '=', $exam->id)->delete();
                $exam = new Exam();
                $exam->title = "test exam of $courses->subject";
                $duration = 2 . ":" . 0;
                $exam->duration = $duration;
                $exam->course_id = $courses->id;
                $exam->save();
            }

            $questions = DB::table('question_import')->where('is_persistent',0)->get();
            foreach ($questions as $question) {
                # code...
                if ($question->title == "Title") continue;
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
        } else
            $picNameToStore = "default.jpg";
        $courses->coursePic =  "default.jpg";
        //------------------------------------
        $courses->save();
        return redirect('/courses/' . $id);

        //
    }

    //===========================================================================

    public function destroy($id)
    {

        $course = Courses::find($id);

        if (user()->id == $course->lec_id) {

            $dir = base_path() . '/public/courses/' . $course->subject . '_' . $course->id;

            if (is_dir($dir)) {
                $videos = scandir($dir);
                foreach ($videos as $video) {
                    if ($video != '.' && $video != '..') unlink($dir . '/' . $video);
                }
                reset($videos);
                rmdir($dir);
            }
            $course->delete();
        }
        return redirect('/homeStudent');
    }
    public function search()
    {
        $word = request('word');
        $courses = Courses::where('subject', 'like', "%$word%")->orWhere('cost', 'like', "%$word%")->get();
        return view('homeStudent', compact('courses'));
    }
}
