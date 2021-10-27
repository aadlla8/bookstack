<?php

namespace BookStack\Http\Controllers\Api;

use BookStack\Actions\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\queue;

class ActivityApiController extends ApiController
{
    public function list(Request $request)
    {
        $last = $request->get('last');
        if (empty($last)) $last = 0;

        $activities = Activity::with([
            'entity' => function ($query) {
                $query->withTrashed();
            },
            'user',
        ])->where('id', '>', $last)
            ->where(function ($query) {
                $query->orWhere('entity_type', 'BookStack\\Book')
                    ->orWhere('entity_type', 'BookStack\\Bookshelf')
                    ->orWhere('entity_type', 'BookStack\\Page')
                    ->orWhere('entity_type', 'BookStack\\Chapter');
            })->orderBy('updated_at', 'desc')->limit(1);
        return response()->json($activities->get());
    }
}
