<?php

namespace App\Http\Controllers\Api\QAManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QA_Manager\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class QAManagerController extends Controller
{
    /**
     * Create new categories in resource
     *
     * @param \App\Http\Requests\Api\QA_Manager\StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewCategory(\App\Http\Requests\Api\QA_Manager\StoreCategoryRequest $request)
    {
        try {
            DB::table('categories')->insert(
                [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 409);
        }

        // if success
        return response()->json('Create new category success', '201');
    }

    /**
     * Update category from resource
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCategory($id, UpdateCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                throw new \Exception("Category not found");
            }

            $category->update($request->only(['name', 'description']));
            return response()->json('Update category success', Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Delete category from resource
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory($id): \Illuminate\Http\JsonResponse
    {
        try {
            if (is_null(DB::table('categories')->find($id))) {
                throw new \Exception("This category not found", Response::HTTP_NOT_FOUND);
            }

            $category = DB::table('categories')
                ->join('ideas', 'categories.id', '=', 'ideas.category_id')
                ->where('categories.id', '=', $id)
                ->get();

            if ($category->count() > 0) {
                throw new \Exception("This category can't delete because it have used by idea", Response::HTTP_NOT_ACCEPTABLE);
            } else {
                DB::table('categories')->delete($id);
            }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
        return response()->json('Delete category success', Response::HTTP_ACCEPTED);
    }


    /**
     * Update user permission vie method sync permission
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserPermissions($id, Request $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Nhớ làm cái này bên frontend là multiple select option, chỉ lấy mấy thằng staff và qa của mỗi department
        // Get list permission from request of user
        $permissions = DB::table('permissions')
            ->whereIn('id', [2])
            ->select('name')
            ->get();

        $data = [];
        foreach ($permissions as $key => $value) {
            $data[$key] = $value->name;
        }

        try {
            $user = User::findOrFail($id);
            $user->syncPermissions($data); // keep array permission from request
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Update permission success', 200);
    }


}
