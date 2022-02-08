<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Staff\StoreIdeaRequest;
use App\Jobs\ProcessSendMailNotificationNewIdea;
use App\Models\Idea;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Smalot\PdfParser\Parser;
use App\Models\File;

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
        if ($currentUser->hasPermissionTo('ideas.create', 'web')) {
            try {
                $data = $request->only(['title', 'content', 'user_id', 'category_id',  'is_hidden']);
                $data['department_id'] = $request->user()->department_id;

                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $content = $parser->parseFile($file->path())->getText();
                    $path = $request->file('file')->store('ideas'); // store idea pdf to storage idea folder
                    $newFile = File::create([
                        'name' => $file->hashName(),
                        'type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'path' => $path
                    ]);

                    // Change data content if user choose post idea vie pdf file
                    $data['content'] = $content;
                    $data['file_id'] = $newFile->id;
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
                "url" => url("api/ideas/$idea->id"),
            ];

            // Send notification via queue job
            dispatch(new ProcessSendMailNotificationNewIdea($data));

            return response()->json('Post idea success', 201);
        }

        // If fail
        return response()->json('You have been remove permission to create a ideas', '403');
    }

}
