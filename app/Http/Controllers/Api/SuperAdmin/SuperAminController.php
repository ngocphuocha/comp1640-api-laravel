<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SuperAdmin\CreateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class SuperAminController extends Controller
{
    //TODO make super admin middleware check is super admin
    public function createUser(CreateUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $fields = $request->only(['name', 'email', 'password']);
            $fields['password'] = bcrypt($fields['password']);

            $userProfile = $request->only(['phone', 'address']);

            $user = User::create($fields);
            $user->profile()->create($userProfile);

            DB::commit();

            $message = 'Register successfully';
            return response()->json(['success' => $message], 201);
        } catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
        }

        return response($message);
    }
}
