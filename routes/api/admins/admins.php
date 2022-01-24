<?php

use Illuminate\Support\Facades\Route;

// Authenticated route
Route::middleware(['auth:sanctum', 'is.admin'])->group(function () {
    Route::get('/admins/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAminController::class, 'getListUsers']);
    Route::get('/admins/users/{id}', [\App\Http\Controllers\Api\SuperAdmin\SuperAminController::class, 'getUsersInfo']);
    Route::post('/admins/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAminController::class, 'createUser']);
});

