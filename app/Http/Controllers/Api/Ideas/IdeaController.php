<?php

namespace App\Http\Controllers\Api\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Idea;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $ideas = Idea::with(['department', 'category'])->where('user_id', '=', $request->user()->id)->paginate(5);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), $exception->getCode());
        }

        return response()->json($ideas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();

        try {
            // Delete comments
            DB::table('comments')->where('idea_id', '=', $id)->delete();
            // Delete idea like
           DB::table('idea_likes')->where('idea_id', '=', $id)->delete();
            // Delete ideas
            DB::table('ideas')->delete($id);

            DB::commit();
            // all good
        } catch (Exception $exception) {
            DB::rollBack();
            // Something error

            return response()->json($exception->getMessage(), Response::HTTP_NOT_ACCEPTABLE);
        }
        return response()->json("Delete idea success", Response::HTTP_ACCEPTED);
    }
}
