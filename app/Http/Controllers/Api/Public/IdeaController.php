<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return response()->json(Idea::findOrFail($id), 200);
        } catch (\Exception) {
            return response()->json('Resource not found', 404);
        }
    }

    public function downloadIdeaAsPDF($id)
    {
        $idea = Idea::find($id);
        $fileID = $idea->file_id;
        $file = File::find($fileID);
        return Storage::download("ideas/$file->name");
    }
}
