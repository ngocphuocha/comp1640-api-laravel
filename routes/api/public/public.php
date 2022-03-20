<?php

use App\Http\Controllers\Api\Comments\CommentController;
use App\Http\Controllers\Api\Public\CategoryController;
use App\Http\Controllers\Api\Public\IdeaController;
use Illuminate\Support\Facades\Route;

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);

// UserIdeas

Route::controller(IdeaController::class)->group(function () {
    Route::get('/ideas', 'index')->withoutMiddleware('api')->middleware('throttle:global');
    Route::get('/ideas/{id}', 'show');
    Route::get('/ideas/{id}/like/is-exist', 'checkIsExistLike')->middleware(['auth:sanctum']);
    Route::get('/ideas/{id}/download', 'downloadIdeaAsPDF'); // download pdf idea
    Route::get('/ideas/{idea}/likes', 'getTotalLikeOfIdea'); // get all like idea
    Route::post('/ideas/{idea}/likes', 'likeIdea')->middleware(['auth:sanctum']); // Like idea
    Route::delete('/ideas/{idea}/likes', 'unlikeIdea')->middleware(['auth:sanctum']); // delete like idea
});
// Comments
Route::controller(CommentController::class)->group(function () {
    Route::get('/ideas/{idea}/comments', 'index');
    Route::post('/ideas/{idea}/comments', 'store')->middleware(['auth:sanctum']); // post new comment to idea
});
