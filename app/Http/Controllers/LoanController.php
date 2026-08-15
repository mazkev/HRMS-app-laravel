<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\EmployeeLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Employee Loan List & Form
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $loans = EmployeeLoan::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.loans.index', compact('loans', 'user'));
    }

    /**
     * Store Loan Application
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500000|max:20000000',
            'tenor_months' => 'required|integer|min:1|max:12',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $amount = $request->input('amount');
        $tenor = $request->input('tenor_months');
        $monthlyInstallment = round($amount / $tenor, 2);

        EmployeeLoan::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'tenor_months' => $tenor,
            'monthly_installment' => $monthlyInstallment,
            'remaining_amount' => $amount,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.loans.index')
            ->with('success', 'Permohonan pinjaman/kasbon berhasil diajukan untuk diverifikasi HR Finance.');
    }

    /**
     * Admin Loan Approval Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $query = EmployeeLoan::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        $loans = $query->latest()->paginate(15)->withQueryString();
        $totalActiveLoans = EmployeeLoan::where('status', 'approved')->sum('remaining_amount');

        return view('admin.loans.index', compact('loans', 'status', 'totalActiveLoans'));
    }

    /**
     * Approve Loan
     */
    public function approve(Request $request, $id)
    {
        $loan = EmployeeLoan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pinjaman ini sudah diproses sebelumnya.');
        }

        $loan->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'disbursed_at' => now()->toDateString(),
            'admin_notes' => $request->input('admin_notes', 'Disetujui untuk pencairan dana kasbon.'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'APPROVE_EMPLOYEE_LOAN',
            'description' => "Menyetujui pinjaman Rp " . number_format($loan->amount, 0, ',', '.') . " untuk {$loan->user->name}.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Pinjaman {$loan->user->name} sebesar Rp " . number_format($loan->amount, 0, ',', '.') . " berhasil disetujui.");
    }

    /**
     * Reject Loan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $loan = EmployeeLoan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return back()->with('error', 'Pinjaman ini sudah diproses sebelumnya.');
        }

        $loan->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Permohonan pinjaman berhasil ditolak.');
    }
}
