<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\BusinessTrip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessTripController extends Controller
{
    /**
     * Admin Business Trips & SPPD Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $query = BusinessTrip::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        $trips = $query->latest()->paginate(15)->withQueryString();
        $totalAllowanceDisbursed = BusinessTrip::where('status', 'approved')->sum('total_allowance');

        return view('admin.business_trips.index', compact('trips', 'status', 'totalAllowanceDisbursed'));
    }

    /**
     * Approve Business Trip & Generate Official SPPD Number
     */
    public function approve(Request $request, $id)
    {
        $trip = BusinessTrip::with('user')->findOrFail($id);

        if ($trip->status !== 'pending') {
            return back()->with('error', 'Permohonan SPPD ini sudah diproses.');
        }

        $now = Carbon::now();
        $count = BusinessTrip::whereNotNull('sppd_number')->whereYear('created_at', $now->year)->count() + 1;
        $sppdNumber = sprintf("SPPD/%04d/%02d/%03d", $now->year, $now->month, $count);

        $trip->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'sppd_number' => $sppdNumber,
            'admin_notes' => $request->input('admin_notes', 'Surat Tugas SPPD disetujui untuk dinas luar kota.'),
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'APPROVE_SPPD_BUSINESS_TRIP',
            'description' => "Menyetujui Perjalanan Dinas ({$trip->destination_city}) untuk {$trip->user->name} (SPPD: {$sppdNumber}).",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Surat Tugas SPPD Nomor {$sppdNumber} untuk {$trip->user->name} berhasil disetujui.");
    }

    /**
     * Employee Business Trips & Submission
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $trips = BusinessTrip::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('employee.business_trips.index', compact('trips', 'user'));
    }

    /**
     * Employee Store Business Trip Request
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_city' => 'required|string|max:100',
            'purpose' => 'required|string|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->input('start_date'));
        $end = Carbon::parse($request->input('end_date'));
        $totalDays = $start->diffInDays($end) + 1;
        $dailyRate = 350000.00; // Pagu uang harian standar per diem
        $totalAllowance = $totalDays * $dailyRate;

        BusinessTrip::create([
            'user_id' => Auth::id(),
            'sppd_number' => 'DRAFT-' . strtoupper(uniqid()),
            'destination_city' => $request->input('destination_city'),
            'purpose' => $request->input('purpose'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'total_days' => $totalDays,
            'daily_allowance_rate' => $dailyRate,
            'total_allowance' => $totalAllowance,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.business-trips.index')
            ->with('success', 'Permohonan perjalanan dinas berhasil diajukan untuk penerbitan SPPD resmi.');
    }

    /**
     * Show Official Printable SPPD Document
     */
    public function showPrint($id)
    {
        $trip = BusinessTrip::with(['user.department', 'approver'])->findOrFail($id);

        if ($trip->status !== 'approved') {
            abort(403, 'Surat Perintah Perjalanan Dinas belum disetujui.');
        }

        if (Auth::user()->role !== 'admin_hr' && Auth::id() !== $trip->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('business_trips.print', compact('trip'));
    }
}
