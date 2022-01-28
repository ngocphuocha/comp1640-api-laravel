<?php

use Illuminate\Support\Facades\Route;

// Auth routes
Route::prefix('qa-managers')->middleware(['auth:sanctum', 'is.qa_manager'])->group(function () {
    Route::post('/categories', [\App\Http\Controllers\Api\QAManager\QAManagerController::class, 'createNewCategory']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Api\QAManager\QAManagerController::class, 'deleteCategory']);
    Route::put('/users/{id}/permissions', [\App\Http\Controllers\Api\QAManager\QAManagerController::class, 'updateUserPermissions']);
});
