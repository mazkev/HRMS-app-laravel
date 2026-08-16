<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ThrPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThrController extends Controller
{
    /**
     * Admin THR List & Generator
     */
    public function adminIndex(Request $request)
    {
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $holidayName = $request->input('holiday_name', 'Idul Fitri 1447 H');

        $thrPayments = ThrPayment::with('user.department')
            ->where('year', $selectedYear)
            ->where('holiday_name', $holidayName)
            ->get();

        $totalDisbursed = $thrPayments->sum('thr_amount');

        return view('admin.thr.index', compact('thrPayments', 'selectedYear', 'holidayName', 'totalDisbursed'));
    }

    /**
     * Generate THR with Kemnaker Pro-Rata Formula
     */
    public function generate(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4',
            'holiday_name' => 'required|string|max:100',
        ]);

        $year = $request->input('year');
        $holidayName = $request->input('holiday_name');
        $employees = User::where('role', 'employee')->get();
        $generatedCount = 0;

        foreach ($employees as $employee) {
            $joinDate = Carbon::parse($employee->join_date);
            $now = Carbon::now();
            $tenureMonths = max(1, $joinDate->diffInMonths($now));
            $basicSalary = (float) $employee->salary;

            // Kemnaker Regulation Formula
            if ($tenureMonths >= 12) {
                $thrAmount = $basicSalary; // 1x Full Salary
            } else {
                $thrAmount = round(($tenureMonths / 12) * $basicSalary, 2); // Pro-rata
            }

            ThrPayment::updateOrCreate(
                [
                    'user_id' => $employee->id,
                    'year' => $year,
                    'holiday_name' => $holidayName,
                ],
                [
                    'tenure_months' => $tenureMonths,
                    'basic_salary' => $basicSalary,
                    'thr_amount' => $thrAmount,
                    'payment_date' => Carbon::now()->toDateString(),
                    'status' => 'paid',
                    'notes' => ($tenureMonths >= 12) ? 'Masa kerja >= 12 bulan (1x Gaji Penuh)' : "Masa kerja {$tenureMonths} bulan (Pro-rata Kemnaker)",
                ]
            );

            $generatedCount++;
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'GENERATE_THR_PAYMENT',
            'description' => "Menghitung dan menerbitkan THR {$holidayName} {$year} untuk {$generatedCount} karyawan.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.thr.index', ['year' => $year, 'holiday_name' => $holidayName])
            ->with('success', "Berhasil menerbitkan THR {$holidayName} {$year} untuk {$generatedCount} karyawan.");
    }

    /**
     * Employee View Their Own THR
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $thrPayments = ThrPayment::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('employee.thr.index', compact('thrPayments', 'user'));
    }

    /**
     * Printable Official Slip THR
     */
    public function showSlip($id)
    {
        $thr = ThrPayment::with('user.department')->findOrFail($id);

        if (Auth::user()->role !== 'admin_hr' && Auth::id() !== $thr->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('thr.slip', compact('thr'));
    }
}
