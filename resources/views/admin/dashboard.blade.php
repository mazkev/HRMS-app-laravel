@extends('layouts.app')

@section('title', 'Overview HRD')
@section('page-title', 'Overview Dashboard')
@section('page-subtitle', 'Monitoring kehadiran real-time, status karyawan, dan permohonan cuti.')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Karyawan -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Karyawan</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalEmployees }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Pegawai aktif terdaftar</p>
        </div>

        <!-- Total Departemen -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Departemen</span>
                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ $totalDepartments }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Divisi & unit kerja</p>
        </div>

        <!-- Hadir Hari Ini -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Hadir Hari Ini</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-emerald-600">{{ $presentToday }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Telah clock-in hari ini</p>
        </div>

        <!-- Terlambat Hari Ini -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Terlambat</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-amber-600">{{ $lateToday }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Masuk > 08:30 WIB</p>
        </div>

        <!-- Menunggu Cuti -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-rose-700 uppercase tracking-wider">Pending Cuti</span>
                <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-rose-600">{{ $pendingLeaves }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Menunggu approval</p>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div class="saas-card p-6 bg-gradient-to-r from-blue-900 to-slate-900 text-white flex flex-col md:flex-row items-center justify-between gap-4 border-none shadow-md">
        <div>
            <h4 class="text-base font-bold text-white mb-1">Aksi Cepat Admin HRD</h4>
            <p class="text-xs text-blue-200">Kelola operasional absensi kamera, verifikasi foto kehadiran, dan persetujuan hak cuti.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow transition flex items-center gap-2">
                <i class="fa-solid fa-camera"></i>
                <span>Verifikasi Foto Absensi</span>
            </a>
            <a href="{{ route('admin.leave.index') }}" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-900 text-xs font-bold shadow transition flex items-center gap-2">
                <i class="fa-solid fa-check-double text-blue-600"></i>
                <span>Review Cuti ({{ $pendingLeaves }})</span>
            </a>
            <a href="{{ route('admin.employees.create') }}" class="px-4 py-2 rounded-xl bg-blue-950/80 hover:bg-blue-950 text-white text-xs font-semibold border border-blue-700/60 transition flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i>
                <span>+ Karyawan Baru</span>
            </a>
        </div>
    </div>

    <!-- Two-column Section: Live Attendance & Pending Leave Requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Attendance Log Today -->
        <div class="saas-card rounded-2xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Log Kehadiran Hari Ini</h4>
                    <p class="text-xs text-slate-500">Aktivitas clock-in & clock-out terbaru</p>
                </div>
                <a href="{{ route('admin.attendance.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1">
                    Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-2.5 flex-1 overflow-y-auto max-h-[380px]">
                @forelse($recentAttendances as $att)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 hover:bg-white hover:shadow-sm transition">
                        <div class="flex items-center gap-3">
                            @if($att->image_in)
                                <img src="{{ asset('storage/' . $att->image_in) }}" alt="Selfie" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shadow-sm cursor-pointer hover:opacity-80 transition" onclick="showPhotoModal('{{ asset('storage/' . $att->image_in) }}', '{{ $att->user->name }} - Clock In ({{ $att->time_in }})')">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($att->user->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">{{ $att->user->name }}</h5>
                                <p class="text-[11px] text-slate-500">{{ $att->user->department->name ?? 'General' }} • {{ $att->user->nik }}</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="flex items-center justify-end gap-2 mb-0.5">
                                <span class="text-xs font-mono font-bold text-slate-800">{{ $att->time_in ?? '--:--' }}</span>
                                @if($att->status === 'present')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Tepat Waktu</span>
                                @elseif($att->status === 'late')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Terlambat</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">{{ ucfirst($att->status) }}</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400">Pulang: {{ $att->time_out ?? 'Belum Clock-out' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        <i class="fa-solid fa-fingerprint text-3xl mb-2 text-slate-300"></i>
                        <p class="text-xs font-medium">Belum ada aktivitas absensi tercatat hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Leave Requests -->
        <div class="saas-card rounded-2xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Antrean Pengajuan Cuti</h4>
                    <p class="text-xs text-slate-500">Permohonan cuti menunggu persetujuan HRD</p>
                </div>
                <a href="{{ route('admin.leave.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold flex items-center gap-1">
                    Kelola Cuti <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto max-h-[380px]">
                @forelse($recentLeaves as $leave)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-start justify-between">
                            <div>
                                <h5 class="text-xs font-bold text-slate-900">{{ $leave->user->name }}</h5>
                                <p class="text-[11px] text-slate-500">{{ $leave->user->department->name ?? 'General' }} • Kuota sisa: {{ $leave->user->leave_quota }} hari</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $leave->total_days }} Hari
                            </span>
                        </div>

                        <div class="text-xs text-slate-700 bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                            <p class="font-bold text-blue-700 text-[11px] mb-0.5">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                            </p>
                            <p class="text-slate-600 italic text-[11px]">"{{ $leave->reason }}"</p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-1">
                            <form action="{{ route('admin.leave.approve', $leave->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan cuti ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Setujui</span>
                                </button>
                            </form>
                            <button type="button" onclick="openRejectModal({{ $leave->id }}, '{{ $leave->user->name }}')" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Tolak</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300"></i>
                        <p class="text-xs font-medium">Tidak ada pengajuan cuti yang menunggu saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto Preview -->
<div id="photoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 id="photoModalTitle" class="text-sm font-bold text-slate-900">Bukti Foto Absensi</h4>
            <button onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Bukti Foto Absensi" class="w-full max-h-[420px] object-contain">
        </div>
    </div>
</div>

<!-- Modal Reject Leave -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Pengajuan Cuti</h4>
        <p class="text-xs text-slate-500 mb-4" id="rejectModalDesc">Berikan alasan penolakan permohonan cuti:</p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" required rows="3" placeholder="Contoh: Jadwal operasional sedang padat..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                    Tolak Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showPhotoModal(src, title) {
        document.getElementById('photoModalImg').src = src;
        document.getElementById('photoModalTitle').innerText = title;
        document.getElementById('photoModal').classList.remove('hidden');
        document.getElementById('photoModal').classList.add('flex');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.getElementById('photoModal').classList.remove('flex');
    }

    function openRejectModal(leaveId, employeeName) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/leave-requests/${leaveId}/reject`;
        document.getElementById('rejectModalDesc').innerText = `Penolakan cuti untuk ${employeeName}:`;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
