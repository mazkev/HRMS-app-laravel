<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\EmployeeLoan;
use App\Models\Payroll;
use App\Models\User;
use App\Services\TaxBpjsCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    /**
     * Admin Payroll Management View
     */
    public function adminIndex(Request $request)
    {
        $selectedMonth = $request->input('period_month', Carbon::now()->format('Y-m'));

        $payrolls = Payroll::with(['user.department'])
            ->where('period_month', $selectedMonth)
            ->get();

        $totalDisbursed = $payrolls->where('status', 'paid')->sum('net_salary');
        $totalPending = $payrolls->where('status', 'pending')->sum('net_salary');

        return view('admin.payroll.index', compact('payrolls', 'selectedMonth', 'totalDisbursed', 'totalPending'));
    }

    /**
     * Generate Monthly Payroll with Indonesian Tax (PPh 21 TER) & BPJS Deductions
     */
    public function generate(Request $request)
    {
        $request->validate([
            'period_month' => 'required|date_format:Y-m',
        ]);

        $periodMonth = $request->input('period_month');
        $startOfMonth = Carbon::createFromFormat('Y-m', $periodMonth)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromFormat('Y-m', $periodMonth)->endOfMonth()->toDateString();

        $employees = User::where('role', 'employee')->get();
        $generatedCount = 0;

        foreach ($employees as $employee) {
            // Count Attendance
            $attendances = Attendance::where('user_id', $employee->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->get();

            $presentDays = $attendances->where('status', 'present')->count();
            $lateDays = $attendances->where('status', 'late')->count();

            // Financial Calculations
            $basicSalary = (float) $employee->salary;
            $allowances = 500000.00; // Tunjangan standar
            $grossIncome = $basicSalary + $allowances;

            // Late Penalty (Rp 50.000 per late day)
            $lateDeduction = $lateDays * 50000.00;

            // Tax & BPJS Deductions (Indonesian Statutory)
            $pph21 = TaxBpjsCalculator::calculatePph21($grossIncome, $employee->ptkp_status ?? 'TK/0');
            $bpjsKesehatan = TaxBpjsCalculator::calculateBpjsKesehatan($basicSalary);
            $bpjsTk = TaxBpjsCalculator::calculateBpjsTk($basicSalary);

            // Active Loan Auto-Deduction
            $activeLoan = EmployeeLoan::where('user_id', $employee->id)
                ->where('status', 'approved')
                ->where('remaining_amount', '>', 0)
                ->first();

            $loanDeduction = 0.00;
            if ($activeLoan) {
                $installment = min((float) $activeLoan->monthly_installment, (float) $activeLoan->remaining_amount);
                $loanDeduction = $installment;
            }

            $totalDeductions = $lateDeduction + $pph21 + $bpjsKesehatan + $bpjsTk + $loanDeduction;
            $netSalary = max(0, $grossIncome - $totalDeductions);

            Payroll::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    'period_month' => $periodMonth,
                ],
                [
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'pph21_amount' => $pph21,
                    'bpjs_kesehatan_deduction' => $bpjsKesehatan,
                    'bpjs_tk_deduction' => $bpjsTk,
                    'loan_deduction' => $loanDeduction,
                    'late_deduction' => $lateDeduction,
                    'other_deductions' => 0.00,
                    'net_salary' => $netSalary,
                    'total_present_days' => $presentDays,
                    'total_late_days' => $lateDays,
                    'status' => 'paid',
                    'payment_date' => Carbon::now()->toDateString(),
                    'notes' => 'Gaji bulan ' . Carbon::createFromFormat('Y-m', $periodMonth)->translatedFormat('F Y'),
                ]
            );

            $generatedCount++;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'GENERATE_PAYROLL',
            'description' => "Menghitung dan memproses laporan penggajian periode {$periodMonth} untuk {$generatedCount} karyawan.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.payroll.index', ['period_month' => $periodMonth])
            ->with('success', "Berhasil menghitung dan menerbitkan {$generatedCount} slip gaji (PPh 21 TER & BPJS terintegrasi) untuk periode {$periodMonth}.");
    }

    /**
     * Employee View Their Own Payroll History
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $payrolls = Payroll::where('user_id', $user->id)
            ->orderBy('period_month', 'desc')
            ->paginate(12);

        return view('employee.payroll.index', compact('payrolls', 'user'));
    }

    /**
     * Show Printable Official Slip Gaji
     */
    public function showSlip($id)
    {
        $payroll = Payroll::with(['user.department'])->findOrFail($id);

        if (Auth::user()->role !== 'admin_hr' && Auth::id() !== $payroll->user_id) {
            abort(403, 'Akses ditolak untuk melihat slip gaji karyawan lain.');
        }

        return view('payroll.slip', compact('payroll'));
    }
}
