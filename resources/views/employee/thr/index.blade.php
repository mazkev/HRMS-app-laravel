@extends('layouts.employee_app')

@section('title', 'Tunjangan Hari Raya (THR) Saya')
@section('page-title', 'Tunjangan Hari Raya Keagamaan (THR)')
@section('page-subtitle', 'Riwayat pembayaran dan cetak slip Tunjangan Hari Raya (THR) resmi.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($thrPayments as $item)
            <div class="saas-card p-6 flex flex-col justify-between border-t-4 border-t-emerald-600">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                            {{ $item->holiday_name }}
                        </span>
                        <span class="text-xs font-bold font-mono text-slate-700">{{ $item->year }}</span>
                    </div>

                    <h4 class="text-base font-bold text-slate-900 mb-1">Tunjangan Hari Raya</h4>
                    <p class="text-xs text-slate-500 mb-4">{{ $item->notes }}</p>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 mb-4 text-center">
                        <span class="text-[11px] text-slate-500 block uppercase font-bold">Nominal THR Diterima:</span>
                        <h3 class="text-2xl font-black text-emerald-700 font-mono mt-0.5">
                            Rp {{ number_format($item->thr_amount, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="space-y-1.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Masa Kerja:</span>
                            <span class="font-bold text-slate-800">{{ $item->tenure_months }} Bulan</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal Bayar:</span>
                            <span class="font-semibold text-slate-700">{{ $item->payment_date ? \Carbon\Carbon::parse($item->payment_date)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="{{ route('thr.slip', $item->id) }}" target="_blank"
                        class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / Unduh Slip THR PDF</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 saas-card p-12 text-center text-slate-400">
                <i class="fa-solid fa-hand-holding-dollar text-4xl mb-3 text-slate-300"></i>
                <h4 class="text-sm font-bold text-slate-700">Belum Ada Riwayat THR</h4>
                <p class="text-xs mt-1">Pembayaran THR keagamaan akan otomatis tertera di sini saat masa hari raya.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
