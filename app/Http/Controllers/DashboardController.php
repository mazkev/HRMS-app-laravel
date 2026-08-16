<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $today = Carbon::today()->toDateString();

        $totalEmployees = User::where('role', 'employee')->count();
        $totalDepartments = Department::count();
        $todayAttendances = Attendance::where('date', $today)->get();
        $presentToday = $todayAttendances->whereIn('status', ['present', 'late'])->count();
        $lateToday = $todayAttendances->where('status', 'late')->count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();

        $recentAttendances = Attendance::with('user.department')
            ->where('date', $today)
            ->latest('updated_at')
            ->take(8)
            ->get();

        $recentLeaves = LeaveRequest::with('user.department')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'presentToday',
            'lateToday',
            'pendingLeaves',
            'recentAttendances',
            'recentLeaves'
        ));
    }

    public function employeeDashboard()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyAttendances = Attendance::where('user_id', $user->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get();

        $presentCount = $monthlyAttendances->where('status', 'present')->count();
        $lateCount = $monthlyAttendances->where('status', 'late')->count();
        
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->latest('date')
            ->take(5)
            ->get();

        $recentLeaves = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $latestPayroll = Payroll::where('user_id', $user->id)
            ->latest('period_month')
            ->first();

        $latestAnnouncement = Announcement::where('is_pinned', true)->latest()->first() 
            ?? Announcement::latest()->first();

        return view('employee.dashboard', compact(
            'user',
            'todayAttendance',
            'presentCount',
            'lateCount',
            'recentAttendances',
            'recentLeaves',
            'latestPayroll',
            'latestAnnouncement'
        ));
    }
}
