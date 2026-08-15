<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Admin Payroll Management List
     */
    public function adminIndex(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $departmentId = $request->input('department_id');
        $status = $request->input('status');

        $departments = Department::orderBy('name')->get();

        $query = Payroll::with(['user.department'])
            ->where('period_month', $month);

        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $payrolls = $query->paginate(15)->withQueryString();

        $totalPayout = Payroll::where('period_month', $month)->sum('net_salary');
        $totalEmployeesPaid = Payroll::where('period_month', $month)->count();

        return view('admin.payroll.index', compact('payrolls', 'departments', 'month', 'departmentId', 'status', 'totalPayout', 'totalEmployeesPaid'));
    }

    /**
     * Generate Payroll for all active employees for given month
     */
    public function generate(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();

        $employees = User::where('role', 'employee')->get();
        $generatedCount = 0;

        foreach ($employees as $emp) {
            // Count attendances in period
            $attendances = Attendance::where('user_id', $emp->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();
            $lateDays = $attendances->where('status', 'late')->count();

            // Late penalty: Rp 50.000 per late day
            $latePenalty = $lateDays * 50000.00;
            $allowance = 500000.00; // Standard transport/meal allowance
            $otherDeductions = 0.00;

            $netSalary = max(0, $emp->salary + $allowance - $latePenalty - $otherDeductions);

            Payroll::updateOrCreate(
                [
                    'user_id' => $emp->id,
                    'period_month' => $month,
                ],
                [
                    'basic_salary' => $emp->salary,
                    'allowances' => $allowance,
                    'late_deduction' => $latePenalty,
                    'other_deductions' => $otherDeductions,
                    'net_salary' => $netSalary,
                    'total_present_days' => $presentDays,
                    'total_late_days' => $lateDays,
                    'status' => 'published',
                    'payment_date' => Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString(),
                ]
            );

            $generatedCount++;
        }

        return redirect()->route('admin.payroll.index', ['month' => $month])
            ->with('success', "Payroll periode {$month} berhasil digenerate untuk {$generatedCount} karyawan.");
    }

    /**
     * Employee Payroll History
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $payrolls = Payroll::where('user_id', $user->id)
            ->where('status', '!=', 'draft')
            ->orderBy('period_month', 'desc')
            ->paginate(12);

        return view('employee.payroll.index', compact('payrolls', 'user'));
    }

    /**
     * Show Printable / PDF Slip Gaji
     */
    public function showSlip($id)
    {
        $payroll = Payroll::with(['user.department'])->findOrFail($id);

        // Security check: Employee can only view their own slip
        if (Auth::user()->role === 'employee' && $payroll->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return view('payroll.slip', compact('payroll'));
    }
}
