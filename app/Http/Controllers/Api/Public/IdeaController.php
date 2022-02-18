<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\IdeaLike;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

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
            $ideas = Idea::with(['category', 'department'])->where('is_hidden', '=', false)
                ->orderBy('id', 'desc')->paginate(5);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($ideas, 200);
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
            $idea = Idea::with(['user', 'department'])->where('id', '=', $id)->first();
            if (is_null($idea)) {
                throw new \Exception('Idea not found', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
        return response()->json($idea, Response::HTTP_OK);
    }


    /**
     * Dowload pdf file of idea
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadIdeaAsPDF($id, Mpdf $mpdf)
    {
        try {
            $idea = Idea::find($id);

            // if idea not null then download
            if (!is_null($idea)) {
                $mpdf->WriteHTML($idea->content);
                $mpdf->Output($idea->title, 'D');
            } else {
                throw new \Exception('Idea not found', 404);
            }

        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Store a like idea in idea_likes table
     * @param Idea $idea
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeIdea(Request $request, Idea $idea)
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
     * Check idea's like is exist in resouces
     *
     * @param $id
     * @param Request $request
     * @return false|IdeaLike[]|\Illuminate\Database\Eloquent\Collection false if resource not found, otherwise instance idea's like
     */
    public function checkLikeIdeaIsExist($id, Request $request)
    {
        // Get like of user where idea = idea id
        $ideaLike = IdeaLike::where('user_id', $request->user()->id)->where('idea_id', $id)->first();

        if (is_null($ideaLike)) {
            return false;
        }
        return $ideaLike;
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

    /**
     * Check idea is liked
     *
     * @param $ideaId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIsExistLike($ideaId, Request $request)
    {
        $result = $this->checkLikeIdeaIsExist($ideaId, $request);

        try {
            if ($result === false) {
                return response()->json(['isExist' => false], Response::HTTP_OK);
            } else {
                return response()->json(['isExist' => true], Response::HTTP_OK);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }

    }
}
