<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = DB::table('categories')->select('id', 'name', 'description')->get();
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json($categories, 200);
    }

    public function show($id): JsonResponse
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                throw new Exception("Category not found");
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        }

        return response()->json($category, Response::HTTP_OK);
    }


}
