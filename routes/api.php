<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::post('/employees/{id}/face-register', [EmployeeController::class, 'registerFace']);
    Route::post('/employees/{id}/qr-generate', [EmployeeController::class, 'generateQr']);

    // Face liveness challenge
    Route::post('/face/challenge', [AttendanceController::class, 'createChallenge']);

    Route::post('/attendance/qr/verify', [AttendanceController::class, 'verifyQr']);
    Route::post('/attendance/face/verify', [AttendanceController::class, 'verifyFace']);
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn']);
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut']);
});
