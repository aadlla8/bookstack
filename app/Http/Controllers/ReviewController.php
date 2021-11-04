<?php

namespace BookStack\Http\Controllers;

use BookStack\Courses;
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
    public function store(Request $request)
    {
    }
}
