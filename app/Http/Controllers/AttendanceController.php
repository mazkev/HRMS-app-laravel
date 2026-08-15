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
    // PT Maju Office Coordinates
    const OFFICE_LAT = -6.2088000;
    const OFFICE_LNG = 106.8456000;
    const OFFICE_RADIUS_METERS = 250;

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

        $officeLat = self::OFFICE_LAT;
        $officeLng = self::OFFICE_LNG;
        $officeRadius = self::OFFICE_RADIUS_METERS;

        return view('employee.attendance.index', compact('todayAttendance', 'history', 'month', 'officeLat', 'officeLng', 'officeRadius'));
    }

    /**
     * Handle Clock-In with Camera Snapshot & GPS Geofencing
     */
    public function clockIn(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // base64 data url
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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

        // GPS Calculation
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $distance = null;
        $isWithinRadius = true;

        if ($lat && $lng) {
            $distance = (int) round($this->calculateDistance($lat, $lng, self::OFFICE_LAT, self::OFFICE_LNG));
            $isWithinRadius = ($distance <= self::OFFICE_RADIUS_METERS);
        }

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
                'latitude' => $lat,
                'longitude' => $lng,
                'distance_meters' => $distance,
                'is_office_radius' => $isWithinRadius,
                'status' => $status,
                'notes' => $request->input('notes'),
            ]
        );

        $statusLabel = $status === 'late' ? 'Terlambat' : 'Tepat Waktu';
        $locationMsg = $distance !== null ? " (Jarak GPS: {$distance}m)" : "";
        return redirect()->route('employee.attendance.index')
            ->with('success', "Absen Masuk berhasil dicatat! Status: {$statusLabel} ({$now->format('H:i:s')}){$locationMsg}.");
    }

    /**
     * Handle Clock-Out with Camera Snapshot
     */
    public function clockOut(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
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
     * Calculate Distance between coordinates in meters using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // in meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Helper to store base64 image data to public storage
     */
    private function saveBase64Image(string $base64Data, string $prefix): string
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);
        } else {
            $type = 'jpg';
        }

        $imageData = base64_decode($base64Data);
        $fileName = 'attendances/' . $prefix . '_' . Auth::id() . '_' . time() . '_' . Str::random(8) . '.' . $type;

        Storage::disk('public')->put($fileName, $imageData);

        return $fileName;
    }
}
