<?php
use Illuminate\Support\Facades\Route;

Route::prefix('staffs')->middleware(['auth:sanctum', 'is.staff'])->group(function () {
   Route::post('/ideas', [\App\Http\Controllers\Api\Staff\StaffController::class, 'postIdea']);
});
