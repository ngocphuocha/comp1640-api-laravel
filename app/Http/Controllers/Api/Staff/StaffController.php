<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessSendMailNotificationNewIdea;
use App\Models\Idea;
use App\Models\User;
use App\Notifications\NotifyNewPostToAllUsers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    /**
     * Store new idea in resources
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postIdea(Request $request)
    {
//        TODO:: them send email thong bao toan bo cho user trong he thong khi post mot idea
        $currentUser = $request->user();

        // Check permission create idea of this staff
        if ($currentUser->hasPermissionTo('ideas.create', 'web')) {
            try {
                $idea = Idea::create($request->only(['title', 'content', 'user_id', 'category_id', 'department_id', 'is_hidden']));

                Permission::create(['guard_name' => 'web', 'name' => "idea.edit.$idea->id"]);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 409);
            }
            // If success
            $users = User::all();
            $data = [
                "body" => "A new ideas have been upload by user $currentUser->email",
                "url" => url("api/ideas/$idea->id"),
            ];
            // Send notification using queue job
            dispatch(new ProcessSendMailNotificationNewIdea($data));

            return response()->json('Post idea success', 201);
        }

        // If fail
        return response()->json('You have been remove permission to create a ideas', '403');
    }
}
