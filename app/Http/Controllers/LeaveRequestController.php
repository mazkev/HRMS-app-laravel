<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    /**
     * Employee Leave History & Application List
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.leave.index', compact('user', 'leaves'));
    }

    /**
     * Store new Leave Request submitted by Employee
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();
        $start = Carbon::parse($request->input('start_date'));
        $end = Carbon::parse($request->input('end_date'));
        $totalDays = $start->diffInDays($end) + 1;

        // Check if user has sufficient leave quota
        if ($user->leave_quota < $totalDays) {
            return back()->withInput()->with('error', "Sisa kuota cuti Anda ({$user->leave_quota} hari) tidak mencukupi untuk pengajuan {$totalDays} hari cuti.");
        }

        // Check overlapping leave requests
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($sub) use ($start, $end) {
                      $sub->where('start_date', '<=', $start)
                          ->where('end_date', '>=', $end);
                  });
            })->exists();

        if ($overlap) {
            return back()->withInput()->with('error', 'Anda sudah memiliki pengajuan cuti yang aktif pada rentang tanggal tersebut.');
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_days' => $totalDays,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leave.index')
            ->with('success', "Pengajuan cuti selama {$totalDays} hari berhasil dikirim dan menunggu persetujuan HRD.");
    }

    /**
     * Admin Leave Management Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = LeaveRequest::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $leaves = $query->latest()->paginate(15)->withQueryString();

        return view('admin.leave.index', compact('leaves', 'status', 'search'));
    }

    /**
     * Admin Approve Leave Request (with automated quota deduction)
     */
    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($leave, $request) {
            $employee = $leave->user;

            // Deduct leave quota
            if ($employee->leave_quota >= $leave->total_days) {
                $employee->decrement('leave_quota', $leave->total_days);
            } else {
                $employee->update(['leave_quota' => 0]);
            }

            $leave->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'admin_notes' => $request->input('admin_notes', 'Disetujui oleh HRD.'),
            ]);
        });

        return back()->with('success', "Pengajuan cuti {$leave->user->name} berhasil disetujui. Kuota cuti berkurang {$leave->total_days} hari.");
    }

    /**
     * Admin Reject Leave Request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $leave = LeaveRequest::findOrFail($id);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan cuti ini sudah diproses sebelumnya.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
