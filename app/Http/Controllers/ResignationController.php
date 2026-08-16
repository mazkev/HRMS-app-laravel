<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Resignation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    /**
     * Employee Resignation Form & History
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $resignations = Resignation::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('employee.resignations.index', compact('resignations', 'user'));
    }

    /**
     * Store 1-Month Notice Resignation Request
     */
    public function store(Request $request)
    {
        $request->validate([
            'resign_date' => 'required|date|after_or_equal:' . Carbon::now()->addDays(20)->toDateString(),
            'reason' => 'required|string|min:10|max:1000',
        ]);

        Resignation::create([
            'user_id' => Auth::id(),
            'notice_date' => Carbon::now()->toDateString(),
            'resign_date' => $request->input('resign_date'),
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.resignations.index')
            ->with('success', 'Permohonan pengunduran diri (1-Month Notice) berhasil diajukan ke Manajemen HRD.');
    }

    /**
     * Admin Resignations & Exit Clearance Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $query = Resignation::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        $resignations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.resignations.index', compact('resignations', 'status'));
    }

    /**
     * Approve Resignation & Issue Official Paklaring Number
     */
    public function approve(Request $request, $id)
    {
        $resignation = Resignation::with('user')->findOrFail($id);

        if ($resignation->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses.');
        }

        $now = Carbon::now();
        $count = Resignation::whereNotNull('paklaring_number')->whereYear('created_at', $now->year)->count() + 1;
        $paklaringNumber = sprintf("PKL/%04d/%02d/%03d", $now->year, $now->month, $count);

        $resignation->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'paklaring_number' => $paklaringNumber,
            'exit_clearance_notes' => $request->input('exit_clearance_notes', 'Exit clearance disetujui. Aset dan serah terima pekerjaan selesai.'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'APPROVE_RESIGNATION_ISSUE_PAKLARING',
            'description' => "Menyetujui permohonan resignasi {$resignation->user->name} dan menerbitkan Paklaring No. {$paklaringNumber}.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Permohonan resign {$resignation->user->name} disetujui dan Surat Paklaring No: {$paklaringNumber} berhasil diterbitkan.");
    }

    /**
     * Show Official Printable Paklaring Document
     */
    public function showPaklaring($id)
    {
        $resignation = Resignation::with(['user.department', 'approver'])->findOrFail($id);

        if ($resignation->status !== 'approved' && $resignation->status !== 'completed') {
            abort(403, 'Surat Pengalaman Kerja belum diterbitkan.');
        }

        if (Auth::user()->role !== 'admin_hr' && Auth::id() !== $resignation->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('resignations.paklaring', compact('resignation'));
    }
}
