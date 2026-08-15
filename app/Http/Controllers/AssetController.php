<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CompanyAsset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Admin Asset Manager
     */
    public function adminIndex(Request $request)
    {
        $category = $request->input('category');
        $status = $request->input('status');
        $search = $request->input('search');

        $employees = User::where('role', 'employee')->orderBy('name')->get();
        $query = CompanyAsset::with('user.department');

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $assets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.assets.index', compact('assets', 'employees', 'category', 'status', 'search'));
    }

    /**
     * Store New Asset
     */
    public function store(Request $request)
    {
        $request->validate([
            'asset_code' => 'required|string|unique:company_assets,asset_code|max:50',
            'name' => 'required|string|max:255',
            'category' => 'required|in:laptop,vehicle,monitor,furniture,device,other',
            'serial_number' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric',
            'condition' => 'required|in:good,fair,damaged,maintenance',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $status = $request->input('user_id') ? 'in_use' : 'available';
        $assignedDate = $request->input('user_id') ? now()->toDateString() : null;

        CompanyAsset::create(array_merge($request->all(), [
            'status' => $status,
            'assigned_date' => $assignedDate,
        ]));

        return redirect()->route('admin.assets.index')
            ->with('success', 'Data aset perusahaan berhasil didaftarkan.');
    }

    /**
     * Assign / Return Asset
     */
    public function assign(Request $request, $id)
    {
        $asset = CompanyAsset::findOrFail($id);
        $userId = $request->input('user_id');

        if ($userId) {
            $asset->update([
                'user_id' => $userId,
                'status' => 'in_use',
                'assigned_date' => now()->toDateString(),
            ]);
            $msg = "Aset {$asset->name} berhasil diserahterimakan ke karyawan.";
        } else {
            $asset->update([
                'user_id' => null,
                'status' => 'available',
                'assigned_date' => null,
            ]);
            $msg = "Aset {$asset->name} berhasil dikembalikan ke inventaris kantor.";
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'ASSET_ASSIGNMENT_UPDATE',
            'description' => $msg,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', $msg);
    }

    /**
     * Employee Assets View
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $assets = CompanyAsset::where('user_id', $user->id)->get();

        return view('employee.assets.index', compact('assets', 'user'));
    }
}
