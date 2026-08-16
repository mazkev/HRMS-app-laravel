<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessTripController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KudosController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\NotificationGatewayController;
use App\Http\Controllers\OrgChartController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\ResignationController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftSwapController;
use App\Http\Controllers\ThrController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\WarningLetterController;
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

    // Shared Printable Documents
    Route::get('/payroll/{id}/slip', [PayrollController::class, 'showSlip'])->name('payroll.slip');
    Route::get('/thr/{id}/slip', [ThrController::class, 'showSlip'])->name('thr.slip');
    Route::get('/business-trips/{id}/print', [BusinessTripController::class, 'showPrint'])->name('business-trips.print');
    Route::get('/warning-letters/{id}/print', [WarningLetterController::class, 'showPrint'])->name('warning-letters.print');
    Route::get('/resignations/{id}/paklaring', [ResignationController::class, 'showPaklaring'])->name('resignations.paklaring');

    // Shared Announcements, Documents, Org Chart, Peer Kudos
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/org-chart', [OrgChartController::class, 'index'])->name('orgchart.index');
    Route::get('/kudos', [KudosController::class, 'index'])->name('kudos.index');
    Route::post('/kudos', [KudosController::class, 'store'])->name('kudos.store');

    // ==========================================
    // ADMIN HRD ROUTES
    // ==========================================
    Route::middleware(['role:admin_hr'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Executive Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

        // Notification Gateway Simulator
        Route::get('/notifications', [NotificationGatewayController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/send', [NotificationGatewayController::class, 'send'])->name('notifications.send');

        // THR Payments
        Route::get('/thr', [ThrController::class, 'adminIndex'])->name('thr.index');
        Route::post('/thr/generate', [ThrController::class, 'generate'])->name('thr.generate');

        // Business Trips & SPPD
        Route::get('/business-trips', [BusinessTripController::class, 'adminIndex'])->name('business-trips.index');
        Route::patch('/business-trips/{id}/approve', [BusinessTripController::class, 'approve'])->name('business-trips.approve');

        // Shift Swaps
        Route::get('/shift-swaps', [ShiftSwapController::class, 'adminIndex'])->name('shift-swaps.index');
        Route::patch('/shift-swaps/{id}/approve', [ShiftSwapController::class, 'approve'])->name('shift-swaps.approve');

        // Disciplinary & Warning Letters (SP)
        Route::get('/warning-letters', [WarningLetterController::class, 'adminIndex'])->name('warning-letters.index');
        Route::post('/warning-letters', [WarningLetterController::class, 'store'])->name('warning-letters.store');

        // Offboarding & Resignations
        Route::get('/resignations', [ResignationController::class, 'adminIndex'])->name('resignations.index');
        Route::patch('/resignations/{id}/approve', [ResignationController::class, 'approve'])->name('resignations.approve');

        // Recruitment & ATS
        Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
        Route::post('/recruitment/jobs', [RecruitmentController::class, 'storeJob'])->name('recruitment.jobs.store');
        Route::patch('/recruitment/applications/{id}/status', [RecruitmentController::class, 'updateApplicationStatus'])->name('recruitment.applications.status');
        Route::post('/recruitment/applications/{id}/convert', [RecruitmentController::class, 'convertToEmployee'])->name('recruitment.applications.convert');

        // Asset Management
        Route::get('/assets', [AssetController::class, 'adminIndex'])->name('assets.index');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::patch('/assets/{id}/assign', [AssetController::class, 'assign'])->name('assets.assign');

        // Loans & Kasbon Management
        Route::get('/loans', [LoanController::class, 'adminIndex'])->name('loans.index');
        Route::patch('/loans/{id}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::patch('/loans/{id}/reject', [LoanController::class, 'reject'])->name('loans.reject');

        // Training LMS Lite
        Route::get('/trainings', [TrainingController::class, 'adminIndex'])->name('trainings.index');
        Route::post('/trainings', [TrainingController::class, 'store'])->name('trainings.store');

        // Security Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');

        // Employee Management
        Route::resource('employees', EmployeeController::class);

        // Department Management
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        // Shifts Management
        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::put('/shifts/{id}', [ShiftController::class, 'update'])->name('shifts.update');
        Route::delete('/shifts/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');

        // Attendance Verification & Logs
        Route::get('/attendance', [AttendanceController::class, 'adminIndex'])->name('attendance.index');

        // Leave Requests Approval Workflow
        Route::get('/leave-requests', [LeaveRequestController::class, 'adminIndex'])->name('leave.index');
        Route::patch('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave.approve');
        Route::patch('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave.reject');

        // Overtime Management
        Route::get('/overtimes', [OvertimeController::class, 'adminIndex'])->name('overtime.index');
        Route::patch('/overtimes/{id}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
        Route::patch('/overtimes/{id}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');

        // Reimbursements
        Route::get('/reimbursements', [ReimbursementController::class, 'adminIndex'])->name('reimbursements.index');
        Route::patch('/reimbursements/{id}/approve', [ReimbursementController::class, 'approve'])->name('reimbursements.approve');
        Route::patch('/reimbursements/{id}/reject', [ReimbursementController::class, 'reject'])->name('reimbursements.reject');

        // Performance & KPI Appraisal
        Route::get('/performance', [PerformanceController::class, 'adminIndex'])->name('performance.index');
        Route::post('/performance', [PerformanceController::class, 'store'])->name('performance.store');

        // Announcements
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // Payroll Management
        Route::get('/payroll', [PayrollController::class, 'adminIndex'])->name('payroll.index');
        Route::post('/payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');

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

        // Business Trips & SPPD
        Route::get('/business-trips', [BusinessTripController::class, 'employeeIndex'])->name('business-trips.index');
        Route::post('/business-trips', [BusinessTripController::class, 'store'])->name('business-trips.store');

        // Shift Swaps
        Route::get('/shift-swaps', [ShiftSwapController::class, 'employeeIndex'])->name('shift-swaps.index');
        Route::post('/shift-swaps', [ShiftSwapController::class, 'store'])->name('shift-swaps.store');

        // THR Payments
        Route::get('/thr', [ThrController::class, 'employeeIndex'])->name('thr.index');

        // Warning Letters
        Route::get('/warning-letters', [WarningLetterController::class, 'employeeIndex'])->name('warning-letters.index');

        // Resignations & Paklaring
        Route::get('/resignations', [ResignationController::class, 'employeeIndex'])->name('resignations.index');
        Route::post('/resignations', [ResignationController::class, 'store'])->name('resignations.store');

        // Overtime Submissions
        Route::get('/overtimes', [OvertimeController::class, 'employeeIndex'])->name('overtime.index');
        Route::post('/overtimes', [OvertimeController::class, 'store'])->name('overtime.store');

        // Reimbursements
        Route::get('/reimbursements', [ReimbursementController::class, 'employeeIndex'])->name('reimbursements.index');
        Route::post('/reimbursements', [ReimbursementController::class, 'store'])->name('reimbursements.store');

        // My Assets
        Route::get('/assets', [AssetController::class, 'employeeIndex'])->name('assets.index');

        // Loans / Kasbon
        Route::get('/loans', [LoanController::class, 'employeeIndex'])->name('loans.index');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

        // Trainings
        Route::get('/trainings', [TrainingController::class, 'employeeIndex'])->name('trainings.index');
        Route::post('/trainings/{id}/enroll', [TrainingController::class, 'enroll'])->name('trainings.enroll');

        // Performance Scorecard
        Route::get('/performance', [PerformanceController::class, 'employeeIndex'])->name('performance.index');

        // Payroll / Slip Gaji
        Route::get('/payroll', [PayrollController::class, 'employeeIndex'])->name('payroll.index');

        // Team Calendar
        Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    });
});
