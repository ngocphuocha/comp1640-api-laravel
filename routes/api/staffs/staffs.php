<?php

use App\Http\Controllers\Api\Staff\StaffController;
use Illuminate\Support\Facades\Route;

Route::prefix('staffs')->middleware(['auth:sanctum', 'is.staff'])->group(function () {
    Route::post('/ideas', [StaffController::class, 'postIdea']);
});
