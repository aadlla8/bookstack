<?php

namespace BookStack\Http\Controllers;

use BookStack\Courses;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use BookStack\Imports\ImportQuestionOption;
use BookStack\Imports\ImportQuestionOptionPersistent;
use Bookstack\QuestionImport;

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
}
