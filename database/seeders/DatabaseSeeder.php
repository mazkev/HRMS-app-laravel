<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\BusinessTrip;
use App\Models\CompanyAsset;
use App\Models\Department;
use App\Models\EmployeeDocument;
use App\Models\EmployeeLoan;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeaveRequest;
use App\Models\NotificationLog;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\PeerKudos;
use App\Models\PerformanceReview;
use App\Models\Reimbursement;
use App\Models\Resignation;
use App\Models\Shift;
use App\Models\ShiftSwap;
use App\Models\ThrPayment;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use App\Models\WarningLetter;
use App\Services\TaxBpjsCalculator;
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
            'ptkp_status' => 'K/1',
            'npwp' => '09.123.456.7-012.000',
            'bpjs_kesehatan_no' => '0001234567890',
            'bpjs_ketenagakerjaan_no' => '21098765432',
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
            'ptkp_status' => 'TK/0',
            'npwp' => '08.765.432.1-012.000',
            'bpjs_kesehatan_no' => '0001234567891',
            'bpjs_ketenagakerjaan_no' => '21098765433',
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
            'ptkp_status' => 'K/0',
            'npwp' => '07.654.321.0-012.000',
            'bpjs_kesehatan_no' => '0001234567892',
            'bpjs_ketenagakerjaan_no' => '21098765434',
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
            'ptkp_status' => 'TK/1',
            'npwp' => '06.543.210.9-012.000',
            'bpjs_kesehatan_no' => '0001234567893',
            'bpjs_ketenagakerjaan_no' => '21098765435',
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
            'ptkp_status' => 'TK/0',
            'npwp' => '05.432.109.8-012.000',
            'bpjs_kesehatan_no' => '0001234567894',
            'bpjs_ketenagakerjaan_no' => '21098765436',
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

        // 6. Leave Requests (Annual & Sick with SKD)
        LeaveRequest::create([
            'user_id' => $budi->id,
            'leave_type' => 'annual',
            'start_date' => Carbon::now()->addDays(3)->toDateString(),
            'end_date' => Carbon::now()->addDays(5)->toDateString(),
            'total_days' => 3,
            'reason' => 'Keperluan keluarga dan pengurusan dokumen resmi.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'user_id' => $dewi->id,
            'leave_type' => 'sick',
            'start_date' => Carbon::now()->subDays(10)->toDateString(),
            'end_date' => Carbon::now()->subDays(9)->toDateString(),
            'total_days' => 2,
            'reason' => 'Istirahat medis pasca rawat jalan (Flu berat).',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui. Bukti SKD terverifikasi.',
        ]);

        // 7. Business Trips (SPPD)
        BusinessTrip::create([
            'user_id' => $budi->id,
            'sppd_number' => 'SPPD/2026/08/001',
            'destination_city' => 'Surabaya',
            'purpose' => 'Audit infrastruktur server cloud cabang Jawa Timur dan instalasi disaster recovery system.',
            'start_date' => Carbon::now()->addDays(5)->toDateString(),
            'end_date' => Carbon::now()->addDays(8)->toDateString(),
            'total_days' => 4,
            'daily_allowance_rate' => 350000.00,
            'total_allowance' => 1400000.00,
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui. Tiket dan hotel dipersiapkan oleh tim GA.',
        ]);

        // 8. Shift Swaps
        ShiftSwap::create([
            'requester_id' => $andi->id,
            'target_user_id' => $budi->id,
            'swap_date' => Carbon::now()->addDays(2)->toDateString(),
            'requester_shift_id' => $morningShift->id,
            'target_shift_id' => $regularShift->id,
            'reason' => 'Ada keperluan keluarga mendesak di pagi hari.',
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);

        // 9. THR Payments
        foreach ([$budi, $siti, $andi, $dewi] as $emp) {
            $joinDate = Carbon::parse($emp->join_date);
            $tenure = max(1, $joinDate->diffInMonths(Carbon::now()));
            $salary = (float) $emp->salary;
            $thrAmount = ($tenure >= 12) ? $salary : round(($tenure / 12) * $salary, 2);

            ThrPayment::create([
                'user_id' => $emp->id,
                'year' => '2026',
                'holiday_name' => 'Idul Fitri 1447 H',
                'tenure_months' => $tenure,
                'basic_salary' => $salary,
                'thr_amount' => $thrAmount,
                'payment_date' => Carbon::now()->toDateString(),
                'status' => 'paid',
                'notes' => ($tenure >= 12) ? 'Masa kerja >= 12 bulan (1x Gaji Penuh)' : "Masa kerja {$tenure} bulan (Pro-rata Kemnaker)",
            ]);
        }

        // 10. Peer Kudos
        PeerKudos::create([
            'sender_id' => $admin->id,
            'receiver_id' => $budi->id,
            'badge_type' => 'problem_solver',
            'message' => 'Luar biasa dalam mengimplementasikan sistem absensi biometrik & GPS yang sangat handal!',
        ]);

        PeerKudos::create([
            'sender_id' => $budi->id,
            'receiver_id' => $siti->id,
            'badge_type' => 'team_player',
            'message' => 'Terima kasih atas bantuan rekonsiliasi data payroll dan perhitungan pajak PPh 21 TER yang sangat rapi.',
        ]);

        PeerKudos::create([
            'sender_id' => $siti->id,
            'receiver_id' => $budi->id,
            'badge_type' => 'innovator',
            'message' => 'Fitur HRMS baru sangat memudahkan pekerjaan divisi finance dan karyawan lainnya!',
        ]);

        // 11. Notification Gateway Logs
        NotificationLog::create([
            'user_id' => $budi->id,
            'channel' => 'whatsapp',
            'recipient' => $budi->phone,
            'subject' => 'Slip Gaji Bulan Ini Telah Diterbitkan 📄',
            'message' => 'Halo Budi Santoso, slip gaji periode bulan berjalan Anda telah resmi diterbitkan oleh HRD.',
            'status' => 'sent',
        ]);

        // 12. Warning Letters (Disciplinary SP)
        WarningLetter::create([
            'user_id' => $andi->id,
            'letter_number' => 'SP/2026/08/001',
            'level' => 'SP 1',
            'violation_type' => 'Keterlambatan Berulang Shift Pagi',
            'description' => 'Tercatat mengalami keterlambatan hadir lebih dari 5 kali dalam periode 1 bulan.',
            'issued_date' => Carbon::now()->subMonth()->toDateString(),
            'valid_until' => Carbon::now()->addMonths(5)->toDateString(),
            'issued_by' => $admin->id,
            'status' => 'active',
        ]);

        // 13. Resignations & Paklaring
        Resignation::create([
            'user_id' => $dewi->id,
            'notice_date' => Carbon::now()->subDays(15)->toDateString(),
            'resign_date' => Carbon::now()->addDays(15)->toDateString(),
            'reason' => 'Melanjutkan studi magister (S2) ke luar negeri.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'paklaring_number' => 'PKL/2026/08/001',
            'exit_clearance_notes' => 'Handover tugas marketing telah selesai. Surat paklaring diterbitkan.',
        ]);

        // 14. Overtime
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

        // 15. Reimbursements
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

        // 16. Employee Loans
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

        // 17. Trainings
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

        // 18. Audit Logs
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INITIALIZATION',
            'description' => 'Menginisialisasi HRMS Premier Enterprise Suite dengan THR, SPPD, Tukar Shift, WhatsApp Gateway, dan Kudos Wall of Fame.',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
        ]);

        // 19. Payroll Records with PPh 21 TER and BPJS
        $prevMonth = Carbon::now()->subMonth()->format('Y-m');
        foreach ([$budi, $siti, $andi, $dewi] as $emp) {
            $basicSalary = (float) $emp->salary;
            $allowances = 500000.00;
            $gross = $basicSalary + $allowances;

            $pph21 = TaxBpjsCalculator::calculatePph21($gross, $emp->ptkp_status ?? 'TK/0');
            $bpjsKes = TaxBpjsCalculator::calculateBpjsKesehatan($basicSalary);
            $bpjsTk = TaxBpjsCalculator::calculateBpjsTk($basicSalary);
            $loanDed = ($emp->id === $andi->id) ? 1000000.00 : 0.00;

            $totalDeductions = $pph21 + $bpjsKes + $bpjsTk + $loanDed;
            $netSalary = $gross - $totalDeductions;

            Payroll::create([
                'user_id' => $emp->id,
                'period_month' => $prevMonth,
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'pph21_amount' => $pph21,
                'bpjs_kesehatan_deduction' => $bpjsKes,
                'bpjs_tk_deduction' => $bpjsTk,
                'loan_deduction' => $loanDed,
                'late_deduction' => 0.00,
                'other_deductions' => 0.00,
                'net_salary' => $netSalary,
                'total_present_days' => 22,
                'total_late_days' => 0,
                'status' => 'paid',
                'payment_date' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'notes' => 'Gaji bulan ' . Carbon::now()->subMonth()->translatedFormat('F Y'),
            ]);
        }
    }
}
