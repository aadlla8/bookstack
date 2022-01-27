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
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class ShowOnMenuController extends Controller
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
        $this->checkPermission('settings-manage');
        if (signedInUser() && userCan('settings-manage')) {
            return view('showonmenu.home');
        }
    }
}
