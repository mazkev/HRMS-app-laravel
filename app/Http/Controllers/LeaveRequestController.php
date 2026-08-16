<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Employee Leave Index & Submission Form
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.leave.index', compact('leaveRequests', 'user'));
    }

    /**
     * Store Leave Request (Handles Special Leaves & Medical Certificate Upload)
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,sick,maternity,marriage,bereavement,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'medical_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $user = Auth::user();
        $start = Carbon::parse($request->input('start_date'));
        $end = Carbon::parse($request->input('end_date'));
        $totalDays = $start->diffInDays($end) + 1;
        $leaveType = $request->input('leave_type');

        // Check annual leave quota ONLY if type is annual
        if ($leaveType === 'annual' && $user->leave_quota < $totalDays) {
            return back()->with('error', "Sisa kuota cuti tahunan Anda ({$user->leave_quota} hari) tidak mencukupi untuk pengajuan {$totalDays} hari.");
        }

        // Handle Medical Certificate Upload
        $certPath = null;
        if ($request->hasFile('medical_certificate')) {
            $certPath = $request->file('medical_certificate')->store('medical_certificates', 'public');
        } elseif ($leaveType === 'sick') {
            return back()->with('error', 'Pengajuan cuti sakit wajib melampirkan foto/PDF Surat Keterangan Dokter (SKD).');
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type' => $leaveType,
            'medical_certificate' => $certPath,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'total_days' => $totalDays,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leave.index')
            ->with('success', 'Permohonan cuti/izin berhasil diajukan untuk ditinjau oleh HRD.');
    }

    /**
     * Admin Leave Management Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $query = LeaveRequest::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        $leaveRequests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.leave.index', compact('leaveRequests', 'status'));
    }

    /**
     * Approve Leave Request
     */
    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Permohonan cuti ini sudah diproses sebelumnya.');
        }

        $user = $leave->user;

        // Deduct quota only if annual leave
        if ($leave->leave_type === 'annual') {
            if ($user->leave_quota < $leave->total_days) {
                return back()->with('error', 'Sisa kuota cuti karyawan tidak mencukupi untuk disetujui.');
            }
            $user->decrement('leave_quota', $leave->total_days);
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes', 'Disetujui.'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'APPROVE_LEAVE_REQUEST',
            'description' => "Menyetujui cuti ({$leave->leave_type}) {$user->name} selama {$leave->total_days} hari.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Permohonan cuti {$user->name} berhasil disetujui.");
    }

    /**
     * Reject Leave Request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $leave = LeaveRequest::findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Permohonan cuti ini sudah diproses sebelumnya.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Permohonan cuti berhasil ditolak.');
    }
}
