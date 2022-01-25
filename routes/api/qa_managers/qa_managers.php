<?php
use Illuminate\Support\Facades\Route;

Route::prefix('qa-managers')->middleware(['auth:sanctum'])->group(function () {
Route::post('/categories', [\App\Http\Controllers\Api\QAManager\QAManagerController::class, 'createNewCategories']);
});
