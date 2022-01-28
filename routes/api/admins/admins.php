<?php

use Illuminate\Support\Facades\Route;

// Authenticated route
Route::prefix('/admins')->middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getListUsers']);
    Route::get('/users/{id}', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'getUsersInfo']);
    Route::post('/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAdminController::class, 'createUser']);
});

