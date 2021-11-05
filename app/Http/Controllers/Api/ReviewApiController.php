<?php

namespace BookStack\Http\Controllers\Api;

use BookStack\QuestionImport;
use BookStack\Entities\Repos\BookRepo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewApiController extends ApiController
{
    protected $rules = [
        'create' => [
            'title'        => 'required|string|max:255',
            'topic'        => 'required|string|max:255',
            'question'     => 'string',
            'correct_ans'  => 'string',
            'option1'      => 'string',
            'option2'      => 'string',
            'option3'      => 'string',
            'option4'      => 'string',
        ],
        'update' => [
            'title'        => 'required|string|max:255',
            'topic'        => 'required|string|max:255',
            'question'     => 'string',
            'correct_ans'  => 'string',
            'option1'      => 'string',
            'option2'      => 'string',
            'option3'      => 'string',
            'option4'      => 'string',
        ],
    ];

    public function count(Request $request)
    {
        return response()->json(QuestionImport::where('is_persistent', 1)->where('topic', '=', $request->get('topic'))->count());
    }

    public function list()
    {
        $books = QuestionImport::where('is_persistent', 1);

        return $this->apiListingResponse(
            $books,
            ['id', 'stt', 'topic', 'title', 'question', 'correct_ans', 'option1', 'option2', 'option3', 'option4', 'is_persistent']
        );
    }
    public function create(Request $request)
    {
        $q = new QuestionImport();
        try {
            $requestData = $this->validate($request, $this->rules['create']);
            $q->title = $requestData['title'];
            $q->topic = $requestData['topic'];
            $q->question = $requestData['question'];
            $q->correct_ans = $requestData['correct_ans'];
            $q->option1 = $requestData['option1'];
            $q->option2 = $requestData['option2'];
            $q->option3 = $requestData['option3'];
            $q->option4 = $requestData['option4'];
            $q->is_persistent = 1;
            $q->save();
        } catch (Exception $ex) {
            return response($ex->getMessage(), 200);
        }

        return response()->json($q);
    }

    public function update(Request $request, string $id)
    {
        $q = QuestionImport::where('id', $id)->first();
        if (empty($q)) {
            return response('Not found', 401);
        }
        try {
            $requestData = $this->validate($request, $this->rules['update']);
            $q->title = $requestData['title'];
            $q->topic = $requestData['topic'];
            $q->question = $requestData['question'];
            $q->correct_ans = $requestData['correct_ans'];
            $q->option1 = $requestData['option1'];
            $q->option2 = $requestData['option2'];
            $q->option3 = $requestData['option3'];
            $q->option4 = $requestData['option4'];
            $q->is_persistent = 1;
            $q->update();
        } catch (Exception $ex) {
            //throw $th;
            return response($ex->getMessage(), 500);
        }
        return response()->json($q);
    }
    public function delete(string $id)
    {
        QuestionImport::where('id', ($id))->delete();

        return response('', 204);
    }
}
