<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        // check email
        $user = User::where('email', $request->email)->first();

        // check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json('Email or password is not correct!', 401);
        }
        $token = $user->createToken('myToken')->plainTextToken;
        $response = [
            'token' => $token,
            'user' => $user,
        ];
        return response()->json($response, 201);

    }
}
