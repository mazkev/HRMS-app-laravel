@extends('layouts.app')

@section('title', 'Penilaian Kinerja & KPI')
@section('page-title', 'Manajemen Penilaian Kinerja (KPI Appraisal)')
@section('page-subtitle', 'Evaluasi performa triwulanan/tahunan karyawan, perhitungan skor bobot, dan pemberian grade.')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Filter Toolbar -->
    <div class="saas-card p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.performance.index') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div>
                <label for="year" class="block text-[11px] font-bold text-slate-600 mb-1">Tahun Evaluasi</label>
                <select name="year" id="year" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    @for($y = date('Y'); $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="quarter" class="block text-[11px] font-bold text-slate-600 mb-1">Periode</label>
                <select name="quarter" id="quarter" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Periode</option>
                    <option value="Q1" {{ $quarter === 'Q1' ? 'selected' : '' }}>Kuartal 1 (Q1)</option>
                    <option value="Q2" {{ $quarter === 'Q2' ? 'selected' : '' }}>Kuartal 2 (Q2)</option>
                    <option value="Q3" {{ $quarter === 'Q3' ? 'selected' : '' }}>Kuartal 3 (Q3)</option>
                    <option value="Q4" {{ $quarter === 'Q4' ? 'selected' : '' }}>Kuartal 4 (Q4)</option>
                    <option value="Annual" {{ $quarter === 'Annual' ? 'selected' : '' }}>Tahunan (Annual)</option>
                </select>
            </div>
        </form>

        <!-- Button Input Penilaian Baru -->
        <button type="button" onclick="openAppraisalModal()"
            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Input Evaluasi Kinerja Baru</span>
        </button>
    </div>

    <!-- Reviews Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Departemen</th>
                        <th class="py-3 px-4">Periode</th>
                        <th class="py-3 px-4 text-center">Skor KPI (50%)</th>
                        <th class="py-3 px-4 text-center">Absensi (30%)</th>
                        <th class="py-3 px-4 text-center">Teamwork (20%)</th>
                        <th class="py-3 px-4 text-center">Final Grade</th>
                        <th class="py-3 px-4">Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $r)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $r->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $r->user->nik }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $r->user->department->name ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 font-semibold text-slate-800">
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
                                        GRADE A (Sangat Baik)
                                    </span>
                                @elseif($r->final_grade === 'B')
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-blue-50 text-blue-700 border border-blue-300">
                                        GRADE B (Baik)
                                    </span>
                                @elseif($r->final_grade === 'C')
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-amber-50 text-amber-700 border border-amber-300">
                                        GRADE C (Cukup)
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-rose-50 text-rose-700 border border-rose-300">
                                        GRADE D (Kurang)
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate italic" title="{{ $r->feedback }}">
                                {{ $r->feedback ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-award text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada data evaluasi kinerja untuk periode yang dipilih.</p>
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

<!-- Modal Input Appraisal -->
<div id="appraisalModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Input Form Penilaian Kinerja Karyawan</h4>
            <button onclick="closeAppraisalModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.performance.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Karyawan <span class="text-rose-500">*</span></label>
                <select name="user_id" id="user_id" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }} - {{ $emp->department->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="period_year" class="block text-xs font-bold text-slate-700 mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                    <input type="number" name="period_year" id="period_year" required value="{{ date('Y') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="period_quarter" class="block text-xs font-bold text-slate-700 mb-1.5">Periode <span class="text-rose-500">*</span></label>
                    <select name="period_quarter" id="period_quarter" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        <option value="Q1">Kuartal 1 (Q1)</option>
                        <option value="Q2">Kuartal 2 (Q2)</option>
                        <option value="Q3">Kuartal 3 (Q3)</option>
                        <option value="Q4">Kuartal 4 (Q4)</option>
                        <option value="Annual">Tahunan (Annual)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label for="kpi_score" class="block text-xs font-bold text-slate-700 mb-1.5">Skor KPI (0-100)</label>
                    <input type="number" name="kpi_score" id="kpi_score" min="0" max="100" required value="85"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
                <div>
                    <label for="attendance_score" class="block text-xs font-bold text-slate-700 mb-1.5">Absensi (0-100)</label>
                    <input type="number" name="attendance_score" id="attendance_score" min="0" max="100" required value="90"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
                <div>
                    <label for="teamwork_score" class="block text-xs font-bold text-slate-700 mb-1.5">Teamwork (0-100)</label>
                    <input type="number" name="teamwork_score" id="teamwork_score" min="0" max="100" required value="85"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="feedback" class="block text-xs font-bold text-slate-700 mb-1.5">Umpan Balik / Catatan Pengembangan</label>
                <textarea name="feedback" id="feedback" rows="3" placeholder="Berikan catatan pencapaian target dan area perbaikan..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeAppraisalModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Simpan Nilai Kinerja
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAppraisalModal() {
        document.getElementById('appraisalModal').classList.remove('hidden');
        document.getElementById('appraisalModal').classList.add('flex');
    }

    function closeAppraisalModal() {
        document.getElementById('appraisalModal').classList.add('hidden');
        document.getElementById('appraisalModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
