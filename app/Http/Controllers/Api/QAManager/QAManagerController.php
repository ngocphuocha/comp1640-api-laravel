<?php

namespace App\Http\Controllers\Api\QAManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Permission;

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
                    'description' => $request->input('description')
                ]
            );
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 409);
        }

        // if success
        return response()->json('Create new category success', '201');
    }

    /**
     * Delete category from resource
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory($id)
    {
        try {
            DB::table('categories')->delete($id);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
        return response()->json('Delete category success', '200');
    }

    /**
     * Update user permission vie method sync permission
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserPermissions($id, Request $request)
    {
        // TODO: Nhớ làm cái này bên frontend là multiple select option
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
