<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    /**
     * Admin Performance Management List
     */
    public function adminIndex(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $quarter = $request->input('quarter');
        $departmentId = $request->input('department_id');

        $departments = Department::orderBy('name')->get();
        $employees = User::where('role', 'employee')->orderBy('name')->get();

        $query = PerformanceReview::with(['user.department', 'reviewer'])
            ->where('period_year', $year);

        if ($quarter) {
            $query->where('period_quarter', $quarter);
        }

        if ($departmentId) {
            $query->whereHas('user', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.performance.index', compact('reviews', 'departments', 'employees', 'year', 'quarter', 'departmentId'));
    }

    /**
     * Admin Store Performance Review
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_year' => 'required|digits:4',
            'period_quarter' => 'required|string',
            'kpi_score' => 'required|integer|min:0|max:100',
            'attendance_score' => 'required|integer|min:0|max:100',
            'teamwork_score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $kpi = $request->input('kpi_score');
        $att = $request->input('attendance_score');
        $team = $request->input('teamwork_score');

        // Calculate weighted average (50% KPI, 30% Attendance, 20% Teamwork)
        $finalScore = round(($kpi * 0.5) + ($att * 0.3) + ($team * 0.2));

        if ($finalScore >= 85) {
            $grade = 'A';
        } elseif ($finalScore >= 75) {
            $grade = 'B';
        } elseif ($finalScore >= 60) {
            $grade = 'C';
        } else {
            $grade = 'D';
        }

        PerformanceReview::updateOrCreate(
            [
                'user_id' => $request->input('user_id'),
                'period_year' => $request->input('period_year'),
                'period_quarter' => $request->input('period_quarter'),
            ],
            [
                'reviewer_id' => Auth::id(),
                'kpi_score' => $kpi,
                'attendance_score' => $att,
                'teamwork_score' => $team,
                'final_grade' => $grade,
                'feedback' => $request->input('feedback'),
                'status' => 'final',
            ]
        );

        return redirect()->route('admin.performance.index')
            ->with('success', "Penilaian KPI berhasil disimpan dengan predikat Grade {$grade}.");
    }

    /**
     * Employee Scorecard View
     */
    public function employeeIndex()
    {
        $user = Auth::user();
        $reviews = PerformanceReview::with('reviewer')
            ->where('user_id', $user->id)
            ->where('status', 'final')
            ->orderBy('period_year', 'desc')
            ->orderBy('period_quarter', 'desc')
            ->paginate(10);

        return view('employee.performance.index', compact('reviews', 'user'));
    }
}
