<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SuperAdmin\CreateUserRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    /**
     * Get list users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListUsers(Request $request)
    {
        $adminRoleId = Role::findByName('super admin', 'web')->id;

        // Get list with query role id, but not included Admin role
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
            $user = User::with('department')->whereIn('id', $listOfId)->paginate(5);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Get detail info of users
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersInfo($id): \Illuminate\Http\JsonResponse
    {
        $adminRoleId = Role::findByName('super admin', 'web')->id; // Get admin role id

        try {
            $user = User::with(['roles', 'permissions', 'profile', 'department'])
                ->join('model_has_roles', function ($join) use ($adminRoleId) {
                    $join->on('users.id', '=', 'model_has_roles.model_id')
                        ->where('model_has_roles.role_id', '!=', $adminRoleId);
                })
                ->findOrFail($id);
//            dd($user);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Admin create new user for system
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(CreateUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $fields = $request->only(['email', 'password', 'department_id']); // Get only email and password
            $fields['password'] = bcrypt($fields['password']); // hash password

            $role_id = $request->input('role_id'); //get role id field
            $permissions = Role::findById($role_id, 'web')->permissions; // get all persmissions with role id

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
        } catch (\Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
        }

        return response()->json($message, 201);
    }


    public function getRoles()
    {
        try {
            $roles = Role::whereNotIn('name', ['super admin'])->get();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json($roles, 200);
    }

    public function getDepartments(): \Illuminate\Http\JsonResponse
    {
        try {
            $departments = Department::all();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json($departments, 200);
    }
}
