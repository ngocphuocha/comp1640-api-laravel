<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = DB::table('categories')->select('id', 'name', 'description')->get();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json($categories, 200);
    }
}
