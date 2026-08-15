@extends('layouts.app')

@section('title', 'Manajemen Penggajian')
@section('page-title', 'Manajemen Penggajian (Payroll)')
@section('page-subtitle', 'Otomatisasi kalkulasi gaji bulanan, pemotongan keterlambatan, dan pencetakan slip gaji resmi.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Pembayaran Gaji -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Payroll (Bulan Ini)</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900 font-mono">Rp {{ number_format($totalPayout, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Estimasi total pembayaran bersih</p>
        </div>

        <!-- Total Karyawan Terdaftar di Payroll -->
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Karyawan Terproses</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-emerald-600">{{ $totalEmployeesPaid }} <span class="text-xs text-slate-400 font-normal">Karyawan</span></h3>
            <p class="text-[11px] text-slate-400 mt-1">Data penggajian periode {{ $month }}</p>
        </div>

        <!-- Tombol Aksi Generate -->
        <div class="saas-card p-5 flex flex-col justify-between bg-gradient-to-br from-blue-900 to-slate-900 text-white border-none shadow-md">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-200">Kalkulasi Otomatis</h4>
                <p class="text-xs text-slate-300 mt-1">Hitung gaji berdasarkan log kehadiran & denda keterlambatan.</p>
            </div>

            <form action="{{ route('admin.payroll.generate') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" onclick="return confirm('Generate / perbarui payroll untuk periode {{ $month }}?')"
                    class="w-full py-2 px-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Generate Payroll Periode Ini</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Filter & Export Toolbar -->
    <div class="saas-card p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.payroll.index') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div>
                <label for="month" class="block text-[11px] font-bold text-slate-600 mb-1">Periode Bulan</label>
                <input type="month" name="month" id="month" value="{{ $month }}" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="department_id" class="block text-[11px] font-bold text-slate-600 mb-1">Departemen</label>
                <select name="department_id" id="department_id" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Export CSV Button -->
        <a href="{{ route('admin.export.payroll', ['month' => $month]) }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-300 flex items-center justify-center gap-2 transition shadow-sm">
            <i class="fa-solid fa-file-csv text-emerald-600 text-sm"></i>
            <span>Export Rekap CSV</span>
        </a>
    </div>

    <!-- Payroll Data Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Departemen</th>
                        <th class="py-3 px-4">Gaji Pokok</th>
                        <th class="py-3 px-4">Tunjangan</th>
                        <th class="py-3 px-4">Denda Telat</th>
                        <th class="py-3 px-4">Total Gaji Bersih</th>
                        <th class="py-3 px-4 text-center">Kehadiran</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Slip Gaji</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrolls as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $p->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $p->user->nik }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $p->user->department->name ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-medium text-slate-700">
                                Rp {{ number_format($p->basic_salary, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-medium text-emerald-700">
                                +Rp {{ number_format($p->allowances, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-medium text-rose-600">
                                -Rp {{ number_format($p->late_deduction, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900 text-xs">
                                Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $p->total_present_days }} Hadir ({{ $p->total_late_days }} Telat)
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($p->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Terbayar
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Published
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('payroll.slip', $p->id) }}" target="_blank"
                                    class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs border border-blue-200 transition inline-flex items-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-print"></i>
                                    <span>Cetak</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-file-invoice text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada data payroll untuk periode {{ $month }}. Silakan klik tombol "Generate Payroll".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $payrolls->links() }}
        </div>
    </div>
</div>
@endsection
