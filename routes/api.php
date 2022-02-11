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

Route::middleware(['auth:sanctum'])->controller(\App\Http\Controllers\Api\Auth\AuthController::class)->group(function () {
    Route::post('/login', 'login')->withoutMiddleware(['auth:sanctum']);

    Route::post('/logout', 'logout');

    Route::get('/auth/users', 'getRole');

    Route::get('/auth/users/profiles', 'show');
});
