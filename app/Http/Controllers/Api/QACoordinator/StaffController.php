<?php

namespace App\Http\Controllers\Api\QACoordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

class StaffController extends Controller
{
    /**
     * @param Request $request
     * @param $listOfId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchUser(Request $request, $listOfId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $staffs = User::with('department')->whereIn('id', $listOfId)
            ->where('email', 'like', "%" . $request->query('email') . "%")
            ->paginate(5);
        return $staffs->appends(['email' => $request->query('email')]);
    }


    public function getStaffUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        $staffRoleId = Role::findByName('staff', 'web')->id;
        $listOfId = DB::table('model_has_roles')
            ->where('model_type', 'App\Models\User')
            ->where('role_id', $staffRoleId)
            ->pluck('model_id');
        try {
            if (!is_null($request->query('email'))) {
                $staffs = $this->searchUser($request, $listOfId);
            } else {
                $staffs = User::with('department')->whereIn('id', $listOfId)->paginate(5);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
        return response()->json($staffs, 200);
    }

    public function getAllStaffPermission()
    {
        $roleId = Role::findByName('staff', 'web')->id; // staff role id
        $permissions = DB::table('role_has_permissions')
            ->join('permissions', function ($join) use ($roleId) {
                $join->on('role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $roleId);
            })
            ->get();
        return $permissions;
    }

    public function getStaffUserDetail($id): \Illuminate\Http\JsonResponse
    {
        try {
            $permissions = $this->getAllStaffPermission();

            // Get role id of staff
            $staffRoleId = Role::findByName('staff', 'web')->id;
            $staff = User::with(['permissions', 'department', 'profile'])
                ->join('model_has_roles', function ($join) use ($staffRoleId) {
                    $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.role_id', '=', $staffRoleId);
                })->where('id', $id)->first();

            if (is_null($staff)) {
                return response()->json('User not found', ResponseStatus::HTTP_NOT_FOUND);
            }
        } catch (\Exception $exception) {
            return response()->json($exception);
        }
        $response = [
            'staff' => $staff,
            'permissions' => $permissions
        ];
        return response()->json($response, ResponseStatus::HTTP_OK);
    }

    public function givePermission($id, Request $request): \Illuminate\Http\JsonResponse
    {
        // TODO: Nhớ làm cái này bên frontend là multiple select option, chỉ lấy mấy thằng staff của mỗi department
        // Get list permission from request of user
        try {
            $permissions = $request->input('permissions');
//            dd($permissions);
            $staff = User::findOrFail($id); // if fail return response 404
            $staff->syncPermissions($permissions); // keep array permission from request
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
        return response()->json('Update permission success', ResponseStatus::HTTP_ACCEPTED);
    }
}
