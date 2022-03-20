<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    public function getIdeasNotHaveComment(): JsonResponse
    {
        // Get idea to have a comment
        try {
            $ideaHaveComments = DB::table('ideas')
                ->join('comments', 'ideas.id', '=', 'comments.idea_id')
                ->pluck('ideas.id')->toArray();

            $ideaNotHaveComments = DB::table('ideas')
                ->whereNotIn('id', $ideaHaveComments)
                ->select('id', 'title', 'content')
                ->paginate(5);
            return response()->json($ideaNotHaveComments);
        } catch (Exception $exception) {
            return response()->json($exception);
        }
    }
}
