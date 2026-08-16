<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Shift;
use App\Models\ShiftSwap;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftSwapController extends Controller
{
    /**
     * Admin Shift Swaps Queue
     */
    public function adminIndex()
    {
        $swaps = ShiftSwap::with(['requester', 'targetUser', 'requesterShift', 'targetShift'])
            ->latest()
            ->paginate(15);

        return view('admin.shift_swaps.index', compact('swaps'));
    }

    /**
     * Approve Shift Swap
     */
    public function approve($id)
    {
        $swap = ShiftSwap::with(['requester', 'targetUser'])->findOrFail($id);

        if ($swap->status !== 'pending_admin') {
            return back()->with('error', 'Permohonan tukar shift ini sudah diproses.');
        }

        $swap->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'APPROVE_SHIFT_SWAP',
            'description' => "Menyetujui pertukaran shift antara {$swap->requester->name} dan {$swap->targetUser->name} pada tanggal {$swap->swap_date->format('d M Y')}.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Pertukaran shift antara {$swap->requester->name} dan {$swap->targetUser->name} berhasil disetujui.");
    }

    /**
     * Employee Shift Swap View & Form
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $colleagues = User::where('role', 'employee')
            ->where('id', '!=', $user->id)
            ->where('department_id', $user->department_id)
            ->orderBy('name')
            ->get();

        $shifts = Shift::all();

        $mySwaps = ShiftSwap::with(['requester', 'targetUser', 'requesterShift', 'targetShift'])
            ->where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                  ->orWhere('target_user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('employee.shift_swaps.index', compact('colleagues', 'shifts', 'mySwaps', 'user'));
    }

    /**
     * Store Shift Swap Request
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_user_id' => 'required|exists:users,id',
            'swap_date' => 'required|date|after_or_equal:today',
            'requester_shift_id' => 'required|exists:shifts,id',
            'target_shift_id' => 'required|exists:shifts,id',
            'reason' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        ShiftSwap::create([
            'requester_id' => $user->id,
            'target_user_id' => $request->input('target_user_id'),
            'swap_date' => $request->input('swap_date'),
            'requester_shift_id' => $request->input('requester_shift_id'),
            'target_shift_id' => $request->input('target_shift_id'),
            'reason' => $request->input('reason'),
            'status' => 'pending_admin',
        ]);

        return redirect()->route('employee.shift-swaps.index')
            ->with('success', 'Permohonan tukar shift berhasil diajukan ke HRD.');
    }
}
