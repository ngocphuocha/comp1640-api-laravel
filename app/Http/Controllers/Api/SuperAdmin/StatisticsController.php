<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getTotalIdeaEachDepartment()
    {
        try {
            $query = DB::table('ideas')
                ->join('departments', 'ideas.department_id', '=', 'departments.id')
                ->select(DB::raw("count(*) as 'total_ideas', departments.name as 'department_name'"))
                ->groupBy('ideas.department_id')
                ->get();
            return response()->json($query);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }

    }

    public function getTotalUserEachDepartment()
    {
        try {
            $query = DB::table('users')
                ->join('departments', 'users.department_id', '=', 'departments.id')
                ->select(DB::raw("count(*) as 'total_contributors', departments.name as 'department_name'"))
                ->groupBy('users.department_id')
                ->get();
            return response()->json($query);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }
}
