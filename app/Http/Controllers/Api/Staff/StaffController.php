<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Staff\StoreIdeaRequest;
use App\Jobs\ProcessSendMailNotificationNewIdea;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Smalot\PdfParser\Parser;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    /**
     * Store new idea in resources
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postIdea(StoreIdeaRequest $request, Parser $parser)
    {
        $currentUser = $request->user();

        // Check permission create idea of this staff
        if ($currentUser->hasDirectPermission('ideas.create')) {
            try {
                $data = $request->only(['title', 'content', 'category_id', 'is_hidden']);
                $data['department_id'] = $request->user()->department_id;
                $data['user_id'] = $request->user()->id;

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $content = $parser->parseFile($file->path())->getText();

                    // Change data content if user choose post idea vie pdf file
                    $data['content'] = $content;
                }
                $idea = Idea::create($data);

                // create permission to edit and delete later access
                Permission::create(['guard_name' => 'web', 'name' => "idea.edit.$idea->id"]);
                Permission::create(['guard_name' => 'web', 'name' => "idea.delete.$idea->id"]);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 409);
            }

            $data = [
                "body" => "A new ideas have been upload by user $currentUser->email",
                "url" => env('CLIENT_APP_URL') . "/ideas/$idea->id", // detail idea with client side url (ReactJS)
            ];

            // Send notification via queue job if post is not hidden
            if ((int)$request->is_hidden === 0) { // cast hidden_post to integer type
                dispatch(new ProcessSendMailNotificationNewIdea($data));
            }

            return response()->json('Post idea success', 201);
        } else {
            return response()->json("You don't have permission to post a idea", Response::HTTP_FORBIDDEN);
        }
    }
}
