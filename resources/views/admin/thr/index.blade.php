@extends('layouts.app')

@section('title', 'Tunjangan Hari Raya (THR)')
@section('page-title', 'Tunjangan Hari Raya Keagamaan (THR)')
@section('page-subtitle', 'Kalkulasi otomatis hak THR berdasarkan masa kerja regulasi Kemnaker RI dan penerbitan slip pembayaran.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pembayaran THR</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 font-mono">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Periode {{ $holidayName }} {{ $selectedYear }}</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Formula Kemnaker</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-calculator"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Pro-Rata Otomatis</h3>
            <p class="text-[11px] text-slate-500 mt-1">Masa kerja >= 12 bulan (1x gaji) | < 12 bulan (masa kerja/12 x gaji).</p>
        </div>

        <!-- Button Generate THR -->
        <div class="saas-card p-5 flex items-center justify-center bg-gradient-to-br from-emerald-800 to-slate-900 text-white border-none shadow-md">
            <button type="button" onclick="openThrModal()" class="w-full py-2.5 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-900 text-xs font-extrabold shadow transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>Hitung & Terbitkan THR Batch</span>
            </button>
        </div>
    </div>

    <!-- THR Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Tgl Masuk</th>
                        <th class="py-3 px-4">Masa Kerja</th>
                        <th class="py-3 px-4">Gaji Pokok Acuan</th>
                        <th class="py-3 px-4">Nominal THR Diterima</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Slip Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($thrPayments as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }} • {{ $item->user->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($item->user->join_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->tenure_months >= 12 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $item->tenure_months }} Bulan {{ $item->tenure_months >= 12 ? '(Penuh)' : '(Pro-rata)' }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                Rp {{ number_format($item->basic_salary, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-emerald-700 text-sm">
                                Rp {{ number_format($item->thr_amount, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-check mr-1"></i> Dibayarkan
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('thr.slip', $item->id) }}" target="_blank"
                                    class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-print text-slate-500"></i>
                                    <span>Slip THR</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-hand-holding-dollar text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada data pembayaran THR untuk periode ini. Klik tombol di atas untuk menghitung otomatis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Generate THR -->
<div id="thrModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Kalkulasi THR Keagamaan Otomatis</h4>
            <button onclick="closeThrModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.thr.generate') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="holiday_name" class="block text-xs font-bold text-slate-700 mb-1">Hari Raya Keagamaan <span class="text-rose-500">*</span></label>
                <select name="holiday_name" id="holiday_name" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-emerald-600">
                    <option value="Idul Fitri 1447 H">Hari Raya Idul Fitri</option>
                    <option value="Natal & Tahun Baru">Hari Raya Natal</option>
                    <option value="Tahun Baru Imlek">Tahun Baru Imlek</option>
                    <option value="Hari Raya Nyepi">Hari Raya Nyepi</option>
                    <option value="Hari Raya Waisak">Hari Raya Waisak</option>
                </select>
            </div>

            <div>
                <label for="year" class="block text-xs font-bold text-slate-700 mb-1">Tahun Anggaran <span class="text-rose-500">*</span></label>
                <input type="number" name="year" id="year" required value="{{ date('Y') }}"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-emerald-600">
            </div>

            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800">
                <i class="fa-solid fa-info-circle mr-1"></i> Sistem akan menghitung masa kerja tiap karyawan dari tanggal bergabung (*join date*) dan menerapkan rumus pro-rata secara otomatis.
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeThrModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-500/20">
                    Hitung & Terbitkan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openThrModal() {
        document.getElementById('thrModal').classList.remove('hidden');
        document.getElementById('thrModal').classList.add('flex');
    }

    function closeThrModal() {
        document.getElementById('thrModal').classList.add('hidden');
        document.getElementById('thrModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
