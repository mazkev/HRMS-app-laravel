<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Document Vault Index
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        if ($user->role === 'admin_hr') {
            $selectedUserId = $request->input('user_id');
            $query = EmployeeDocument::with(['user.department', 'uploader']);

            if ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            }

            $documents = $query->latest()->paginate(15)->withQueryString();
        } else {
            $documents = EmployeeDocument::where('user_id', $user->id)
                ->latest()
                ->paginate(15);
            $selectedUserId = null;
        }

        return view('documents.index', compact('documents', 'employees', 'selectedUserId'));
    }

    /**
     * Store Document
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'document_type' => 'required|string',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // max 5MB
        ]);

        $targetUserId = Auth::user()->role === 'admin_hr'
            ? ($request->input('user_id') ?? Auth::id())
            : Auth::id();

        $path = $request->file('file')->store('documents', 'public');

        EmployeeDocument::create([
            'user_id' => $targetUserId,
            'document_type' => $request->input('document_type'),
            'title' => $request->input('title'),
            'file_path' => $path,
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah ke Digital Document Vault.');
    }

    /**
     * Delete Document
     */
    public function destroy($id)
    {
        $doc = EmployeeDocument::findOrFail($id);

        if (Auth::user()->role !== 'admin_hr' && $doc->user_id !== Auth::id()) {
            abort(403);
        }

        if (Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $doc->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
