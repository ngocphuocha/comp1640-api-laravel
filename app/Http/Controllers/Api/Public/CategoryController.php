<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Get all the categories
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = DB::table('categories')->select('id', 'name', 'description')->get();
            return response()->json($categories);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

    }

    /**
     * Get detail the category
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $category = Category::where('id', $id)->first();
            if (is_null($category)) {
                return response()->json('category not found', 404);
            }
            return response()->json($category);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
