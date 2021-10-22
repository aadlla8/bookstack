<?php

namespace BookStack\Http\Controllers;

use BookStack\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeStudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        return view('home');
    }

    public function homeStudent(Request $request)
    {
        $listDetails = [
            'search' => $request->get('search', ''),
        ];

        $courses = Courses::query();
        if ($request->get('search')) {
            $slg = str_replace('-', " ", Str::slug($request->get('search')));

            $courses = $courses->where('subject', 'like', "%" . $slg . "%")->orWhere('subject', 'like', "%" . $request->get('search') . "%");
        }
        $courses = $courses->orderBy('created_at', 'desc');
        return view('homeStudent')->with(["courses" => $courses->paginate(15), 'listDetails' => $listDetails]);
    }
    public function homeInstructor()
    {
        return view('homeInstructor');
    }

    //    public function addCourses()
    //    {
    //        return view('Courses.create');
    //    }

}
