@extends('layouts.employee_app')

@section('title', 'Slip Gaji Saya')
@section('page-title', 'Informasi Penggajian & Slip Gaji')
@section('page-subtitle', 'Lihat rincian gaji bulanan, tunjangan, dan unduh slip gaji resmi Anda.')

@section('content')
<div class="space-y-6">

    <!-- Salary Overview Card -->
    <div class="saas-card p-6 border-slate-200 bg-gradient-to-r from-blue-900 to-slate-900 text-white border-none shadow-md">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-white text-2xl">
                    <i class="fa-solid fa-wallet text-blue-300"></i>
                </div>
                <div>
                    <span class="text-xs font-bold text-blue-200 uppercase tracking-wider">Gaji Pokok Terdaftar</span>
                    <h3 class="text-2xl font-black text-white font-mono mt-0.5">Rp {{ number_format($user->salary, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-300 mt-1">{{ $user->position }} • {{ $user->department->name ?? '-' }}</p>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold">
                    Payroll Aktif Bulanan
                </span>
            </div>
        </div>
    </div>

    <!-- Monthly Slips Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Riwayat Slip Gaji</h4>
                <p class="text-xs text-slate-500">Daftar slip pembayaran gaji bulanan yang telah diterbitkan HRD</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Periode Bulan</th>
                        <th class="py-3 px-4">Gaji Pokok</th>
                        <th class="py-3 px-4">Tunjangan</th>
                        <th class="py-3 px-4">Potongan Telat</th>
                        <th class="py-3 px-4">Total Gaji Bersih</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payrolls as $p)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $p->period_month)->translatedFormat('F Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                Rp {{ number_format($p->basic_salary, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-emerald-700">
                                +Rp {{ number_format($p->allowances, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-rose-600">
                                -Rp {{ number_format($p->late_deduction, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                    {{ $p->status }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('payroll.slip', $p->id) }}" target="_blank"
                                    class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                    <span>Lihat & Cetak Slip</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada slip gaji yang diterbitkan untuk akun Anda.</p>
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
