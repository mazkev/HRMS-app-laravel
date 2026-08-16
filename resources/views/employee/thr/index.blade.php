@extends('layouts.employee_app')

@section('title', 'Tunjangan Hari Raya (THR)')
@section('page-title', 'Tunjangan Hari Raya (THR) 🕌')
@section('page-subtitle', 'Kalkulasi dan slip tanda terima pembayaran THR resmi')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE THR CARDS -->
    <div class="space-y-3">
        @forelse($thrPayments as $item)
            <div class="saas-card p-4 space-y-3 border-t-4 border-t-emerald-600">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300 uppercase">
                        {{ $item->holiday_name }}
                    </span>
                    <span class="text-xs font-bold font-mono text-slate-700">{{ $item->year }}</span>
                </div>

                <div class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-center">
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Nominal THR Diterima:</span>
                    <h3 class="text-2xl font-black text-emerald-700 font-mono mt-0.5">
                        Rp {{ number_format($item->thr_amount, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="space-y-1 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span>Masa Kerja:</span>
                        <span class="font-bold text-slate-800">{{ $item->tenure_months }} Bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Pencairan:</span>
                        <span class="font-semibold text-slate-700">{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-700 font-semibold">
                        <span>Keterangan:</span>
                        <span>{{ $item->notes }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <a href="{{ route('thr.slip', $item->id) }}" target="_blank"
                        class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md shadow-emerald-500/25 flex items-center justify-center gap-1.5 transition active:scale-95">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / Unduh Slip THR PDF</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="saas-card p-6 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-gift text-3xl mb-1 text-slate-300"></i>
                <p>Belum ada riwayat pembayaran THR keagamaan.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
