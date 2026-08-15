<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\CompanyAsset;
use App\Models\Department;
use App\Models\EmployeeDocument;
use App\Models\EmployeeLoan;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeaveRequest;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\PerformanceReview;
use App\Models\Reimbursement;
use App\Models\Shift;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Shifts
        $regularShift = Shift::create([
            'name' => 'Regular Office Hour',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'late_threshold_time' => '08:30:00',
            'description' => 'Jam kerja operasional standar kantor pusat.',
        ]);

        $morningShift = Shift::create([
            'name' => 'Shift 1 (Pagi)',
            'start_time' => '07:00:00',
            'end_time' => '15:30:00',
            'late_threshold_time' => '07:15:00',
            'description' => 'Shift pagi untuk staf operasional gudang dan customer support.',
        ]);

        $afternoonShift = Shift::create([
            'name' => 'Shift 2 (Siang)',
            'start_time' => '14:30:00',
            'end_time' => '22:30:00',
            'late_threshold_time' => '14:45:00',
            'description' => 'Shift siang untuk staf monitoring sistem dan logistik.',
        ]);

        // 2. Departments
        $hrDept = Department::create([
            'name' => 'Human Resources & General Affairs',
            'description' => 'Departemen pengelolaan sumber daya manusia, rekrutmen, dan operasional umum.',
        ]);

        $itDept = Department::create([
            'name' => 'Information Technology',
            'description' => 'Departemen pengembangan sistem informasi, software, dan infrastruktur IT.',
        ]);

        $financeDept = Department::create([
            'name' => 'Finance & Accounting',
            'description' => 'Departemen pengelolaan anggaran, akuntansi, dan keuangan perusahaan.',
        ]);

        $opsDept = Department::create([
            'name' => 'Operations & Logistics',
            'description' => 'Departemen operasional harian, supply chain, dan logistik.',
        ]);

        $mktDept = Department::create([
            'name' => 'Marketing & Sales',
            'description' => 'Departemen pemasaran produk, branding, dan ekspansi pasar.',
        ]);

        // 3. Admin HR User
        $admin = User::create([
            'nik' => 'HR001',
            'name' => 'Admin HRD PT Maju',
            'email' => 'admin@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'admin_hr',
            'department_id' => $hrDept->id,
            'shift_id' => $regularShift->id,
            'position' => 'HR Manager',
            'join_date' => '2022-01-10',
            'salary' => 18500000.00,
            'leave_quota' => 15,
            'phone' => '081234567890',
        ]);

        // 4. Employees
        $budi = User::create([
            'nik' => 'EMP001',
            'name' => 'Budi Santoso',
            'email' => 'budi@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $itDept->id,
            'shift_id' => $regularShift->id,
            'position' => 'Senior Software Engineer',
            'join_date' => '2023-02-15',
            'salary' => 16000000.00,
            'leave_quota' => 12,
            'phone' => '081298765432',
        ]);

        $siti = User::create([
            'nik' => 'EMP002',
            'name' => 'Siti Rahmawati',
            'email' => 'siti@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $financeDept->id,
            'shift_id' => $regularShift->id,
            'position' => 'Senior Financial Analyst',
            'join_date' => '2023-05-01',
            'salary' => 12500000.00,
            'leave_quota' => 10,
            'phone' => '081345678901',
        ]);

        $andi = User::create([
            'nik' => 'EMP003',
            'name' => 'Andi Pratama',
            'email' => 'andi@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $opsDept->id,
            'shift_id' => $morningShift->id,
            'position' => 'Operations Officer',
            'join_date' => '2023-08-10',
            'salary' => 9500000.00,
            'leave_quota' => 12,
            'phone' => '081456789012',
        ]);

        $dewi = User::create([
            'nik' => 'EMP004',
            'name' => 'Dewi Lestari',
            'email' => 'dewi@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $mktDept->id,
            'shift_id' => $regularShift->id,
            'position' => 'Marketing Specialist',
            'join_date' => '2024-01-15',
            'salary' => 10000000.00,
            'leave_quota' => 8,
            'phone' => '081567890123',
        ]);

        // 5. Attendances with GPS Coordinates
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        Attendance::create([
            'user_id' => $budi->id,
            'date' => $yesterday,
            'time_in' => '08:15:00',
            'time_out' => '17:05:00',
            'latitude' => -6.2088500,
            'longitude' => 106.8456200,
            'distance_meters' => 15,
            'is_office_radius' => true,
            'status' => 'present',
            'notes' => 'Hadir tepat waktu di kantor pusat.',
        ]);

        Attendance::create([
            'user_id' => $siti->id,
            'date' => $yesterday,
            'time_in' => '08:42:00',
            'time_out' => '17:15:00',
            'latitude' => -6.2089000,
            'longitude' => 106.8457000,
            'distance_meters' => 28,
            'is_office_radius' => true,
            'status' => 'late',
            'notes' => 'Terlambat karena kemacetan lalu lintas.',
        ]);

        Attendance::create([
            'user_id' => $siti->id,
            'date' => $today,
            'time_in' => '08:20:00',
            'latitude' => -6.2088200,
            'longitude' => 106.8456100,
            'distance_meters' => 12,
            'is_office_radius' => true,
            'status' => 'present',
        ]);

        // 6. Leave Requests
        LeaveRequest::create([
            'user_id' => $budi->id,
            'start_date' => Carbon::now()->addDays(3)->toDateString(),
            'end_date' => Carbon::now()->addDays(5)->toDateString(),
            'total_days' => 3,
            'reason' => 'Keperluan keluarga dan pengurusan dokumen resmi.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'user_id' => $dewi->id,
            'start_date' => Carbon::now()->subDays(10)->toDateString(),
            'end_date' => Carbon::now()->subDays(9)->toDateString(),
            'total_days' => 2,
            'reason' => 'Istirahat pasca rawat jalan.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui. Semoga lekas pulih.',
        ]);

        // 7. Overtime
        Overtime::create([
            'user_id' => $budi->id,
            'date' => $yesterday,
            'start_time' => '17:30:00',
            'end_time' => '20:30:00',
            'duration_hours' => 3.00,
            'reason' => 'Penyelesaian deployment hotfix server dan optimasi performa sistem.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui untuk rilis patch.',
        ]);

        // 8. Sample Reimbursements
        Reimbursement::create([
            'user_id' => $budi->id,
            'category' => 'transport',
            'title' => 'Taksi Kunjungan Data Center Cyber 2',
            'amount' => 175000.00,
            'description' => 'Perjalanan dinas darurat perbaikan server gateway.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui sesuai kuitansi.',
        ]);

        // 9. Performance Reviews
        PerformanceReview::create([
            'user_id' => $budi->id,
            'reviewer_id' => $admin->id,
            'period_year' => '2026',
            'period_quarter' => 'Q1',
            'kpi_score' => 92,
            'attendance_score' => 95,
            'teamwork_score' => 88,
            'final_grade' => 'A',
            'feedback' => 'Kontribusi sangat signifikan dalam pembaruan arsitektur HRMS dan sistem absensi biometrik.',
            'status' => 'final',
        ]);

        // 10. Announcements
        Announcement::create([
            'user_id' => $admin->id,
            'title' => 'Jadwal Libur Nasional & Cuti Bersama Idul Fitri 1447 H',
            'category' => 'holiday',
            'content' => "Diberitahukan kepada seluruh karyawan PT Maju Nusantara bahwa libur nasional dan cuti bersama akan berlangsung sesuai keputusan SKB 3 Menteri.\n\nBagi divisi yang bertugas piket on-call, mohon berkoordinasi dengan kepala departemen masing-masing.",
            'is_pinned' => true,
        ]);

        // 11. ATS & Job Postings
        $devJob = JobPosting::create([
            'title' => 'Senior Fullstack Engineer (Laravel & React)',
            'department_id' => $itDept->id,
            'type' => 'full_time',
            'experience_level' => '3 - 5 Tahun',
            'salary_min' => 15000000.00,
            'salary_max' => 22000000.00,
            'description' => 'Bertanggung jawab mengembangkan core application HRMS dan arsitektur enterprise microservices.',
            'requirements' => 'Keahlian mendalam dalam PHP, Laravel, MySQL, REST API, Tailwind CSS, dan Docker.',
            'status' => 'active',
        ]);

        $financeJob = JobPosting::create([
            'title' => 'Tax & Accounting Specialist',
            'department_id' => $financeDept->id,
            'type' => 'full_time',
            'experience_level' => '2 - 4 Tahun',
            'salary_min' => 10000000.00,
            'salary_max' => 14000000.00,
            'description' => 'Mengelola pelaporan PPh 21, rekonsiliasi bank, dan audit keuangan bulanan.',
            'requirements' => 'Lulusan Akuntansi, memiliki sertifikasi Brevet A & B.',
            'status' => 'active',
        ]);

        // Candidate Applications
        JobApplication::create([
            'job_posting_id' => $devJob->id,
            'candidate_name' => 'Rian Hidayat',
            'candidate_email' => 'rian.hidayat@example.com',
            'candidate_phone' => '081233445566',
            'status' => 'hired',
            'interview_date' => Carbon::now()->subDays(2)->toDateTimeString(),
            'notes' => 'Lulus tes koding dengan nilai 98/100. Rekomendasi offering letter disetujui.',
        ]);

        JobApplication::create([
            'job_posting_id' => $financeJob->id,
            'candidate_name' => 'Mega Puspita',
            'candidate_email' => 'mega.puspita@example.com',
            'candidate_phone' => '081277889900',
            'status' => 'interview',
            'interview_date' => Carbon::now()->addDays(2)->setTime(10, 0)->toDateTimeString(),
            'notes' => 'Jadwal interview user bersama HR Manager.',
        ]);

        // 12. Company Assets
        CompanyAsset::create([
            'user_id' => $budi->id,
            'asset_code' => 'AST-LAP-001',
            'name' => 'MacBook Pro 14 M3 Pro (18GB / 512GB SSD)',
            'category' => 'laptop',
            'serial_number' => 'C02G899XMD6T',
            'purchase_date' => '2024-01-10',
            'purchase_cost' => 32000000.00,
            'condition' => 'good',
            'status' => 'in_use',
            'assigned_date' => '2024-01-15',
            'notes' => 'Diserahterimakan lengkap dengan charger 70W dan laptop bag.',
        ]);

        CompanyAsset::create([
            'user_id' => $siti->id,
            'asset_code' => 'AST-LAP-002',
            'name' => 'Lenovo ThinkPad T14s Gen 4 (Core i7 / 16GB)',
            'category' => 'laptop',
            'serial_number' => 'PF39XX01',
            'purchase_date' => '2023-06-20',
            'purchase_cost' => 21000000.00,
            'condition' => 'good',
            'status' => 'in_use',
            'assigned_date' => '2023-07-01',
            'notes' => 'Laptop operasional divisi Finance.',
        ]);

        CompanyAsset::create([
            'user_id' => null,
            'asset_code' => 'AST-VEH-001',
            'name' => 'Toyota Avanza 1.5 G TSS (B 1234 MAJ)',
            'category' => 'vehicle',
            'serial_number' => 'MHKM12345678',
            'purchase_date' => '2023-03-10',
            'purchase_cost' => 265000000.00,
            'condition' => 'good',
            'status' => 'available',
            'notes' => 'Kendaraan dinas operasional kantor pusat.',
        ]);

        // 13. Employee Loans
        EmployeeLoan::create([
            'user_id' => $andi->id,
            'amount' => 3000000.00,
            'tenor_months' => 3,
            'monthly_installment' => 1000000.00,
            'remaining_amount' => 2000000.00,
            'reason' => 'Biaya renovasi atap rumah darurat.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'disbursed_at' => Carbon::now()->subMonth()->toDateString(),
            'admin_notes' => 'Disetujui. Telah terpotong 1x cicilan di payroll bulan lalu.',
        ]);

        // 14. Trainings
        $trainCloud = Training::create([
            'title' => 'Mastering Microservices with Docker & Kubernetes',
            'trainer_name' => 'Hendra Wijaya, Solution Architect',
            'category' => 'Engineering & Cloud',
            'start_date' => Carbon::now()->addDays(7)->toDateString(),
            'end_date' => Carbon::now()->addDays(8)->toDateString(),
            'location' => 'Training Room Lt. 3 & Zoom',
            'capacity' => 15,
            'description' => 'Pelatihan intensif containerization, autoscaling, dan CI/CD deployment pipeline.',
            'status' => 'upcoming',
        ]);

        TrainingParticipant::create([
            'training_id' => $trainCloud->id,
            'user_id' => $budi->id,
            'status' => 'enrolled',
        ]);

        // 15. Audit Logs
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INITIALIZATION',
            'description' => 'Menginisialisasi HRMS Tier-1 Global Enterprise Suite dengan modul ATS, Asset, Loan, dan Training.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        // 16. Payroll Records
        $prevMonth = Carbon::now()->subMonth()->format('Y-m');
        foreach ([$budi, $siti, $andi, $dewi] as $emp) {
            Payroll::create([
                'user_id' => $emp->id,
                'period_month' => $prevMonth,
                'basic_salary' => $emp->salary,
                'allowances' => 500000.00,
                'late_deduction' => 0.00,
                'other_deductions' => ($emp->id === $andi->id) ? 1000000.00 : 0.00, // Loan deduction
                'net_salary' => $emp->salary + 500000.00 - (($emp->id === $andi->id) ? 1000000.00 : 0.00),
                'total_present_days' => 22,
                'total_late_days' => 0,
                'status' => 'paid',
                'payment_date' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'notes' => 'Gaji bulan ' . Carbon::now()->subMonth()->translatedFormat('F Y'),
            ]);
        }
    }
}
