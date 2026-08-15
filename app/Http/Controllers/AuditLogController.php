<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Admin Audit Trail View
     */
    public function index(Request $request)
    {
        $action = $request->input('action');
        $search = $request->input('search');

        $query = AuditLog::with('user');

        if ($action) {
            $query->where('action', $action);
        }

        if ($search) {
            $query->where('description', 'like', "%{$search}%");
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.audit.index', compact('logs', 'action', 'search'));
    }
}
