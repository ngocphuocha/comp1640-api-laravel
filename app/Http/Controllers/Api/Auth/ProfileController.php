<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Models\Profile;
use ErrorException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * User update current profile
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $profile = $request->only(['name', 'phone_number', 'address', 'gender']);
        try {
            if ($request->user()->profile === null) {
                $request->user()->profile()->create($profile);
            } else {
                Profile::where('user_id', '=', $request->user()->id)->update($profile);
            }
            return response()->json('Update profile success'); // if successfully
        } catch (Exception $exception) {
            return response()->json($exception, 500);
        }
    }

    /**
     * User change current password
     *
     * @param UpdatePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $fields = $request->only(['current_password', 'password']);
        $currentUserPassword = $request->user()->password; // user password in system

        try {
            // If user input true password then update new password
            if (Hash::check($fields['current_password'], $currentUserPassword)) {
                $request->user()->update([
                    'password' => Hash::make($fields['password'])
                ]);
            } else {
                return response()->json('The current password is wrong!', 403);
            }

            // If success
            return response()->json('Change current password successfully');
        } catch (ErrorException $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
