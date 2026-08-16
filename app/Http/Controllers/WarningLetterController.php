<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\WarningLetter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningLetterController extends Controller
{
    /**
     * Admin Warning Letters List
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        $query = WarningLetter::with(['user.department', 'issuer']);

        if ($status) {
            $query->where('status', $status);
        }

        $warningLetters = $query->latest()->paginate(15)->withQueryString();

        return view('admin.warning_letters.index', compact('warningLetters', 'employees', 'status'));
    }

    /**
     * Issue New Warning Letter (SP)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'level' => 'required|in:SP 1,SP 2,SP 3',
            'violation_type' => 'required|string|max:255',
            'description' => 'required|string',
            'issued_date' => 'required|date',
        ]);

        $issuedDate = Carbon::parse($request->input('issued_date'));
        $validUntil = $issuedDate->copy()->addMonths(6); // 6 bulan masa berlaku aktif

        // Generate Letter Number
        $count = WarningLetter::whereYear('issued_date', $issuedDate->year)->count() + 1;
        $letterNumber = sprintf("SP/%04d/%02d/%03d", $issuedDate->year, $issuedDate->month, $count);

        $sp = WarningLetter::create([
            'user_id' => $request->input('user_id'),
            'letter_number' => $letterNumber,
            'level' => $request->input('level'),
            'violation_type' => $request->input('violation_type'),
            'description' => $request->input('description'),
            'issued_date' => $issuedDate->toDateString(),
            'valid_until' => $validUntil->toDateString(),
            'issued_by' => Auth::id(),
            'status' => 'active',
        ]);

        $targetUser = User::find($request->input('user_id'));

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ISSUE_WARNING_LETTER',
            'description' => "Menerbitkan {$sp->level} Nomor {$letterNumber} untuk {$targetUser->name}.",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.warning-letters.index')
            ->with('success', "Surat Peringatan ({$sp->level}) untuk {$targetUser->name} berhasil diterbitkan dengan Nomor: {$letterNumber}.");
    }

    /**
     * Employee View Their Own Warning Letters
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $warningLetters = WarningLetter::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('employee.warning_letters.index', compact('warningLetters', 'user'));
    }

    /**
     * Show Official Printable Warning Letter Document
     */
    public function showPrint($id)
    {
        $letter = WarningLetter::with(['user.department', 'issuer'])->findOrFail($id);

        if (Auth::user()->role !== 'admin_hr' && Auth::id() !== $letter->user_id) {
            abort(403, 'Akses ditolak.');
        }

        return view('warning_letters.print', compact('letter'));
    }
}
