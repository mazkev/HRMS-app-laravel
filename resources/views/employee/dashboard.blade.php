@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Karyawan')
@section('page-subtitle', 'Pantau status absensi harian dan sisa hak cuti Anda.')

@section('content')
<div class="space-y-6">

    <!-- Welcome & Attendance Status Banner -->
    <div class="saas-card p-6 border-slate-200/90 bg-white relative overflow-hidden shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 text-xl font-bold shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-0.5">Halo, {{ $user->name }}! 👋</h3>
                    <p class="text-xs text-slate-500 font-medium">{{ $user->position ?? 'Staff' }} • {{ $user->department->name ?? 'PT Maju' }} (NIK: <span class="font-mono font-bold text-slate-700">{{ $user->nik }}</span>)</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-slate-500 font-medium">
                            <i class="fa-regular fa-calendar-days text-blue-600 mr-1"></i> Bergabung: {{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance Action -->
            <div class="flex flex-col sm:flex-row items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="text-center sm:text-left">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Status Kehadiran Hari Ini</span>
                    <div class="flex items-center gap-2 mt-1">
                        @if(!$todayAttendance)
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-amber-700">Belum Clock-In</span>
                        @elseif($todayAttendance->time_in && !$todayAttendance->time_out)
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs font-bold text-emerald-700">Masuk ({{ $todayAttendance->time_in }})</span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <span class="text-xs font-bold text-blue-700">Selesai (In: {{ $todayAttendance->time_in }} • Out: {{ $todayAttendance->time_out }})</span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('employee.attendance.index') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                    <i class="fa-solid fa-camera"></i>
                    <span>{{ !$todayAttendance ? 'Buka Kamera & Clock-In' : ($todayAttendance->time_out ? 'Lihat Riwayat Absen' : 'Ambil Foto & Clock-Out') }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Monthly Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Hadir Bulan Ini -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Hadir Tepat Waktu (Bulan Ini)</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-emerald-600">{{ $presentCount }} <span class="text-xs text-slate-400 font-normal">Hari</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Kehadiran on-time bulan {{ now()->translatedFormat('F') }}</p>
        </div>

        <!-- Terlambat Bulan Ini -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Keterlambatan (Bulan Ini)</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-amber-600">{{ $lateCount }} <span class="text-xs text-slate-400 font-normal">Hari</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Masuk lewat pukul 08:30 WIB</p>
        </div>

        <!-- Sisa Kuota Cuti -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Sisa Kuota Cuti Tahunan</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-umbrella-beach"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-blue-600">{{ $user->leave_quota }} <span class="text-xs text-slate-400 font-normal">Hari Tersedia</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Dari total plafon 12 hari/tahun</p>
        </div>
    </div>

    <!-- Recent Leave Requests History -->
    <div class="saas-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Status Permohonan Cuti Terbaru</h4>
                <p class="text-xs text-slate-500">Daftar pengajuan izin cuti dan keputusan HRD</p>
            </div>
            <a href="{{ route('employee.leave.index') }}" class="px-3.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 flex items-center gap-1.5 transition">
                <i class="fa-solid fa-plus"></i>
                <span>Ajukan Cuti Baru</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Rentang Tanggal</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Alasan Cuti</th>
                        <th class="py-3 px-4">Status Pengajuan</th>
                        <th class="py-3 px-4">Catatan HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentLeaves as $leave)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">{{ $leave->total_days }} Hari</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate">
                                {{ $leave->reason }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($leave->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Disetujui</span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu Review</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $leave->admin_notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">
                                Belum ada pengajuan cuti yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
