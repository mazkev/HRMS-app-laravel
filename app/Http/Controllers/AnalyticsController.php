<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * HR Executive Analytics Dashboard
     */
    public function index()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $totalDepartments = Department::count();

        // 1. Attendance Metrics (Last 6 Months)
        $months = [];
        $presentCounts = [];
        $lateCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthObj = Carbon::now()->subMonths($i);
            $monthKey = $monthObj->format('Y-m');
            $months[] = $monthObj->translatedFormat('M Y');

            $start = $monthObj->copy()->startOfMonth()->toDateString();
            $end = $monthObj->copy()->endOfMonth()->toDateString();

            $pCount = Attendance::whereBetween('date', [$start, $end])
                ->where('status', 'present')
                ->count();
            $lCount = Attendance::whereBetween('date', [$start, $end])
                ->where('status', 'late')
                ->count();

            $presentCounts[] = $pCount;
            $lateCounts[] = $lCount;
        }

        // 2. Department Payroll Distribution
        $currentMonth = Carbon::now()->format('Y-m');
        $departments = Department::with(['users.payrolls' => function ($q) use ($currentMonth) {
            $q->where('period_month', $currentMonth);
        }])->get();

        $deptLabels = [];
        $deptPayrollSums = [];
        $deptHeadcounts = [];

        foreach ($departments as $dept) {
            $deptLabels[] = $dept->name;
            $deptHeadcounts[] = $dept->users->where('role', 'employee')->count();

            $sumPayroll = 0;
            foreach ($dept->users as $u) {
                $sumPayroll += $u->salary; // base monthly allocation
            }
            $deptPayrollSums[] = $sumPayroll;
        }

        // 3. Leave Utilization by Department
        $deptLeaveCounts = [];
        foreach ($departments as $dept) {
            $leaves = LeaveRequest::whereHas('user', function ($q) use ($dept) {
                $q->where('department_id', $dept->id);
            })->where('status', 'approved')->sum('total_days');

            $deptLeaveCounts[] = (int) $leaves;
        }

        $totalPayrollBudget = array_sum($deptPayrollSums);
        $totalMonthlyLeaves = array_sum($deptLeaveCounts);

        return view('admin.analytics.index', compact(
            'totalEmployees',
            'totalDepartments',
            'totalPayrollBudget',
            'totalMonthlyLeaves',
            'months',
            'presentCounts',
            'lateCounts',
            'deptLabels',
            'deptPayrollSums',
            'deptHeadcounts',
            'deptLeaveCounts'
        ));
    }
}
