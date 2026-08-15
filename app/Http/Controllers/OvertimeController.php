<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    /**
     * Employee Overtime Submission & History
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $overtimes = Overtime::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.overtime.index', compact('overtimes', 'user'));
    }

    /**
     * Store new Overtime Request
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $start = Carbon::parse($request->input('start_time'));
        $end = Carbon::parse($request->input('end_time'));
        $durationHours = round($end->diffInMinutes($start) / 60, 2);

        Overtime::create([
            'user_id' => Auth::id(),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'duration_hours' => $durationHours,
            'reason' => $request->input('reason'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.overtime.index')
            ->with('success', "Pengajuan lembur ({$durationHours} jam) berhasil dikirim untuk diverifikasi HRD.");
    }

    /**
     * Admin Overtime Approval Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Overtime::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $overtimes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.overtime.index', compact('overtimes', 'status', 'search'));
    }

    /**
     * Approve Overtime
     */
    public function approve(Request $request, $id)
    {
        $overtime = Overtime::findOrFail($id);

        if ($overtime->status !== 'pending') {
            return back()->with('error', 'Pengajuan lembur ini sudah diproses sebelumnya.');
        }

        $overtime->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes', 'Disetujui oleh HRD.'),
        ]);

        return back()->with('success', "Lembur {$overtime->user->name} berhasil disetujui.");
    }

    /**
     * Reject Overtime
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $overtime = Overtime::findOrFail($id);

        if ($overtime->status !== 'pending') {
            return back()->with('error', 'Pengajuan lembur ini sudah diproses sebelumnya.');
        }

        $overtime->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Pengajuan lembur berhasil ditolak.');
    }
}
