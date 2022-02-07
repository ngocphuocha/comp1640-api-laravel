<?php

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Idea $idea)
    {
        try {
            $comments = Comment::with('user')->where('idea_id', $idea->id)->get();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 404);
        }
        return response()->json($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Idea $idea)
    {
        try {
            Comment::create([
                'content' => $request->input('content'),
                'user_id' => $request->user()->id,
                'idea_id' => $idea->id,
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 409);
        }

        return response()->json('Post comment success', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
