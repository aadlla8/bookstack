<?php

namespace BookStack\Http\Controllers;

use BookStack\Courses;
use Illuminate\Http\Request;


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

    public function homeStudent()
    {
        $courses = Courses::query()->orderBy('created_at', 'desc')->get();
        return view('homeStudent')->with("courses", $courses);
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
