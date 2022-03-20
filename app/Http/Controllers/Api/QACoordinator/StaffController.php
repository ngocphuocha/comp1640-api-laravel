<?php

namespace App\Http\Controllers\Api\QACoordinator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

class StaffController extends Controller
{
    public function getStaffUsers(Request $request): JsonResponse
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
            return response()->json($staffs, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * @param Request $request
     * @param $listOfId
     * @return LengthAwarePaginator
     */
    public function searchUser(Request $request, $listOfId): LengthAwarePaginator
    {
        $staffs = User::with('department')->whereIn('id', $listOfId)
            ->where('email', 'like', "%" . $request->query('email') . "%")
            ->paginate(5);
        return $staffs->appends(['email' => $request->query('email')]);
    }

    public function getStaffUserDetail($id): JsonResponse
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
            $response = [
                'staff' => $staff,
                'permissions' => $permissions
            ];
            return response()->json($response, ResponseStatus::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json($exception);
        }
    }

    /**
     * Get all staff permission
     *
     * @return Collection
     */
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

    public function givePermission($id, Request $request): JsonResponse
    {
        // Get list permission from request of user
        try {
            $permissions = $request->input('permissions');
            $staff = User::findOrFail($id); // if fail return response 404
            $staff->syncPermissions($permissions); // keep array permission from request
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
        return response()->json('Update permission success', ResponseStatus::HTTP_ACCEPTED);
    }
}
