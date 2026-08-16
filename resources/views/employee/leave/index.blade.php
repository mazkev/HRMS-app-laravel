@extends('layouts.employee_app')

@section('title', 'Pengajuan Cuti & Izin')
@section('page-title', 'Cuti & Izin Khusus 🏖️')
@section('page-subtitle', 'Ajukan cuti tahunan, cuti khusus, atau izin sakit dengan surat dokter')

@section('content')
<div class="space-y-4">

    <!-- 1. LEAVE QUOTA WALLET CARD -->
    <div class="saas-card p-4 bg-gradient-to-br from-blue-700 to-slate-900 text-white shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-extrabold text-blue-200 uppercase tracking-wider">Hak Cuti Tahunan</span>
            <span class="px-2 py-0.5 rounded-full bg-blue-500/30 border border-blue-400/40 text-[10px] font-bold text-white">
                Tahun {{ date('Y') }}
            </span>
        </div>

        <div class="flex items-center justify-between py-1">
            <div>
                <span class="text-[11px] text-blue-200 block">Sisa Kuota Tersedia:</span>
                <h3 class="text-3xl font-black text-white font-mono mt-0.5">
                    {{ $user->leave_quota }} <span class="text-sm font-normal text-blue-200">Hari</span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-xl text-white">
                <i class="fa-solid fa-umbrella-beach"></i>
            </div>
        </div>

        <div class="mt-3 pt-2.5 border-t border-white/15 flex items-center justify-between text-[10px] text-blue-200">
            <span>Masa Berlaku s/d <strong>31 Des {{ date('Y') }}</strong></span>
            <span>Cuti Khusus: <strong>Bebas Kuota</strong></span>
        </div>
    </div>

    <!-- 2. TOUCH-FRIENDLY MOBILE LEAVE APPLICATION FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-paper-plane text-blue-600"></i>
            <span>Formulir Permohonan Cuti</span>
        </h4>

        <form action="{{ route('employee.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <!-- Leave Type Radio Pills -->
            <div>
                <label class="block text-[11px] font-bold text-slate-700 mb-1.5">
                    Kategori Cuti / Izin <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="annual" checked onchange="toggleSkdUpload(false)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">🏖️ Cuti Tahunan</span>
                    </label>

                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="sick" onchange="toggleSkdUpload(true)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">🏥 Cuti Sakit (SKD)</span>
                    </label>

                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="maternity" onchange="toggleSkdUpload(false)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">👶 Melahirkan (90 hr)</span>
                    </label>

                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="marriage" onchange="toggleSkdUpload(false)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">💍 Menikah (3 hr)</span>
                    </label>

                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="bereavement" onchange="toggleSkdUpload(false)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">🕊️ Duka Cita (2 hr)</span>
                    </label>

                    <label class="p-2.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center gap-2 cursor-pointer hover:bg-blue-50/50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/70 has-[:checked]:text-blue-900 transition">
                        <input type="radio" name="leave_type" value="unpaid" onchange="toggleSkdUpload(false)" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-semibold text-[11px]">⏳ Cuti Tanpa Gaji</span>
                    </label>
                </div>
            </div>

            <!-- Date Range Pickers -->
            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="start_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Mulai Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Sampai Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>
            </div>

            <!-- SKD Medical Certificate Upload (Visible for sick leave) -->
            <div id="skdUploadContainer" class="hidden p-3 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-1.5">
                <label for="medical_certificate" class="block text-[11px] font-bold text-amber-900">
                    Unggah Surat Dokter (SKD) <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="medical_certificate" id="medical_certificate" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700">
                <p class="text-[10px] text-amber-700">Format: JPG, PNG, atau PDF (Maks 3MB).</p>
            </div>

            <!-- Reason Field -->
            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Alasan / Keperluan <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="2.5" required placeholder="Tuliskan keterangan keperluan cuti Anda..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Kirim Permohonan Cuti</span>
            </button>
        </form>
    </div>

    <!-- 3. MOBILE LEAVE REQUEST HISTORY (CARD-BASED NATIVE APP LIST) -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Pengajuan Cuti</h4>

        <div class="space-y-2.5">
            @forelse($leaveRequests as $item)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                            @if($item->leave_type === 'sick') bg-rose-100 text-rose-800 border border-rose-200
                            @elseif($item->leave_type === 'maternity') bg-purple-100 text-purple-800 border border-purple-200
                            @elseif($item->leave_type === 'marriage') bg-pink-100 text-pink-800 border border-pink-200
                            @else bg-blue-100 text-blue-800 border border-blue-200 @endif">
                            {{ ucfirst($item->leave_type) }} ({{ $item->total_days }} Hari)
                        </span>

                        @if($item->status === 'approved')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
                                <i class="fa-solid fa-check text-[9px]"></i> Disetujui
                            </span>
                        @elseif($item->status === 'rejected')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="text-xs">
                        <p class="font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($item->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($item->end_date)->translatedFormat('d M Y') }}
                        </p>
                        <p class="text-[11px] text-slate-600 italic mt-0.5">"{{ $item->reason }}"</p>
                    </div>

                    @if($item->medical_certificate)
                        <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                            <span class="text-slate-500 font-medium">Lampiran SKD:</span>
                            <a href="{{ asset('storage/' . $item->medical_certificate) }}" target="_blank" class="text-blue-600 font-bold hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-file-medical"></i> Lihat Berkas Dokter
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-umbrella-beach text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat permohonan cuti.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('scripts')
<script>
    function toggleSkdUpload(show) {
        const container = document.getElementById('skdUploadContainer');
        const fileInput = document.getElementById('medical_certificate');
        if (show) {
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
