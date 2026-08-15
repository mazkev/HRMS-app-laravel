# 📄 Product Requirement Document (PRD)
## HR Management System (HRMS) - PT Maju

---

## 1. Executive Summary & Overview
* **Project Name:** PT Maju HRMS
* **Version:** 1.0.0 (MVP)
* **Tech Stack:** Laravel 11, MySQL, FilamentPHP / Blade UI
* **Project Objective:** Build a centralized HR management platform for PT Maju to automate employee data records, daily attendance tracking, and leave request workflows in a transparent and structured manner.

---

## 2. User Roles & Permissions

| Role | Access & Permissions Description |
| :--- | :--- |
| **Admin HRD** | Full system access: CRUD operations on employee data, managing departments, reviewing attendance logs with photo selfie verification, and approving/rejecting leave requests. |
| **Employee** | Self-service access: Clock-in/clock-out attendance with live camera selfie capture, viewing personal attendance history, and submitting leave requests. |

---

## 3. Functional Requirements

### 3.1 Employee & Department Management
* Unique Employee Identification Number (NIK) assignment for every staff member.
* Department categorization (HRD, IT, Finance, Operations, etc.) and job positions.
* Record join dates, base salary, and annual leave quotas (defaulted to 12 days/year).

### 3.2 Attendance & Time Tracking (Camera-based)
* Real-time **Clock-in** and **Clock-out** capabilities.
* **Camera / Selfie Capture:** Mandatory live photo capture via device camera (Web Camera API) as proof of attendance during clock-in and clock-out.
* Automatic status calculation: `present` (On-time), `late` (After 08:30 AM), or `absent`.
* Duplicate clock-in prevention mechanism for the same working day.

### 3.3 Leave Management & Approval
* Leave application form for employees (select start date, end date, and reason).
* Automated verification of remaining leave quota prior to submission.
* Approval workflow for Admin HRD (`pending` → `approved` / `rejected`).
* Automatic leave quota deduction upon status change to `approved`.

---

## 4. Database Schema (MySQL ERD Structure)

```sql
-- Departments Table
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Users / Employees Table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin_hr', 'employee') DEFAULT 'employee',
    department_id BIGINT UNSIGNED NULL,
    position VARCHAR(255) NOT NULL,
    join_date DATE NOT NULL,
    salary DECIMAL(15,2) DEFAULT 0.00,
    leave_quota INT DEFAULT 12,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Attendance Table
CREATE TABLE attendances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    time_in TIME NULL,
    time_out TIME NULL,
    image_in VARCHAR(255) NULL,
    image_out VARCHAR(255) NULL,
    status ENUM('present', 'late', 'absent', 'leave') DEFAULT 'present',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Leave Requests Table
CREATE TABLE leave_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 5. Application Architecture & Routes (Laravel Web Routes)

```php
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Employee Portal
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::post('/leave-request', [LeaveRequestController::class, 'store'])->name('leave.store');

    // Admin HRD Portal
    Route::middleware(['role:admin_hr'])->prefix('admin')->group(function () {
        Route::patch('/leave-request/{id}/approve', [LeaveRequestController::class, 'approve'])->name('admin.leave.approve');
    });
});
```

---

## 6. Non-Functional Requirements

* **Security:** Password hashing using `bcrypt`, CSRF protection across all forms, and route protection via custom Role Middleware.
* **Media & Storage:** Otomatisasi kompresi dan validasi format gambar foto selfie (JPEG/PNG/WebP) sebelum disimpan ke storage server untuk menghemat kapasitas disk.
* **Data Integrity:** Enforcement of MySQL Foreign Key Constraints with cascade/set null rules to prevent orphaned records.
* **Scalability:** Modular design structure to easily integrate Payroll, KPI Performance, and Overtime Management modules in future releases.