@extends('layouts.app')

@section('title', 'Log Absensi & Verifikasi')
@section('page-title', 'Log Absensi & Verifikasi GPS')
@section('page-subtitle', 'Monitoring rekap absensi harian, verifikasi foto selfie, dan validasi radius geolokasi kantor.')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="saas-card p-5">
        <form method="GET" action="{{ route('admin.attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <!-- Date Filter -->
            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Absensi</label>
                <input type="date" name="date" id="date" value="{{ $date }}"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <!-- Department Filter -->
            <div>
                <label for="department_id" class="block text-xs font-bold text-slate-700 mb-1">Departemen</label>
                <select name="department_id" id="department_id"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Status Kehadiran</label>
                <select name="status" id="status"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Status</option>
                    <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="late" {{ $status === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                </select>
            </div>

            <!-- Search Employee Name/NIK -->
            <div>
                <label for="search" class="block text-xs font-bold text-slate-700 mb-1">Cari Karyawan</label>
                <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama / NIK..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filter Data</span>
                </button>
                <a href="{{ route('admin.attendance.index') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Logs Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Rekap Kehadiran ({{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }})</h4>
                <p class="text-xs text-slate-500">Ditemukan {{ $attendances->total() }} catatan kehadiran</p>
            </div>

            <!-- Export CSV Button -->
            <a href="{{ route('admin.export.attendance', ['date' => $date]) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-300 flex items-center gap-2 transition shadow-sm self-start sm:self-auto">
                <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i>
                <span>Export Log CSV</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Departemen & Jabatan</th>
                        <th class="py-3 px-4 text-center">Foto Masuk</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-4 text-center">Foto Pulang</th>
                        <th class="py-3 px-4">Jam Pulang</th>
                        <th class="py-3 px-4">Status & Geolocation</th>
                        <th class="py-3 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Karyawan -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 font-bold flex items-center justify-center text-xs border border-blue-100">
                                        {{ strtoupper(substr($att->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $att->user->name }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono">{{ $att->user->nik }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Dept & Position -->
                            <td class="py-3.5 px-4">
                                <p class="text-slate-800 font-semibold">{{ $att->user->department->name ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $att->user->position ?? '-' }}</p>
                            </td>

                            <!-- Foto In -->
                            <td class="py-3.5 px-4 text-center">
                                @if($att->image_in)
                                    <img src="{{ asset('storage/' . $att->image_in) }}" alt="Selfie Masuk"
                                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 mx-auto cursor-pointer hover:opacity-80 shadow-sm transition"
                                        onclick="showPhotoModal('{{ asset('storage/' . $att->image_in) }}', '{{ $att->user->name }} - Clock In ({{ $att->time_in }})')">
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Jam Masuk -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $att->time_in ?? '-' }}
                            </td>

                            <!-- Foto Out -->
                            <td class="py-3.5 px-4 text-center">
                                @if($att->image_out)
                                    <img src="{{ asset('storage/' . $att->image_out) }}" alt="Selfie Pulang"
                                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 mx-auto cursor-pointer hover:opacity-80 shadow-sm transition"
                                        onclick="showPhotoModal('{{ asset('storage/' . $att->image_out) }}', '{{ $att->user->name }} - Clock Out ({{ $att->time_out }})')">
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Jam Pulang -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $att->time_out ?? '-' }}
                            </td>

                            <!-- Status & Geolocation -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1">
                                    <div>
                                        @if($att->status === 'present')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <i class="fa-solid fa-check mr-1"></i> Tepat Waktu
                                            </span>
                                        @elseif($att->status === 'late')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <i class="fa-solid fa-clock mr-1"></i> Terlambat
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                                {{ ucfirst($att->status) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($att->distance_meters !== null)
                                        <span class="text-[10px] text-slate-500 font-medium">
                                            <i class="fa-solid fa-location-dot text-blue-600 text-[9px]"></i> {{ $att->distance_meters }}m dari kantor
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Catatan -->
                            <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">
                                {{ $att->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-clipboard-question text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada log absensi yang cocok dengan filter tanggal/kriteria yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<!-- Modal Foto Preview -->
<div id="photoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 id="photoModalTitle" class="text-sm font-bold text-slate-900">Bukti Foto Absensi</h4>
            <button onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Bukti Foto Absensi" class="w-full max-h-[460px] object-contain">
        </div>
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
</script>
@endpush
@endsection
