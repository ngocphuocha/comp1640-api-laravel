<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\IdeaLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class IdeaController extends Controller
{
    /**
     * Display the all resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if (!is_null($request->query('title'))) {
                $ideas = $this->searchIdea($request);
            } else {
                $ideas = Idea::with(['category', 'department'])->where('is_hidden', '=', false)
                    ->orderBy('id', 'desc')->paginate(5);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($ideas, 200);
    }

    /**
     *  Search idea is not hidden
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    protected function searchIdea(Request $request)
    {
        $ideas = Idea::with(['department', 'category'])->where('is_hidden', '=', false)->where('title', 'like', '%' . $request->query('title') . '%')->paginate(5);
        $ideas = $ideas->appends(['title' => $request->query('title')]);
        return $ideas;
    }

    /**
     * Get all hidden ideas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHiddenIdeas(Request $request)
    {
        try {
            if (!is_null($request->query('title'))) {
                $ideas = $this->searchHiddenIdea($request);
            } else {
                $ideas = Idea::with(['category', 'department'])->where('is_hidden', '=', true)
                    ->orderBy('id', 'desc')->paginate(5);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($ideas, 200);
    }

    /**
     * Search Idea is hidden
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    protected function searchHiddenIdea(Request $request)
    {
        $ideas = Idea::with(['department', 'category'])->where('is_hidden', '=', true)->where('title', 'like', '%' . $request->query('title') . '%')->paginate(5);
        $ideas = $ideas->appends(['title' => $request->query('title')]);
        return $ideas;
    }


    /**
     * Get the detail idea
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $idea = Idea::with(['user', 'department'])->where('id', '=', $id)->first();

            if (is_null($idea)) {
                return response()->json('Idea not found', 404);
            }

            return response()->json($idea);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
                $fileName = $idea->title . ".pdf";
                $mpdf->WriteHTML($idea->content);
                $mpdf->Output($fileName, 'D');
            } else {
                return response()->json('Idea not found', 404);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
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
     * Get total like of the idea detail
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
                return response()->json(['isExist' => false]);
            } else {
                return response()->json(['isExist' => true]);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
