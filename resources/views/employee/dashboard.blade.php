@extends('layouts.employee_app')

@section('title', 'Beranda Karyawan')
@section('page-title', 'Selamat Datang! 👋')
@section('page-subtitle', 'Pantau kehadiran, cuti, slip gaji, dan tugas Anda')

@section('content')
<div class="space-y-4">

    <!-- 1. LIVE CLOCK-IN & QUICK ATTENDANCE HERO CARD -->
    <div class="saas-card p-5 bg-gradient-to-br from-white via-slate-50 to-blue-50/40 border border-slate-200/80 shadow-md relative overflow-hidden">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Presensi Hari Ini</span>
            </div>
            <span class="text-[11px] font-mono font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                {{ now()->translatedFormat('l, d M Y') }}
            </span>
        </div>

        <div class="flex items-center justify-between py-2">
            <div>
                <p class="text-[11px] text-slate-500">Status Kehadiran:</p>
                @if($todayAttendance)
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Sudah Masuk ({{ substr($todayAttendance->time_in, 0, 5) }})
                        </span>
                    </div>
                @else
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                            <i class="fa-solid fa-clock text-amber-600"></i> Belum Melakukan Absen
                        </span>
                    </div>
                @endif
            </div>

            <!-- Quick Action Button to Camera Attendance -->
            <a href="{{ route('employee.attendance.index') }}" 
                class="px-4 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-camera"></i>
                <span>{{ $todayAttendance && !$todayAttendance->time_out ? 'Absen Pulang' : 'Absen Masuk' }}</span>
            </a>
        </div>

        <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-500">
            <span>Shift: <strong>{{ Auth::user()->shift->name ?? 'Regular Office' }}</strong></span>
            <span>Jam: <strong>{{ substr(Auth::user()->shift->start_time ?? '08:00', 0, 5) }} - {{ substr(Auth::user()->shift->end_time ?? '17:00', 0, 5) }}</strong></span>
        </div>
    </div>

    <!-- 2. QUICK STATS WALLET (2 COLS) -->
    <div class="grid grid-cols-2 gap-3">
        <!-- Cuti Tahunan -->
        <a href="{{ route('employee.leave.index') }}" class="saas-card p-4 hover:border-blue-400 transition block">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sisa Kuota Cuti</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
            </div>
            <h4 class="text-xl font-black text-slate-900 font-mono">{{ Auth::user()->leave_quota }} <span class="text-xs font-normal text-slate-500">Hari</span></h4>
            <span class="text-[10px] text-blue-600 font-semibold mt-1 inline-flex items-center gap-1">Ajukan Cuti <i class="fa-solid fa-chevron-right text-[8px]"></i></span>
        </a>

        <!-- Slip Gaji Terakhir -->
        <a href="{{ route('employee.payroll.index') }}" class="saas-card p-4 hover:border-blue-400 transition block">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Gaji Terakhir</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
            <h4 class="text-base font-black text-emerald-700 font-mono">
                Rp {{ number_format($latestPayroll->net_salary ?? Auth::user()->salary, 0, ',', '.') }}
            </h4>
            <span class="text-[10px] text-blue-600 font-semibold mt-1 inline-flex items-center gap-1">Lihat Slip <i class="fa-solid fa-chevron-right text-[8px]"></i></span>
        </a>
    </div>

    <!-- 3. TOP 8 QUICK ACTION CIRCULAR ICONS GRID (SUPERAPP STYLE) -->
    <div class="saas-card p-5">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Layanan Mandiri Cepat</h4>
            <button onclick="openMenuSheet()" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                Lihat Semua <i class="fa-solid fa-chevron-right text-[9px] ml-0.5"></i>
            </button>
        </div>

        <div class="grid grid-cols-4 gap-y-4 gap-x-2 text-center">
            <!-- 1. Absen GPS -->
            <a href="{{ route('employee.attendance.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm border border-blue-100 p-3">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Absen</span>
            </a>

            <!-- 2. Cuti & SKD -->
            <a href="{{ route('employee.leave.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm border border-emerald-100 p-3">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Cuti</span>
            </a>

            <!-- 3. Slip Gaji -->
            <a href="{{ route('employee.payroll.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shadow-sm border border-teal-100 p-3">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Slip Gaji</span>
            </a>

            <!-- 4. SPPD Dinas -->
            <a href="{{ route('employee.business-trips.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm border border-indigo-100 p-3">
                    <i class="fa-solid fa-plane-departure"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">SPPD</span>
            </a>

            <!-- 5. Tukar Shift -->
            <a href="{{ route('employee.shift-swaps.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm border border-amber-100 p-3">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Tukar Shift</span>
            </a>

            <!-- 6. Kasbon 0% -->
            <a href="{{ route('employee.loans.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shadow-sm border border-cyan-100 p-3">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Kasbon</span>
            </a>

            <!-- 7. Reimburse -->
            <a href="{{ route('employee.reimbursements.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shadow-sm border border-rose-100 p-3">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Reimburse</span>
            </a>

            <!-- 8. Wall of Fame -->
            <a href="{{ route('kudos.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                <div class="w-13 h-13 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl shadow-sm border border-amber-300 p-3">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-700">Kudos</span>
            </a>
        </div>
    </div>

    <!-- 4. PENGUMUMAN PERUSAHAAN (PINNED ANNOUNCEMENT) -->
    @if(isset($latestAnnouncement) && $latestAnnouncement)
        <div class="saas-card p-4 bg-gradient-to-r from-blue-900 to-slate-900 text-white shadow-md">
            <div class="flex items-center justify-between mb-2">
                <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[9px] uppercase tracking-wider">
                    📌 Pengumuman Kantor
                </span>
                <span class="text-[10px] text-slate-300">{{ $latestAnnouncement->created_at->diffForHumans() }}</span>
            </div>
            <h5 class="text-xs font-bold text-white mb-1">{{ $latestAnnouncement->title }}</h5>
            <p class="text-[11px] text-slate-300 line-clamp-2">{{ $latestAnnouncement->content }}</p>
            <a href="{{ route('announcements.index') }}" class="text-[10px] font-bold text-amber-300 hover:text-amber-200 mt-2 inline-flex items-center gap-1">
                Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[8px]"></i>
            </a>
        </div>
    @endif

    <!-- 5. RIWAYAT PRESENSI MINGGU INI (RECENT LOGS) -->
    <div class="saas-card p-5">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Riwayat Absensi Terakhir</h4>
            <a href="{{ route('employee.attendance.index') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">
                Log Lengkap
            </a>
        </div>

        <div class="space-y-2.5">
            @forelse($recentAttendances as $att)
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M') }}</p>
                        <p class="text-[10px] text-slate-500 font-mono">
                            Masuk: {{ substr($att->time_in, 0, 5) }} • Pulang: {{ $att->time_out ? substr($att->time_out, 0, 5) : '-' }}
                        </p>
                    </div>

                    <div>
                        @if($att->status === 'present')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Tepat Waktu
                            </span>
                        @elseif($att->status === 'late')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                Terlambat
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                {{ ucfirst($att->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center py-4 text-xs text-slate-400">Belum ada riwayat absensi.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
