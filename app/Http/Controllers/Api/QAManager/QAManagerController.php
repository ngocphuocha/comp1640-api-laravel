<?php

namespace App\Http\Controllers\Api\QAManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QA_Manager\StoreCategoryRequest;
use App\Http\Requests\Api\QA_Manager\UpdateCategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class QAManagerController extends Controller
{
    /**
     * Create new categories in resource
     *
     * @param StoreCategoryRequest $request
     * @return JsonResponse
     */
    public function createNewCategory(StoreCategoryRequest $request)
    {
        try {
            $categoryId = DB::table('categories')->insertGetId(
                [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['categoryId' => $categoryId], Response::HTTP_CREATED); // Success response
    }

    /**
     * Update category from resource
     *
     * @param $id
     * @return JsonResponse
     */
    public function updateCategory($id, UpdateCategoryRequest $request): JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                throw new Exception("Category not found");
            }

            $category->update($request->only(['name', 'description']));
            return response()->json('Update category success', Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete category from resource
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteCategory($id): JsonResponse
    {
        try {
            if (is_null(DB::table('categories')->find($id))) {
                throw new Exception("This category not found", Response::HTTP_NOT_FOUND);
            }

            $category = DB::table('categories')
                ->join('ideas', 'categories.id', '=', 'ideas.category_id')
                ->where('categories.id', '=', $id)
                ->get();

            if ($category->count() > 0) {
                throw new Exception("This category can't delete because it have used by staff", Response::HTTP_NOT_ACCEPTABLE);
            } else {
                DB::table('categories')->delete($id);
            }

            return response()->json('Delete category success', Response::HTTP_ACCEPTED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
