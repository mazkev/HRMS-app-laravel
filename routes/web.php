<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayrollController;
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

    // Shared Printable Slip Gaji Route
    Route::get('/payroll/{id}/slip', [PayrollController::class, 'showSlip'])->name('payroll.slip');

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

        // Payroll Management
        Route::get('/payroll', [PayrollController::class, 'adminIndex'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');

        // Overtime Management
        Route::get('/overtimes', [OvertimeController::class, 'adminIndex'])->name('overtime.index');
        Route::patch('/overtimes/{id}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
        Route::patch('/overtimes/{id}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');

        // Team Leave Calendar
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');

        // Exports
        Route::get('/export/attendance', [ExportController::class, 'exportAttendanceCsv'])->name('export.attendance');
        Route::get('/export/payroll', [ExportController::class, 'exportPayrollCsv'])->name('export.payroll');
    });

    // ==========================================
    // EMPLOYEE SELF-SERVICE PORTAL ROUTES
    // ==========================================
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');

        // Camera Attendance with GPS
        Route::get('/attendance', [AttendanceController::class, 'employeeIndex'])->name('attendance.index');
        Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
        Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');

        // Leave Requests
        Route::get('/leave-requests', [LeaveRequestController::class, 'employeeIndex'])->name('leave.index');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave.store');

        // Overtime Submissions
        Route::get('/overtimes', [OvertimeController::class, 'employeeIndex'])->name('overtime.index');
        Route::post('/overtimes', [OvertimeController::class, 'store'])->name('overtime.store');

        // Payroll / Slip Gaji
        Route::get('/payroll', [PayrollController::class, 'employeeIndex'])->name('payroll.index');

        // Team Calendar
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    });
});
