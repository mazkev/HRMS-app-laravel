<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('users')->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_threshold_time' => 'required',
            'description' => 'nullable|string',
        ]);

        Shift::create($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Jadwal shift baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required',
            'end_time' => 'required',
            'late_threshold_time' => 'required',
            'description' => 'nullable|string',
        ]);

        $shift->update($request->all());

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Jadwal shift berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Jadwal shift berhasil dihapus.');
    }
}
