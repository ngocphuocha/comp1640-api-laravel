<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SuperAdmin\CreateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SuperAminController extends Controller
{
    //TODO make super admin middleware check is super admin
    public function createUser(CreateUserRequest $request)
    {
//        dd('123');
        DB::beginTransaction();

        try {
            $fields = $request->only(['email', 'password']); // Get only email and password
            $fields['password'] = bcrypt($fields['password']); // hash password

            $role_id = $request->input('role_id'); //get role id field
            $permissions = Role::findById($role_id, 'web')->permissions; // get all persmissions with role id

            $dataPermisstions = [];

            // Get only permission name
            foreach ($permissions->toArray() as $key => $val) {
            $dataPermisstions[$key] = $val['name'];
            }

            $userProfile = $request->only(['name', 'gender', 'phone_number', 'address']);

            $user = User::create($fields); // create user
            $user->assignRole($role_id); // assign role for user
            $user->givePermissionTo($dataPermisstions); // give array permissions to this user
            $user->profile()->create($userProfile); // create profile's user

            DB::commit();

            $message = 'Register successfully';
            return response()->json(['success' => $message], 201);
        } catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
        }

        return response()->json($message, 201);
    }
}
