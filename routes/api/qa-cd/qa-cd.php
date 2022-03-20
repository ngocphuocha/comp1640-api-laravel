<?php

use App\Http\Controllers\Api\QACoordinator\StaffController;
use Illuminate\Support\Facades\Route;


Route::controller(StaffController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('/qa-coordinators/staffs', 'getStaffUsers'); // Get staffs users
    Route::get('/qa-coordinators/staffs/{id}', 'getStaffUserDetail'); // Get staff detail
    Route::put('/qa-coordinators/staffs/{id}/permissions', 'givePermission');// Give permission to staff users
});
