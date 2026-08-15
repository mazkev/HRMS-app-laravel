<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecruitmentController extends Controller
{
    /**
     * Admin Recruitment & ATS Pipeline Overview
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $jobId = $request->input('job_id');

        $jobs = JobPosting::withCount('applications')->get();
        $departments = Department::orderBy('name')->get();

        $query = JobApplication::with('jobPosting.department');

        if ($status) {
            $query->where('status', $status);
        }

        if ($jobId) {
            $query->where('job_posting_id', $jobId);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.recruitment.index', compact('applications', 'jobs', 'departments', 'status', 'jobId'));
    }

    /**
     * Store New Job Posting
     */
    public function storeJob(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'type' => 'required|in:full_time,contract,internship,remote',
            'experience_level' => 'required|string',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        JobPosting::create($request->all());

        return redirect()->route('admin.recruitment.index')
            ->with('success', 'Lowongan pekerjaan baru berhasil dipublikasikan.');
    }

    /**
     * Update Candidate Application Status in Pipeline
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:applied,screening,interview,offering,hired,rejected',
            'interview_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $app = JobApplication::findOrFail($id);
        $app->update([
            'status' => $request->input('status'),
            'interview_date' => $request->input('interview_date'),
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', "Status kandidat {$app->candidate_name} diperbarui menjadi: " . strtoupper($app->status));
    }

    /**
     * 1-Click Convert Hired Candidate to Official Employee
     */
    public function convertToEmployee($id)
    {
        $app = JobApplication::with('jobPosting')->findOrFail($id);

        if ($app->status !== 'hired') {
            return back()->with('error', 'Hanya kandidat dengan status "HIRED" yang dapat dikonversi menjadi karyawan.');
        }

        // Check if email already exists
        if (User::where('email', $app->candidate_email)->exists()) {
            return back()->with('error', 'Email kandidat sudah terdaftar sebagai akun karyawan.');
        }

        // Generate NIK (EMP + random 3 digits)
        $latestUser = User::latest('id')->first();
        $nextId = $latestUser ? ($latestUser->id + 1) : 1;
        $nik = 'EMP' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $employee = User::create([
            'nik' => $nik,
            'name' => $app->candidate_name,
            'email' => $app->candidate_email,
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $app->jobPosting->department_id,
            'position' => $app->jobPosting->title,
            'join_date' => now()->toDateString(),
            'salary' => $app->jobPosting->salary_min ?? 8000000.00,
            'leave_quota' => 12,
            'phone' => $app->candidate_phone,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CONVERT_CANDIDATE_TO_EMPLOYEE',
            'description' => "Mengonversi pelamar {$app->candidate_name} menjadi karyawan resmi (NIK: {$nik}).",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', "Kandidat {$app->candidate_name} berhasil diangkat menjadi Karyawan resmi! NIK: {$nik}, Password default: password.");
    }
}
