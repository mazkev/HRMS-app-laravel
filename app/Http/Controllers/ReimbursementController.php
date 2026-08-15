<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReimbursementController extends Controller
{
    /**
     * Employee Reimbursement List & Submission
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $reimbursements = Reimbursement::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('employee.reimbursement.index', compact('reimbursements', 'user'));
    }

    /**
     * Store Reimbursement Request
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:transport,medical,meal,office_supplies,other',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|min:5',
            'receipt' => 'nullable|image|max:3072', // max 3MB image
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Reimbursement::create([
            'user_id' => Auth::id(),
            'category' => $request->input('category'),
            'title' => $request->input('title'),
            'amount' => $request->input('amount'),
            'receipt_image' => $receiptPath,
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        return redirect()->route('employee.reimbursement.index')
            ->with('success', 'Klaim reimbursement berhasil diajukan dan menunggu verifikasi tim HR Finance.');
    }

    /**
     * Admin Reimbursement Review Queue
     */
    public function adminIndex(Request $request)
    {
        $status = $request->input('status');
        $category = $request->input('category');

        $query = Reimbursement::with(['user.department', 'approver']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $reimbursements = $query->latest()->paginate(15)->withQueryString();
        $totalPending = Reimbursement::where('status', 'pending')->sum('amount');

        return view('admin.reimbursement.index', compact('reimbursements', 'status', 'category', 'totalPending'));
    }

    /**
     * Approve Reimbursement
     */
    public function approve(Request $request, $id)
    {
        $claim = Reimbursement::findOrFail($id);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        $claim->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes', 'Disetujui untuk pencairan.'),
        ]);

        return back()->with('success', "Klaim reimbursement {$claim->user->name} sebesar Rp " . number_format($claim->amount, 0, ',', '.') . " berhasil disetujui.");
    }

    /**
     * Reject Reimbursement
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $claim = Reimbursement::findOrFail($id);

        if ($claim->status !== 'pending') {
            return back()->with('error', 'Klaim ini sudah diproses sebelumnya.');
        }

        $claim->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'Klaim reimbursement berhasil ditolak.');
    }
}
