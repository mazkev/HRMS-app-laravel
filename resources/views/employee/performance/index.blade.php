@extends('layouts.app')

@section('title', 'Rapor Kinerja Saya')
@section('page-title', 'Rapor Penilaian Kinerja (KPI Scorecard)')
@section('page-subtitle', 'Pantau evaluasi pencapaian target, skor kehadiran, dan feedback dari manajemen.')

@section('content')
<div class="space-y-6">

    @if($reviews->isNotEmpty())
        @php $latest = $reviews->first(); @endphp
        <!-- Latest Performance Banner -->
        <div class="saas-card p-6 bg-gradient-to-r from-blue-900 to-slate-900 text-white border-none shadow-md">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-blue-200 uppercase tracking-wider">Hasil Evaluasi Terbaru ({{ $latest->period_quarter }} {{ $latest->period_year }})</span>
                    <h3 class="text-2xl font-black text-white">Predikat Kinerja: Grade {{ $latest->final_grade }}</h3>
                    <p class="text-xs text-slate-300 italic">"{{ $latest->feedback ?? 'Pencapaian target kerja memuaskan.' }}"</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-center p-3 rounded-xl bg-white/10 border border-white/20">
                        <span class="text-[10px] text-blue-200 uppercase font-bold block">Skor KPI</span>
                        <span class="text-xl font-bold font-mono">{{ $latest->kpi_score }}/100</span>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white/10 border border-white/20">
                        <span class="text-[10px] text-emerald-200 uppercase font-bold block">Absensi</span>
                        <span class="text-xl font-bold font-mono">{{ $latest->attendance_score }}/100</span>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-white/10 border border-white/20">
                        <span class="text-[10px] text-amber-200 uppercase font-bold block">Teamwork</span>
                        <span class="text-xl font-bold font-mono">{{ $latest->teamwork_score }}/100</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Scorecard History Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Evaluasi Kinerja</h4>
        <p class="text-xs text-slate-500 mb-4">Catatan rapor evaluasi per kuartal dan tahunan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Periode</th>
                        <th class="py-3 px-4 text-center">Skor KPI</th>
                        <th class="py-3 px-4 text-center">Skor Kehadiran</th>
                        <th class="py-3 px-4 text-center">Teamwork</th>
                        <th class="py-3 px-4 text-center">Predikat Grade</th>
                        <th class="py-3 px-4">Umpan Balik & Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $r)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $r->period_quarter }} {{ $r->period_year }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-blue-700 font-mono">
                                {{ $r->kpi_score }}/100
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-700 font-mono">
                                {{ $r->attendance_score }}/100
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700 font-mono">
                                {{ $r->teamwork_score }}/100
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($r->final_grade === 'A')
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-300">
                                        GRADE A
                                    </span>
                                @elseif($r->final_grade === 'B')
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-blue-50 text-blue-700 border border-blue-300">
                                        GRADE B
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-amber-50 text-amber-700 border border-amber-300">
                                        GRADE {{ $r->final_grade }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate italic">
                                "{{ $r->feedback ?? '-' }}"
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-award text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada catatan evaluasi kinerja untuk akun Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $reviews->links() }}
        </div>
    </div>

</div>
@endsection
