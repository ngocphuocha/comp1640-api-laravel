<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SuperAdmin\CreateUserRequest;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    /**
     * Get list users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getListUsers(Request $request): JsonResponse
    {
        // Get admin role id
        $adminRoleId = Role::findByName('super admin', 'web')->id;

        // Get list with query role id, but not include Admin role
        if ($request->has('roleId')) {
            $listOfId = DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('role_id', '!=', $adminRoleId) //make sure prevent admin role
                ->where('role_id', $request->input('roleId'))
                ->pluck('model_id');
        } else {
            // Default return all users except admin role
            $listOfId = DB::table('model_has_roles')
                ->where('model_type', 'App\Models\User')
                ->where('role_id', '!=', $adminRoleId)
                ->pluck('model_id');
        }

        try {
            $users = User::with('department')->whereIn('id', $listOfId)->orderBy('id', 'desc')->paginate(5);
            return response()->json($users);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Get detail info of users
     *
     * @param $id
     * @return JsonResponse
     */
    public function getUsersInfo($id): JsonResponse
    {
        $adminRoleId = Role::findByName('super admin', 'web')->id; // Get admin role id

        try {
            $user = User::with(['roles', 'permissions', 'profile', 'department'])
                ->join('model_has_roles', function ($join) use ($adminRoleId) {
                    $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.role_id', '!=', $adminRoleId);
                })
                ->find($id);

            if (is_null($user)) {
                return response()->json('User not found', 404);
            }

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Admin create new user for system
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function createUser(CreateUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $fields = $request->only(['email', 'password', 'department_id']); // Get only email and password
            $fields['password'] = bcrypt($fields['password']); // hash password

            $role_id = $request->input('role_id'); //get role id field
            $permissions = Role::findById($role_id, 'web')->permissions; // get all permissions with role id

            $dataPermissions = [];

            // Get only permission name
            foreach ($permissions->toArray() as $key => $val) {
                $dataPermissions[$key] = $val['name'];
            }

            $userProfile = $request->only(['name', 'gender', 'phone_number', 'address']);

            $user = User::create($fields); // create user
            $user->assignRole($role_id); // assign role for user
            $user->givePermissionTo($dataPermissions); // give array permissions to this user
            $user->profile()->create($userProfile); // create profile's user

            DB::commit();

            $message = 'Register successfully';
            return response()->json(['success' => $message], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }


    /**
     * Get all role of user not include admin role
     *
     * @return JsonResponse
     */
    public function getRoles(): JsonResponse
    {
        try {
            $roles = Role::whereNotIn('name', ['super admin'])->get();
            return response()->json($roles);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    /**
     * Get all the department
     *
     * @return JsonResponse
     */
    public function getDepartments(): JsonResponse
    {
        try {
            $departments = Department::all();
            return response()->json($departments);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }
}
