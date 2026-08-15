<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Show Attendance Page for Employee (Camera Clock-In / Clock-Out)
     */
    public function employeeIndex(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();

        $history = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        return view('employee.attendance.index', compact('todayAttendance', 'history', 'month'));
    }

    /**
     * Handle Clock-In with Camera Snapshot
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // base64 data url
            'notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $existing = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existing && $existing->time_in) {
            return back()->with('error', 'Anda sudah melakukan Absen Masuk (Clock-in) untuk hari ini.');
        }

        $imagePath = $this->saveBase64Image($request->input('image'), 'clock_in');

        // Status calculation (Cut-off late: 08:30:00)
        $lateThreshold = Carbon::today()->setTime(8, 30, 0);
        $status = $now->greaterThan($lateThreshold) ? 'late' : 'present';

        Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'time_in' => $now->toTimeString(),
                'image_in' => $imagePath,
                'status' => $status,
                'notes' => $request->input('notes'),
            ]
        );

        $statusLabel = $status === 'late' ? 'Terlambat' : 'Tepat Waktu';
        return redirect()->route('employee.attendance.index')
            ->with('success', "Absen Masuk berhasil dicatat! Status: {$statusLabel} ({$now->format('H:i:s')}).");
    }

    /**
     * Handle Clock-Out with Camera Snapshot
     */
    public function clockOut(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // base64 data url
        ]);

        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
            return back()->with('error', 'Anda belum melakukan Absen Masuk untuk hari ini.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'Anda sudah melakukan Absen Pulang (Clock-out) hari ini.');
        }

        $imagePath = $this->saveBase64Image($request->input('image'), 'clock_out');

        $attendance->update([
            'time_out' => $now->toTimeString(),
            'image_out' => $imagePath,
        ]);

        return redirect()->route('employee.attendance.index')
            ->with('success', "Absen Pulang berhasil dicatat! Jam: {$now->format('H:i:s')}.");
    }

    /**
     * Admin Attendance Logs & Photo Verification
     */
    public function adminIndex(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $departmentId = $request->input('department_id');
        $status = $request->input('status');
        $search = $request->input('search');

        $departments = Department::orderBy('name')->get();

        $query = Attendance::with(['user.department'])
            ->where('date', $date);

        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $attendances = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.attendance.index', compact('attendances', 'departments', 'date', 'departmentId', 'status', 'search'));
    }

    /**
     * Helper to store base64 image data to public storage
     */
    private function saveBase64Image(string $base64Data, string $prefix): string
    {
        // Strip out data-uri prefix if present (e.g. data:image/jpeg;base64,)
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, etc.
        } else {
            $type = 'jpg';
        }

        $imageData = base64_decode($base64Data);
        $fileName = 'attendances/' . $prefix . '_' . Auth::id() . '_' . time() . '_' . Str::random(8) . '.' . $type;

        Storage::disk('public')->put($fileName, $imageData);

        return $fileName;
    }
}
