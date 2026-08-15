<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

// Public & Authentication Routes
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin_hr'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('employee.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // ADMIN HRD ROUTES
    // ==========================================
    Route::middleware(['role:admin_hr'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Department Management
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // Attendance Verification & Logs
        Route::get('/attendance', [AttendanceController::class, 'adminIndex'])->name('attendance.index');

        // Leave Requests Approval Workflow
        Route::get('/leave-requests', [LeaveRequestController::class, 'adminIndex'])->name('leave.index');
        Route::patch('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave.approve');
        Route::patch('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave.reject');
    });

    // ==========================================
    // EMPLOYEE SELF-SERVICE PORTAL ROUTES
    // ==========================================
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');

        // Camera Attendance
        Route::get('/attendance', [AttendanceController::class, 'employeeIndex'])->name('attendance.index');
        Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
        Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');

        // Leave Requests
        Route::get('/leave-requests', [LeaveRequestController::class, 'employeeIndex'])->name('leave.index');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave.store');
    });
});
