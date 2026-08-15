@extends('layouts.app')

@section('title', 'Pengajuan Cuti')
@section('page-title', 'Pengajuan Cuti Karyawan')
@section('page-subtitle', 'Ajukan permohonan cuti dan pantau status persetujuan dari tim HRD.')

@section('content')
<div class="space-y-6">

    <!-- Quota & Submission Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Quota Summary Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-umbrella-beach"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Hak Cuti Tahunan</h4>
                        <p class="text-[11px] text-slate-500">Periode Tahun {{ date('Y') }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center mb-4">
                    <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Sisa Kuota Tersedia</span>
                    <h3 class="text-4xl font-extrabold text-blue-600 my-1">{{ $user->leave_quota }}</h3>
                    <p class="text-[11px] text-slate-500">Hari Kerja</p>
                </div>

                <div class="space-y-2 text-xs text-slate-600">
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Plafon Awal:</span>
                        <span class="font-bold text-slate-800">12 Hari / Tahun</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                        <span class="text-slate-500">Nama Pegawai:</span>
                        <span class="font-bold text-slate-800">{{ $user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-slate-500">NIK:</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $user->nik }}</span>
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 space-y-2">
                <div class="flex items-center gap-2 text-slate-800 font-bold">
                    <i class="fa-solid fa-circle-info text-blue-600"></i>
                    <span>Kebijakan Pengajuan Cuti</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-[11px] text-slate-500">
                    <li>Pengajuan dianjurkan minimal 3 hari sebelum tanggal mulai.</li>
                    <li>Sistem otomatis memeriksa kecukupan sisa kuota cuti.</li>
                    <li>Kuota cuti hanya akan terpotong setelah disetujui HRD.</li>
                </ul>
            </div>
        </div>

        <!-- Submission Form (8 Cols) -->
        <div class="lg:col-span-8 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Permohonan Cuti Baru</h4>
            <p class="text-xs text-slate-500 mb-6">Lengkapi tanggal dan alasan cuti secara jelas</p>

            <form action="{{ route('employee.leave.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Mulai Cuti <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                            value="{{ old('start_date') }}" onchange="calculateDays()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Selesai Cuti <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                            value="{{ old('end_date') }}" onchange="calculateDays()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <!-- Estimated Days Counter -->
                <div id="daysCounterBox" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <span class="text-xs text-slate-700 font-semibold">Estimasi Durasi Pengajuan:</span>
                    <span id="daysCountLabel" class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                        0 Hari
                    </span>
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan / Keperluan Cuti <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan alasan pengajuan cuti Anda secara singkat dan jelas..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">{{ old('reason') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Cuti ke HRD</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Seluruh Pengajuan Cuti</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar lengkap permohonan cuti yang telah diajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                        <th class="py-3 px-4">Rentang Cuti</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaves as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 font-medium">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">{{ $item->total_days }} Hari</span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate">
                                {{ $item->reason }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-regular fa-clock mr-1"></i> Menunggu Review
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $item->admin_notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">
                                Belum ada data permohonan cuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $leaves->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    function calculateDays() {
        const startVal = document.getElementById('start_date').value;
        const endVal = document.getElementById('end_date').value;
        const label = document.getElementById('daysCountLabel');

        if (startVal && endVal) {
            const start = new Date(startVal);
            const end = new Date(endVal);

            if (end < start) {
                label.innerText = 'Tanggal tidak valid';
                label.className = 'text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-200';
                return;
            }

            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            label.innerText = `${diffDays} Hari`;
            label.className = 'text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200';
        } else {
            label.innerText = '0 Hari';
        }
    }
</script>
@endpush
@endsection
