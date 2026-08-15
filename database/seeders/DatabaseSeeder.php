<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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

        // 4. Sample Attendances
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        // Yesterday attendance
        Attendance::create([
            'user_id' => $budi->id,
            'date' => $yesterday,
            'time_in' => '08:15:00',
            'time_out' => '17:05:00',
            'status' => 'present',
            'notes' => 'Hadir tepat waktu di kantor pusat.',
        ]);

        Attendance::create([
            'user_id' => $siti->id,
            'date' => $yesterday,
            'time_in' => '08:42:00',
            'time_out' => '17:15:00',
            'status' => 'late',
            'notes' => 'Terlambat karena macet lalu lintas.',
        ]);

        Attendance::create([
            'user_id' => $andi->id,
            'date' => $yesterday,
            'time_in' => '08:10:00',
            'time_out' => '17:00:00',
            'status' => 'present',
        ]);

        // Today attendance for some employees
        Attendance::create([
            'user_id' => $siti->id,
            'date' => $today,
            'time_in' => '08:20:00',
            'status' => 'present',
        ]);

        Attendance::create([
            'user_id' => $andi->id,
            'date' => $today,
            'time_in' => '08:50:00',
            'status' => 'late',
            'notes' => 'Hujan lebat di jalan.',
        ]);

        // 5. Sample Leave Requests
        LeaveRequest::create([
            'user_id' => $budi->id,
            'start_date' => Carbon::now()->addDays(3)->toDateString(),
            'end_date' => Carbon::now()->addDays(5)->toDateString(),
            'total_days' => 3,
            'reason' => 'Keperluan keluarga di luar kota dan perpanjangan dokumen pribadi.',
            'status' => 'pending',
        ]);

        LeaveRequest::create([
            'user_id' => $dewi->id,
            'start_date' => Carbon::now()->subDays(10)->toDateString(),
            'end_date' => Carbon::now()->subDays(9)->toDateString(),
            'total_days' => 2,
            'reason' => 'Istirahat pasca rawat inap di RS.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui. Semoga lekas pulih dan sehat selalu.',
        ]);

        LeaveRequest::create([
            'user_id' => $andi->id,
            'start_date' => Carbon::now()->subDays(20)->toDateString(),
            'end_date' => Carbon::now()->subDays(18)->toDateString(),
            'total_days' => 3,
            'reason' => 'Liburan akhir pekan panjang bersama keluarga.',
            'status' => 'approved',
            'approved_by' => $admin->id,
            'admin_notes' => 'Disetujui.',
        ]);
    }
}
