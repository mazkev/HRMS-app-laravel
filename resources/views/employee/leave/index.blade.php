@extends('layouts.app')

@section('title', 'Pengajuan Cuti & Izin')
@section('page-title', 'Pengajuan Cuti & Izin Khusus')
@section('page-subtitle', 'Kelola permohonan cuti tahunan, cuti sakit, melahirkan, pernikahan, dan izin khusus lainnya.')

@section('content')
<div class="space-y-6">

    <!-- KPI / Leave Quota Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="saas-card p-5">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Sisa Kuota Cuti Tahunan</span>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-extrabold text-blue-600">{{ $user->leave_quota }}</h3>
                <span class="text-xs font-semibold text-slate-500">Hari Kerja Tersisa</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Kuota ter-reset setiap 1 Januari</p>
        </div>

        <div class="saas-card p-5">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider block mb-1">Cuti Melahirkan & Khusus</span>
            <div class="flex items-baseline gap-2">
                <h3 class="text-lg font-bold text-slate-900">Hak Cuti Terlindungi</h3>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Cuti melahirkan, menikah, dan duka tidak memotong kuota tahunan.</p>
        </div>

        <div class="saas-card p-5">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider block mb-1">Cuti Sakit & Surat Dokter</span>
            <div class="flex items-baseline gap-2">
                <h3 class="text-lg font-bold text-slate-900">Lampiran SKD Digital</h3>
            </div>
            <p class="text-[11px] text-slate-500 mt-1">Wajib melampirkan foto Surat Keterangan Dokter saat mengajukan cuti sakit.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Pengajuan Cuti (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pengajuan Cuti / Izin</h4>
            <p class="text-xs text-slate-500 mb-6">Pilih jenis cuti dan tentukan rentang tanggal</p>

            <form action="{{ route('employee.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="leave_type" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Jenis Cuti / Izin <span class="text-rose-500">*</span>
                    </label>
                    <select name="leave_type" id="leave_type" required onchange="toggleSickNoteInput()"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                        <option value="annual">Cuti Tahunan (Memotong Kuota Cuti)</option>
                        <option value="sick">Cuti Sakit (Wajib Lampirkan Surat Dokter / SKD)</option>
                        <option value="maternity">Cuti Melahirkan (90 Hari - Tanpa Potong Kuota)</option>
                        <option value="marriage">Cuti Menikah (3 Hari - Tanpa Potong Kuota)</option>
                        <option value="bereavement">Cuti Duka Cita (2 Hari - Tanpa Potong Kuota)</option>
                        <option value="unpaid">Izin Tidak Berbayar (Unpaid Leave)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <!-- Medical Certificate Upload (Hidden by default, shown when sick is chosen) -->
                <div id="sickNoteContainer" class="hidden p-4 rounded-xl bg-amber-50 border border-amber-200">
                    <label for="medical_certificate" class="block text-xs font-bold text-amber-900 mb-1">
                        Unggah Surat Keterangan Dokter (SKD) <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-[11px] text-amber-700 mb-2">Format: JPG, PNG, PDF (Maksimal 3MB)</p>
                    <input type="file" name="medical_certificate" id="medical_certificate" accept="image/*,application/pdf"
                        class="w-full text-xs text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700">
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan / Keperluan Cuti <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="3" required placeholder="Tuliskan keterangan detail keperluan izin..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Cuti</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan Cuti (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Ketentuan Cuti PT Maju</h4>
                        <p class="text-[11px] text-slate-500">Peraturan Perusahaan & UU Ketenagakerjaan</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Cuti tahunan diajukan minimal 3 hari sebelum tanggal mulai.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Cuti melahirkan 3 bulan (1.5 bulan sebelum & 1.5 bulan sesudah).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Cuti sakit > 1 hari wajib menyertakan resep atau surat dokter (SKD).</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat Pengajuan Cuti Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pengajuan Cuti & Izin</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar permohonan cuti yang telah Anda ajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Jenis Cuti</th>
                        <th class="py-3 px-4">Rentang Tanggal</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Dokumen SKD</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaveRequests as $leave)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold
                                    @if($leave->leave_type === 'sick') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($leave->leave_type === 'maternity') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($leave->leave_type === 'marriage') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ $leave->total_days }} Hari
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($leave->medical_certificate)
                                    <a href="{{ asset('storage/' . $leave->medical_certificate) }}" target="_blank" class="px-2 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold inline-flex items-center gap-1 hover:bg-blue-100">
                                        <i class="fa-solid fa-file-medical"></i>
                                        <span>Lihat SKD</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($leave->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $leave->admin_notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                Belum ada riwayat permohonan cuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $leaveRequests->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    function toggleSickNoteInput() {
        const type = document.getElementById('leave_type').value;
        const container = document.getElementById('sickNoteContainer');
        const fileInput = document.getElementById('medical_certificate');

        if (type === 'sick') {
            container.classList.remove('hidden');
            fileInput.required = true;
        } else {
            container.classList.add('hidden');
            fileInput.required = false;
        }
    }
</script>
@endpush
@endsection
