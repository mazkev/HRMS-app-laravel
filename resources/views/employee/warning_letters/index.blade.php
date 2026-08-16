@extends('layouts.employee_app')

@section('title', 'Surat Peringatan Saya')
@section('page-title', 'Catatan Kedisiplinan & Surat Peringatan')
@section('page-subtitle', 'Riwayat surat peringatan yang diterbitkan oleh departemen HRD.')

@section('content')
<div class="space-y-6">

    <div class="space-y-4">
        @forelse($warningLetters as $item)
            <div class="saas-card p-6 border-l-4 {{ $item->status === 'active' ? 'border-l-rose-600' : 'border-l-slate-300' }}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300 font-mono">
                                {{ $item->level }}
                            </span>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ $item->letter_number }}</span>
                        </div>
                        <h4 class="text-base font-bold text-slate-900">{{ $item->violation_type }}</h4>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $item->status === 'active' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ $item->status === 'active' ? 'Aktif Berlaku (6 Bulan)' : 'Kedaluwarsa' }}
                        </span>
                        <a href="{{ route('warning-letters.print', $item->id) }}" target="_blank"
                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-print text-slate-500"></i>
                            <span>Cetak Dokumen</span>
                        </a>
                    </div>
                </div>

                <p class="text-xs text-slate-700 leading-relaxed mb-4 whitespace-pre-line">{{ $item->description }}</p>

                <div class="flex flex-wrap items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100">
                    <div>
                        <span>Diterbitkan Tanggal: <strong>{{ \Carbon\Carbon::parse($item->issued_date)->translatedFormat('d F Y') }}</strong></span>
                        <span class="mx-2">•</span>
                        <span>Masa Berlaku Hingga: <strong>{{ \Carbon\Carbon::parse($item->valid_until)->translatedFormat('d F Y') }}</strong></span>
                    </div>
                    <span class="text-[11px] text-slate-400">Diterbitkan oleh: HR Department</span>
                </div>
            </div>
        @empty
            <div class="saas-card p-12 text-center text-slate-400">
                <i class="fa-solid fa-shield-check text-5xl mb-3 text-emerald-500"></i>
                <h4 class="text-base font-bold text-slate-800">Catatan Kedisiplinan Bersih</h4>
                <p class="text-xs text-slate-500 mt-1">Anda tidak memiliki surat peringatan aktif. Pertahankan kinerja dan kedisiplinan yang luar biasa!</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
