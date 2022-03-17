<?php

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Comments\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Idea;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Idea $idea)
    {
        try {
            $comments = Comment::with('user')->where('idea_id', $idea->id)->get();
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Idea $idea
     * @return JsonResponse
     */
    public function store(Request $request, Idea $idea)
    {
        try {
            Comment::create([
                'content' => $request->input('content'),
                'user_id' => $request->user()->id,
                'idea_id' => $idea->id,
            ]);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 409);
        }

        return response()->json('Post comment success', 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCommentRequest $request, $id)
    {
        try {
            $commentContent = $request->input('content');
            $comment = Comment::find($id);

            if ($comment->user_id === $request->user()->id) {
                $comment->update(['content' => $commentContent]);
                return response()->json('Update Comment Successfully', 202);
            } else {
                $message = "User don't have permission to delete this resources";
                if ($request->wantsJson()) {
                    return response()->json($message, 403);
                }
                throw new Exception($message);
            }
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $comment = Comment::find($id);
            // if comment belong to user has owner it, then deletes
            if ($comment->user_id === $request->user()->id) {
                $comment->delete(); // delete this comment
                return response()->json('Delete comment success', 202);
            } else {
                $message = "User don't have permission to delete this resources";
                return response()->json($message, 403);
            }
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
