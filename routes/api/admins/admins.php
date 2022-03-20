<?php

use App\Http\Controllers\Api\SuperAdmin\IdeaController;
use App\Http\Controllers\Api\SuperAdmin\StatisticsController;
use App\Http\Controllers\Api\SuperAdmin\SuperAdminController;
use Illuminate\Support\Facades\Route;

// Authenticated route
Route::prefix('/admins')->middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/users', [SuperAdminController::class, 'getListUsers']);
    Route::get('/users/{id}', [SuperAdminController::class, 'getUsersInfo']);
    Route::post('/users', [SuperAdminController::class, 'createUser']);
    Route::get('/roles', [SuperAdminController::class, 'getRoles']);
    Route::get('/departments', [SuperAdminController::class, 'getDepartments']);
});

//Hidden ideas
Route::get('/admins/hidden-ideas', [\App\Http\Controllers\Api\Public\IdeaController::class, 'getHiddenIdeas'])->middleware(['auth:sanctum', 'is.admin']);

// Ideas not have comments
Route::get('/admins/ideas-without-comments', [IdeaController::class, 'getIdeasNotHaveComment'])->middleware(['auth:sanctum', 'is.admin']);

// Statistic
Route::prefix('/admins')->middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/statistic/ideas', [StatisticsController::class, 'getTotalIdeaEachDepartment']);
    Route::get('/statistic/users', [StatisticsController::class, 'getTotalUserEachDepartment']);
});
