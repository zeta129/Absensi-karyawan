<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // QR Code routes (Manager & Admin only)
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr.index');
        Route::post('/qr-codes/generate', [QrCodeController::class, 'generate'])->name('qr.generate');
        Route::get('/qr-codes/{qrCode}', [QrCodeController::class, 'show'])->name('qr.show');
        Route::get('/qr-codes/{qrCode}/download', [QrCodeController::class, 'download'])->name('qr.download');
        Route::post('/qr-codes/{qrCode}/deactivate', [QrCodeController::class, 'deactivate'])->name('qr.deactivate');
    });

    // Face recognition verification endpoint (calls external service)
    Route::post('/attendance/face-verify', [\App\Http\Controllers\AttendanceController::class, 'faceVerify'])
        ->name('attendance.face_verify');

    // Face enrollment endpoint (store reference face)
    Route::post('/attendance/face-enroll', [\App\Http\Controllers\AttendanceController::class, 'faceEnroll'])
        ->name('attendance.face_enroll');

    // Attendance routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::get('/attendance/today-status', [AttendanceController::class, 'todayStatus'])->name('attendance.today-status');

    // Attendance admin/manager routes
    Route::middleware('role:manager,admin')->group(function () {
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::post('/attendance/admin-scan', [AttendanceController::class, 'adminScan'])->name('attendance.admin-scan');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    });

    // Report routes (Manager & Admin only)
    Route::middleware('role:manager,admin')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('/reports/user/{user}', [ReportController::class, 'userDetail'])->name('reports.user-detail');
        Route::get('/reports/activity-log', [ReportController::class, 'activityLog'])->name('reports.activity-log');
    });

    // Admin employee management
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)->parameters(['employees' => 'employee']);
    });
});

require __DIR__.'/auth.php';
