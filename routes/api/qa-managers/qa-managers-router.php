<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QAManager\QAManagerController;

// Auth routes
Route::prefix('qa-managers')->controller(QAManagerController::class)->middleware(['auth:sanctum', 'is.qa_manager'])->group(function () {
    Route::post('/categories', 'createNewCategory');
    Route::put('/categories/{id}', 'updateCategory');
    Route::delete('/categories/{id}', 'deleteCategory');
    Route::put('/users/{id}/permissions', 'updateUserPermissions');
});
