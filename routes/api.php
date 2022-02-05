<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication


Route::post('/login', [\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);

Route::post('/logout', [\App\Http\Controllers\Api\Auth\AuthController::class, 'logout'])->middleware('auth:sanctum');


// Categories

Route::get('/categories', [\App\Http\Controllers\Api\Public\CategoryController::class, 'index']);

// Ideas
Route::controller(\App\Http\Controllers\Api\Public\IdeaController::class)->group(function () {
    Route::get('/ideas', 'index');
    Route::get('/ideas/{id}', 'show');
    Route::get('/ideas/{id}/download', 'downloadIdeaAsPDF'); // download pdf idea
    Route::get('/ideas/{idea}/likes', 'getTotalLikeOfIdea');
    Route::post('/ideas/{idea}/likes', 'likeIdea')->middleware(['auth:sanctum']); // Like idea
    Route::delete('/ideas/{idea}/likes', 'unlikeIdea')->middleware(['auth:sanctum']); // delete like idea
});

// Comments
Route::controller(\App\Http\Controllers\Api\Comments\CommentController::class)->group(function () {
    Route::get('/ideas/{idea}/comments', 'index');
    Route::post('/ideas/{idea}/comments', 'store')->middleware(['auth:sanctum']);
});
