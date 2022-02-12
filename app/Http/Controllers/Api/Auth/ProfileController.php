<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $profile = $request->only(['name', 'phone_number', 'address', 'gender']);
        try {
            if ($request->user()->profile === null) {
                $request->user()->profile()->create($profile);
            } else {
                Profile::where('user_id', '=', $request->user()->id)->update($profile);
            }
        } catch (\Exception $exception) {
            return response()->json($exception);
        }
        return response()->json('Update profile success', 200);
    }
}
