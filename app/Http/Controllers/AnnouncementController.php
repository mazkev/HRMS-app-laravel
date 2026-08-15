<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display Announcements Board (Admin & Employee)
     */
    public function index()
    {
        $announcements = Announcement::with('author')
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Admin Store Announcement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:general,holiday,policy,event',
            'content' => 'required|string',
            'is_pinned' => 'nullable|boolean',
        ]);

        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'content' => $request->input('content'),
            'is_pinned' => $request->has('is_pinned'),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman resmi perusahaan berhasil dipublikasikan.');
    }

    /**
     * Admin Delete Announcement
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
