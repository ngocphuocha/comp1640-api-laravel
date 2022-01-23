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

Route::post('/users/{id}/permissions', [\App\Http\Controllers\UserController::class,'givePermissionToUser'])->middleware(['auth:sanctum', 'auth:super-admin']);

Route::post('/login',[\App\Http\Controllers\Api\Auth\AuthController::class, 'login']);

Route::post('/super-admins/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAminController::class, 'createUser'])->middleware(['auth:sanctum', 'is.admin']);
