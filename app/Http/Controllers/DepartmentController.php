<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('users')->orderBy('name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string|max:1000',
        ]);

        Department::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $department->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $department = Department::withCount('users')->findOrFail($id);

        if ($department->users_count > 0) {
            return back()->with('error', "Departemen ini tidak dapat dihapus karena masih memiliki {$department->users_count} karyawan.");
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
