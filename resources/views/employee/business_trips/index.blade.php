@extends('layouts.employee_app')

@section('title', 'Perjalanan Dinas (SPPD)')
@section('page-title', 'Tugas Dinas (SPPD) ✈️')
@section('page-subtitle', 'Ajukan tugas luar kota, peroleh uang saku (per diem), dan cetak lembar SPPD')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE SPPD FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-plane-departure text-blue-600"></i>
            <span>Formulir Pengajuan SPPD</span>
        </h4>

        <form action="{{ route('employee.business-trips.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="destination_city" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Kota / Negara Tujuan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="destination_city" id="destination_city" required placeholder="Contoh: Surabaya / Singapura / Bali"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="start_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Tgl Berangkat <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>

                <div>
                    <label for="end_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Tgl Kembali <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                </div>
            </div>

            <div class="p-3 rounded-2xl bg-blue-50/70 border border-blue-200 text-xs text-blue-900 flex items-center justify-between">
                <div>
                    <span class="text-[10px] text-slate-500 font-bold block uppercase">Uang Saku Harian (Per Diem):</span>
                    <h5 class="text-xs font-black text-blue-700 font-mono mt-0.5">Rp 350.000 / Hari</h5>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Standar GA</span>
            </div>

            <div>
                <label for="purpose" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Agenda & Maksud Dinas <span class="text-rose-500">*</span>
                </label>
                <textarea name="purpose" id="purpose" rows="2.5" required placeholder="Jelaskan agenda kerja dan target perjalanan dinas..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Kirim Permohonan SPPD</span>
            </button>
        </form>
    </div>

    <!-- 2. MOBILE SPPD HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Perjalanan Dinas Saya</h4>

        <div class="space-y-2.5">
            @forelse($trips as $trip)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-extrabold text-xs text-blue-700">
                            {{ $trip->sppd_number }}
                        </span>

                        @if($trip->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($trip->status === 'rejected')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <p class="font-bold text-slate-900"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i> {{ $trip->destination_city }}</p>
                            <p class="text-[10px] text-slate-500">
                                {{ \Carbon\Carbon::parse($trip->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($trip->end_date)->translatedFormat('d M Y') }} ({{ $trip->total_days }} Hari)
                            </p>
                        </div>
                        <h4 class="text-xs font-bold font-mono text-emerald-700">
                            Rp {{ number_format($trip->total_allowance, 0, ',', '.') }}
                        </h4>
                    </div>

                    <p class="text-[11px] text-slate-600 italic">"{{ $trip->purpose }}"</p>

                    @if($trip->status === 'approved')
                        <div class="pt-2 border-t border-slate-200/60">
                            <a href="{{ route('business-trips.print', $trip->id) }}" target="_blank"
                                class="w-full py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm flex items-center justify-center gap-1.5 transition active:scale-95">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak / Unduh Lembar SPPD</span>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-plane-departure text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat permohonan SPPD.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
