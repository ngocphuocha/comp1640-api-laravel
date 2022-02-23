<?php

use Illuminate\Support\Facades\Route;

// Authenticated route
Route::prefix('/admins')->middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getListUsers']);
    Route::get('/users/{id}', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getUsersInfo']);
    Route::post('/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'createUser']);
    Route::get('/roles', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getRoles']);
    Route::get('/departments', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getDepartments']);
});

//Hiden ideas
Route::get('/admins/hidden-ideas', [\App\Http\Controllers\Api\Public\IdeaController::class, 'getHiddenIdeas'])->middleware(['auth:sanctum', 'is.admin']);

// Ideas not have comments
Route::get('/admins/ideas-without-comments', [\App\Http\Controllers\Api\SuperAdmin\IdeaController::class, 'getIdeasNotHaveComment'])->middleware(['auth:sanctum', 'is.admin']);

// Statistic
Route::prefix('/admins')->middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/statistic/ideas', [\App\Http\Controllers\Api\SuperAdmin\StatisticsController::class, 'getTotalIdeaEachDepartment']);
    Route::get('/statistic/users', [\App\Http\Controllers\Api\SuperAdmin\StatisticsController::class, 'getTotalUserEachDepartment']);
});
