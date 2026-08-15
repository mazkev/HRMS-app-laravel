<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Overtime;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments
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

        // 2. Admin HR User
        $admin = User::create([
            'nik' => 'HR001',
            'name' => 'Admin HRD PT Maju',
            'email' => 'admin@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'admin_hr',
            'department_id' => $hrDept->id,
            'position' => 'HR Manager',
            'join_date' => '2022-01-10',
            'salary' => 18500000.00,
            'leave_quota' => 15,
            'phone' => '081234567890',
        ]);

        // 3. Employees
        $budi = User::create([
            'nik' => 'EMP001',
            'name' => 'Budi Santoso',
            'email' => 'budi@hrms.local',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $itDept->id,
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
            'position' => 'Marketing Specialist',
            'join_date' => '2024-01-15',
            'salary' => 10000000.00,
            'leave_quota' => 8,
            'phone' => '081567890123',
        ]);

        // 4. Attendances with GPS Coordinates
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
            'notes' => 'Terlambat karena macet lalu lintas.',
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

        Attendance::create([
            'user_id' => $andi->id,
            'date' => $today,
            'time_in' => '08:50:00',
            'latitude' => -6.2088000,
            'longitude' => 106.8456000,
            'distance_meters' => 5,
            'is_office_radius' => true,
            'status' => 'late',
            'notes' => 'Hujan lebat di jalan.',
        ]);

        // 5. Leave Requests
        LeaveRequest::create([
            'user_id' => $budi->id,
            'start_date' => Carbon::now()->addDays(3)->toDateString(),
            'end_date' => Carbon::now()->addDays(5)->toDateString(),
            'total_days' => 3,
            'reason' => 'Keperluan keluarga dan pengurusan dokumen.',
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

        // 6. Overtime Requests
        Overtime::create([
            'user_id' => $budi->id,
            'date' => $yesterday,
            'start_time' => '17:30:00',
            'end_time' => '20:30:00',
            'duration_hours' => 3.00,
            'reason' => 'Penyelesaian deployment hotfix server dan optimasi database.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui untuk rilis patch v1.2.',
        ]);

        Overtime::create([
            'user_id' => $andi->id,
            'date' => $yesterday,
            'start_time' => '17:30:00',
            'end_time' => '19:30:00',
            'duration_hours' => 2.00,
            'reason' => 'Pengecekan stok gudang dan audit inventaris logistik.',
            'status' => 'pending',
        ]);

        // 7. Sample Payroll Records
        $currentMonth = Carbon::now()->format('Y-m');
        $prevMonth = Carbon::now()->subMonth()->format('Y-m');

        foreach ([$budi, $siti, $andi, $dewi] as $emp) {
            Payroll::create([
                'user_id' => $emp->id,
                'period_month' => $prevMonth,
                'basic_salary' => $emp->salary,
                'allowances' => 500000.00,
                'late_deduction' => 0.00,
                'other_deductions' => 0.00,
                'net_salary' => $emp->salary + 500000.00,
                'total_present_days' => 22,
                'total_late_days' => 0,
                'status' => 'paid',
                'payment_date' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
                'notes' => 'Gaji bulan ' . Carbon::now()->subMonth()->translatedFormat('F Y'),
            ]);
        }
    }
}
