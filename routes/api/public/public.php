<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Categories

Route::middleware(['throttle:global'])->get('/categories', [\App\Http\Controllers\Api\Public\CategoryController::class, 'index']);

// Ideas
Route::middleware(['throttle:global', 'api'])->controller(\App\Http\Controllers\Api\Public\IdeaController::class)->group(function () {
    Route::get('/ideas', 'index');
    Route::get('/ideas/{id}', 'show');
    Route::get('/ideas/{id}/download', 'downloadIdeaAsPDF'); // download pdf idea
    Route::get('/ideas/{idea}/likes', 'getTotalLikeOfIdea'); // get all like idea
    Route::post('/ideas/{idea}/likes', 'likeIdea')->middleware(['auth:sanctum']); // Like idea
    Route::delete('/ideas/{idea}/likes', 'unlikeIdea')->middleware(['auth:sanctum']); // delete like idea
});
// Comments
Route::middleware(['throttle:global', 'api'])->controller(\App\Http\Controllers\Api\Comments\CommentController::class)->group(function () {
    Route::get('/ideas/{idea}/comments', 'index');
    Route::post('/ideas/{idea}/comments', 'store')->middleware(['auth:sanctum', 'api']);
});
