@extends('layouts.employee_app')

@section('title', 'Pengajuan Lembur')
@section('page-title', 'Pengajuan Lembur ⏰')
@section('page-subtitle', 'Ajukan permohonan lembur kerja dan pantau status persetujuan')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE OVERTIME FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-business-time text-blue-600"></i>
            <span>Formulir Pengajuan Lembur</span>
        </h4>

        <form action="{{ route('employee.overtime.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="date" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Tanggal Pelaksanaan Lembur <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="date" id="date" required value="{{ date('Y-m-d') }}"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="start_time" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time" required value="17:30"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>

                <div>
                    <label for="end_time" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time" required value="20:30"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>
            </div>

            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Uraian Tugas Lembur <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="2.5" required placeholder="Contoh: Penyelesaian rilis sistem / audit laporan..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Kirim Permohonan Lembur</span>
            </button>
        </form>
    </div>

    <!-- 2. MOBILE OVERTIME HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Pengajuan Lembur</h4>

        <div class="space-y-2.5">
            @forelse($overtimes as $ot)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900">
                            {{ \Carbon\Carbon::parse($ot->date)->translatedFormat('l, d M Y') }}
                        </span>

                        @if($ot->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($ot->status === 'rejected')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Waktu: <strong>{{ substr($ot->start_time, 0, 5) }} - {{ substr($ot->end_time, 0, 5) }}</strong></span>
                        <span>Durasi: <strong class="text-blue-700">{{ $ot->duration_hours }} Jam</strong></span>
                    </div>

                    <p class="text-[11px] text-slate-600 italic">"{{ $ot->reason }}"</p>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-business-time text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat pengajuan lembur.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
