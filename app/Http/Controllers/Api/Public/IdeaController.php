<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Idea;
use App\Models\IdeaLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display the all resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $ideas = Idea::where('is_hidden', '=', false)->paginate(5);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($ideas, 200);
    }


    /**
     * @return IdeaLike[]|\Illuminate\Database\Eloquent\Collection
     */
    public function demo()
    {
        $idealike = IdeaLike::all();
        return $idealike;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return response()->json(Idea::findOrFail($id), 200);
        } catch (\Exception) {
            return response()->json('Resource not found', 404);
        }
    }

    public function downloadIdeaAsPDF($id)
    {
        try {
            $idea = Idea::find($id);
            $fileID = $idea->file_id;
            $file = File::find($fileID);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }

        return Storage::download("ideas/$file->name");
    }


    /**
     * Check idea's like is exist in resouces
     *
     * @param $id
     * @param Request $request
     * @return false|IdeaLike[]|\Illuminate\Database\Eloquent\Collection false if resource not found, otherwise instance idea's like
     */
    public function checkLikeIdeaIsExist($id, Request $request)
    {
        $ideaLike = IdeaLike::where('user_id', $request->user()->id)
            ->where('idea_id', $id)
            ->first();

        if (is_null($ideaLike)) {
            return false;
        }
        return $ideaLike;
    }

    /**
     * Store a like idea in idea_likes table
     * @param Idea $idea
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeIdea(Idea $idea, Request $request)
    {
        try {
            if ($this->checkLikeIdeaIsExist($idea->id, $request) === false) {
                IdeaLike::create([
                    'idea_id' => $idea->id,
                    'user_id' => $request->user()->id,
                ]);
            } else {
                return response()->json("You've liked this idea before", 202);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }

        return response()->json('Like success', 201);
    }

    /**
     * Delete idea like of idea
     *
     * @param Idea $idea <p>
     * idea instance of Idea model
     * </p>
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlikeIdea(Idea $idea, Request $request)
    {
        try {
            $result = $this->checkLikeIdeaIsExist($idea->id, $request);
            if ($result !== false) {
                $result->delete();
            } else {
                return response()->json('Idea like not found', 404);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 409);
        }

        return response()->json('Unlike idea success', 202);
    }

    /**
     * Get total like belong to idea tabls
     *
     * @param Idea $idea
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTotalLikeOfIdea(Idea $idea)
    {
        try {
            $ideaLikeCount = DB::table('idea_likes')->where('idea_id', $idea->id)->count();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($ideaLikeCount, 200);
    }


}
