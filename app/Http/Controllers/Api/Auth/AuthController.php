<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            // check email
            $user = User::where('email', $request->input('email'))->first();

            // check password
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json('Email or password is not correct!', 401);
            }
            $token = $user->createToken('myToken')->plainTextToken;
            $response = [
                'token' => $token,
                'user' => $user,
            ];
            return response()->json($response);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json('Logout success');
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Get role of user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRole(Request $request): JsonResponse
    {
        try {
            $user = User::with('roles')->where('id', '=', $request->user()->id)->first();
            if (is_null($user)) {
                return response()->json('User not found', 404);
            }
            return response()->json($user->roles[0]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Get user information
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $user = User::with(['profile', 'department'])->where('id', '=', $request->user()->id)->first();
            return response()->json($user);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
