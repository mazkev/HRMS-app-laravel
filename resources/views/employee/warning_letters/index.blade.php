@extends('layouts.employee_app')

@section('title', 'Surat Peringatan Saya')
@section('page-title', 'Catatan Kedisiplinan ⚠️')
@section('page-subtitle', 'Daftar surat peringatan (SP) dan masa berlaku kedisiplinan')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE SP LIST CARDS -->
    <div class="space-y-3">
        @forelse($warningLetters as $sp)
            <div class="saas-card p-4 space-y-3 border-l-4 {{ $sp->level === 'SP 3' ? 'border-l-rose-600' : ($sp->level === 'SP 2' ? 'border-l-orange-500' : 'border-l-amber-500') }}">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase
                        {{ $sp->level === 'SP 3' ? 'bg-rose-100 text-rose-800' : ($sp->level === 'SP 2' ? 'bg-orange-100 text-orange-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ $sp->level }}
                    </span>
                    <span class="font-mono text-[11px] font-bold text-slate-600">{{ $sp->letter_number }}</span>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-slate-900">{{ $sp->violation_type }}</h5>
                    <p class="text-[11px] text-slate-600 italic mt-1">"{{ $sp->description }}"</p>
                </div>

                <div class="space-y-1 text-[11px] text-slate-500 pt-2 border-t border-slate-100">
                    <div class="flex justify-between">
                        <span>Tanggal Terbit:</span>
                        <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($sp->issued_date)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Masa Berlaku s/d:</span>
                        <span class="font-bold text-rose-600">{{ \Carbon\Carbon::parse($sp->valid_until)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <a href="{{ route('warning-letters.print', $sp->id) }}" target="_blank"
                    class="w-full py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs flex items-center justify-center gap-1.5 transition">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Dokumen Resmi SP</span>
                </a>
            </div>
        @empty
            <div class="saas-card p-6 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-shield-check text-3xl mb-1 text-emerald-400"></i>
                <h5 class="text-xs font-bold text-slate-800">Catatan Bersih!</h5>
                <p class="mt-0.5">Tidak ada riwayat surat peringatan. Pertahankan kedisiplinan kerja Anda.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
