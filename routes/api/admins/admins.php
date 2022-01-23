<?php
use Illuminate\Support\Facades\Route;

// Authenticated route
Route::middleware('auth:sanctum')->group(function () {
   Route::get('/admins/users', [\App\Http\Controllers\Api\SuperAdmin\SuperAminController::class, 'getListUsers']);
});

