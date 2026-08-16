@extends('layouts.employee_app')

@section('title', 'Slip Gaji Saya')
@section('page-title', 'Slip Gaji Saya 💰')
@section('page-subtitle', 'Rincian penerimaan gaji bulanan, potongan PPh 21 TER, BPJS, dan unduh slip resmi')

@section('content')
<div class="space-y-4">

    <!-- 1. SALARY OVERVIEW WALLET -->
    <div class="saas-card p-4 bg-gradient-to-br from-blue-800 to-slate-900 text-white shadow-md">
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[10px] font-extrabold text-blue-200 uppercase tracking-wider">Gaji Pokok Terdaftar</span>
            <span class="px-2 py-0.5 rounded-full bg-emerald-500/30 border border-emerald-400/40 text-[10px] font-bold text-emerald-300">
                Payroll Aktif
            </span>
        </div>

        <h3 class="text-2xl font-black text-white font-mono">
            Rp {{ number_format($user->salary, 0, ',', '.') }}
        </h3>
        <p class="text-[11px] text-blue-200 mt-1">{{ $user->position }} • {{ $user->department->name ?? 'PT Maju' }}</p>

        <div class="mt-3 pt-2.5 border-t border-white/15 flex items-center justify-between text-[10px] text-blue-200">
            <span>Status PTKP: <strong>{{ $user->ptkp_status ?? 'TK/0' }}</strong></span>
            <span>Jadwal Gajian: <strong>Tgl 25 - Akhir Bulan</strong></span>
        </div>
    </div>

    <!-- 2. MOBILE PAYSLIP HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Daftar Slip Gaji Bulanan</h4>

        <div class="space-y-3">
            @forelse($payrolls as $p)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Periode</span>
                            <h4 class="text-xs font-extrabold text-slate-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $p->period_month)->translatedFormat('F Y') }}
                            </h4>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 uppercase">
                            {{ $p->status }}
                        </span>
                    </div>

                    <!-- Breakdown Numbers -->
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Gaji Pokok:</span>
                            <span class="font-mono font-semibold">Rp {{ number_format($p->basic_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-700">
                            <span>Tunjangan Tetap:</span>
                            <span class="font-mono font-semibold">+Rp {{ number_format($p->allowances, 0, ',', '.') }}</span>
                        </div>
                        @if($p->pph21_amount > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Pajak PPh 21 (TER):</span>
                                <span class="font-mono font-semibold">-Rp {{ number_format($p->pph21_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($p->bpjs_kesehatan_deduction > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>BPJS Kesehatan (1%):</span>
                                <span class="font-mono font-semibold">-Rp {{ number_format($p->bpjs_kesehatan_deduction, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($p->bpjs_tk_deduction > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>BPJS TK (JHT & JP 3%):</span>
                                <span class="font-mono font-semibold">-Rp {{ number_format($p->bpjs_tk_deduction, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if($p->loan_deduction > 0)
                            <div class="flex justify-between text-cyan-700">
                                <span>Cicilan Kasbon:</span>
                                <span class="font-mono font-semibold">-Rp {{ number_format($p->loan_deduction, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Net Salary Banner -->
                    <div class="p-2.5 rounded-xl bg-gradient-to-r from-blue-900 to-slate-900 text-white flex items-center justify-between">
                        <span class="text-[10px] font-bold text-blue-200 uppercase">Gaji Bersih (THP):</span>
                        <h4 class="text-sm font-black text-emerald-300 font-mono">
                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                        </h4>
                    </div>

                    <!-- Print Action Button -->
                    <a href="{{ route('payroll.slip', $p->id) }}" target="_blank"
                        class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-1.5 transition active:scale-95">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak / Unduh Slip Gaji PDF</span>
                    </a>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-file-invoice-dollar text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada slip gaji yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
