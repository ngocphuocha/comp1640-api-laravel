<?php

namespace App\Http\Controllers\Api\Ideas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Idea\UpdateIdeaRequest;
use App\Models\Idea;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    /**
     * Display a listing of idea
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $ideas = Idea::with(['department', 'category'])->where('user_id', '=', $request->user()->id)->paginate(5);
            return response()->json($ideas);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateIdeaRequest $request, int $id): JsonResponse
    {
        try {
            $idea = DB::table('ideas')->where('id', '=', $id)->first();

            if (is_null($idea)) {
                throw new Exception('Idea not found', 404);
            }

            // else then update this idea
            DB::table('ideas')->where('id', '=', $id)->update($request->only(['title', 'content', 'category_id']));
            return response()->json('Update category success', 202);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
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
            return response()->json('Change current password successfully', 200); // all good
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(), 500); // something error
        }
    }
}
