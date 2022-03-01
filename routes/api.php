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
    return response()->json($request->user(), \Illuminate\Http\Response::HTTP_OK);
});

// Authentication
Route::middleware(['auth:sanctum'])->controller(\App\Http\Controllers\Api\Auth\AuthController::class)->group(function () {
    Route::post('/login', 'login')->withoutMiddleware(['auth:sanctum']);

    Route::post('/logout', 'logout');

    Route::get('/auth/users', 'getRole');

    Route::get('/auth/users/profiles', 'show');
});

// Profile
Route::middleware(['auth:sanctum'])->controller(\App\Http\Controllers\Api\Auth\ProfileController::class)->group(function () {
    Route::put('/users/profiles', 'update');
    Route::put('/users/passwords', 'changePassword');
});

// Idea
Route::middleware(['auth:sanctum'])->controller(\App\Http\Controllers\Api\Ideas\IdeaController::class)->group(function () {
    Route::get('/users/ideas', 'index');
    Route::put('/users/ideas/{id}', 'update');
    Route::delete('users/ideas/{id}', 'destroy');
});
