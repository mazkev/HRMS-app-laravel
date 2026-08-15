<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display listing of employees
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $departmentId = $request->input('department_id');

        $departments = Department::orderBy('name')->get();

        $query = User::with('department')->where('role', 'employee');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.employees.index', compact('employees', 'departments', 'search', 'departmentId'));
    }

    /**
     * Show create employee form
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.employees.create', compact('departments'));
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:users,nik|max:50',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'leave_quota' => 'required|integer|min:0|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        User::create([
            'nik' => $request->input('nik'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'employee',
            'department_id' => $request->input('department_id'),
            'position' => $request->input('position'),
            'join_date' => $request->input('join_date'),
            'salary' => $request->input('salary'),
            'leave_quota' => $request->input('leave_quota'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', "Karyawan {$request->input('name')} berhasil ditambahkan.");
    }

    /**
     * Show edit employee form
     */
    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        return view('admin.employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update employee
     */
    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $request->validate([
            'nik' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($employee->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($employee->id)],
            'password' => 'nullable|string|min:6',
            'department_id' => 'nullable|exists:departments,id',
            'position' => 'required|string|max:255',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'leave_quota' => 'required|integer|min:0|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'nik' => $request->input('nik'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'department_id' => $request->input('department_id'),
            'position' => $request->input('position'),
            'join_date' => $request->input('join_date'),
            'salary' => $request->input('salary'),
            'leave_quota' => $request->input('leave_quota'),
            'phone' => $request->input('phone'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', "Data karyawan {$employee->name} berhasil diperbarui.");
    }

    /**
     * Remove employee
     */
    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', "Karyawan {$name} berhasil dihapus.");
    }
}
