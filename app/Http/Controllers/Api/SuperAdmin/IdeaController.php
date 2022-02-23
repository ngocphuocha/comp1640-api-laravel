<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    public function getIdeasNotHaveComment()
    {
        // Get idea have comment
        try {
            $ideaHaveComments = DB::table('ideas')
                ->join('comments', 'ideas.id', '=', 'comments.idea_id')
                ->pluck('ideas.id')->toArray();

            $ideaNotHaveComments = DB::table('ideas')
                ->whereNotIn('id', $ideaHaveComments)
                ->select('id', 'title', 'content')
                ->paginate(5);

        } catch (Exception $exception) {
            return response()->json($exception);
        }

        return response()->json($ideaNotHaveComments, 200);
    }

}
