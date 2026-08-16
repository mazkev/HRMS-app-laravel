@extends('layouts.employee_app')

@section('title', 'Rapor Kinerja KPI')
@section('page-title', 'Rapor Kinerja (KPI) 🏆')
@section('page-subtitle', 'Evaluasi performa berkala, pencapaian target, dan penilaian atasan')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE PERFORMANCE CARDS -->
    <div class="space-y-3">
        @forelse($reviews as $item)
            <div class="saas-card p-4 space-y-3 border-t-4 border-t-blue-600">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase block">Periode Evaluasi</span>
                        <h4 class="text-xs font-extrabold text-slate-900">{{ $item->period_quarter }} - {{ $item->period_year }}</h4>
                    </div>

                    <!-- Grade Badge -->
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-700 to-indigo-600 text-white flex items-center justify-center font-black text-lg shadow-md">
                        {{ $item->final_grade ?? 'A' }}
                    </div>
                </div>

                <!-- Scores Breakdown Grid -->
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-100">
                        <span class="text-[10px] text-slate-500 font-bold block uppercase">Skor KPI</span>
                        <h5 class="text-sm font-black text-blue-700 font-mono mt-0.5">{{ $item->kpi_score }}/100</h5>
                    </div>

                    <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100">
                        <span class="text-[10px] text-slate-500 font-bold block uppercase">Kehadiran</span>
                        <h5 class="text-sm font-black text-emerald-700 font-mono mt-0.5">{{ $item->attendance_score }}/100</h5>
                    </div>

                    <div class="p-2.5 rounded-xl bg-purple-50/70 border border-purple-100">
                        <span class="text-[10px] text-slate-500 font-bold block uppercase">Kerja Tim</span>
                        <h5 class="text-sm font-black text-purple-700 font-mono mt-0.5">{{ $item->teamwork_score }}/100</h5>
                    </div>
                </div>

                @if($item->feedback)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Catatan & Masukan Evaluator:</span>
                        <p class="text-[11px] text-slate-700 italic">"{{ $item->feedback }}"</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="saas-card p-6 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-award text-3xl mb-1 text-slate-300"></i>
                <p>Belum ada evaluasi kinerja untuk periode berjalan.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
