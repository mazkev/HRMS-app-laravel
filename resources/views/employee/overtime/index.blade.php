@extends('layouts.employee_app')

@section('title', 'Pengajuan Lembur')
@section('page-title', 'Pengajuan Jam Kerja Lembur')
@section('page-subtitle', 'Ajukan permohonan lembur dan pantau status persetujuan dari tim HRD.')

@section('content')
<div class="space-y-6">

    <!-- Form & Info Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Info Card (4 Cols) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-business-time"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Ketentuan Lembur</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Lembur dihitung setelah jam operasional normal (pukul 17:00 WIB).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Pastikan telah melakukan clock-out absensi aktual setelah lembur.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Kompensasi lembur akan dihitung ke dalam periode penggajian.</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Submission Form (8 Cols) -->
        <div class="lg:col-span-8 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pengajuan Lembur Baru</h4>
            <p class="text-xs text-slate-500 mb-6">Lengkapi tanggal, jam pelaksanaan, dan alasan lembur</p>

            <form action="{{ route('employee.overtime.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Lembur <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" required max="{{ date('Y-m-d') }}"
                            value="{{ old('date', date('Y-m-d')) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="start_time" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jam Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" required value="{{ old('start_time', '17:30') }}" onchange="calcOvertimeHours()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="end_time" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jam Selesai <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" required value="{{ old('end_time', '20:30') }}" onchange="calcOvertimeHours()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <!-- Estimated Duration Box -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <span class="text-xs text-slate-700 font-semibold">Total Durasi Lembur:</span>
                    <span id="overtimeDurationLabel" class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
                        3.0 Jam
                    </span>
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Uraian Tugas / Alasan Lembur <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan pekerjaan atau target yang diselesaikan selama lembur..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">{{ old('reason') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Lembur ke HRD</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pengajuan Lembur</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar permohonan lembur yang telah Anda ajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tanggal Lembur</th>
                        <th class="py-3 px-4">Rentang Waktu</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Uraian Tugas</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($overtimes as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">{{ $item->duration_hours }} Jam</span>
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
                                Belum ada pengajuan lembur yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $overtimes->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    function calcOvertimeHours() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        const label = document.getElementById('overtimeDurationLabel');

        if (start && end) {
            const sParts = start.split(':');
            const eParts = end.split(':');

            const sMinutes = parseInt(sParts[0]) * 60 + parseInt(sParts[1]);
            const eMinutes = parseInt(eParts[0]) * 60 + parseInt(eParts[1]);

            if (eMinutes <= sMinutes) {
                label.innerText = 'Jam selesai tidak valid';
                label.className = 'text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1 rounded-full border border-rose-200';
                return;
            }

            const diffHours = ((eMinutes - sMinutes) / 60).toFixed(1);
            label.innerText = `${diffHours} Jam`;
            label.className = 'text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200';
        }
    }
</script>
@endpush
@endsection
