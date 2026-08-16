@extends('layouts.employee_app')

@section('title', 'Pinjaman & Kasbon')
@section('page-title', 'Pinjaman & Kasbon 💸')
@section('page-subtitle', 'Fasilitas pinjaman darurat bunga 0% dengan cicilan otomatis via Payroll')

@section('content')
<div class="space-y-4">

    <!-- 1. ACTIVE LOAN WALLET SUMMARY -->
    @if(isset($activeLoan) && $activeLoan)
        <div class="saas-card p-4 bg-gradient-to-br from-emerald-800 to-slate-900 text-white shadow-md">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold text-emerald-300 uppercase tracking-wider">Pinjaman Aktif</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/30 border border-emerald-400/40 text-[10px] font-bold text-white">
                    Tenor: {{ $activeLoan->tenor_months }} Bulan
                </span>
            </div>

            <div>
                <span class="text-[10px] text-slate-300 block">Sisa Saldo Pinjaman:</span>
                <h3 class="text-2xl font-black text-white font-mono mt-0.5">
                    Rp {{ number_format($activeLoan->remaining_amount, 0, ',', '.') }}
                </h3>
            </div>

            <div class="mt-3 pt-2.5 border-t border-white/15 flex items-center justify-between text-[11px] text-emerald-200">
                <span>Potongan Payroll: <strong>Rp {{ number_format($activeLoan->monthly_installment, 0, ',', '.') }} / bln</strong></span>
                <span class="font-bold text-amber-300">Bunga 0%</span>
            </div>
        </div>
    @endif

    <!-- 2. MOBILE LOAN APPLICATION FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-hand-holding-dollar text-blue-600"></i>
            <span>Formulir Pengajuan Kasbon</span>
        </h4>

        <form action="{{ route('employee.loans.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="amount" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Nominal Pinjaman (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="amount" id="loanAmount" required min="500000" max="20000000" step="100000" placeholder="3000000"
                        oninput="calculateInstallment()"
                        class="w-full pl-9 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Plafon maksimal: Rp 20.000.000.</p>
            </div>

            <div>
                <label for="tenor_months" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Tenor Cicilan (Bulan) <span class="text-rose-500">*</span>
                </label>
                <select name="tenor_months" id="loanTenor" required onchange="calculateInstallment()"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                    <option value="1">1 Bulan</option>
                    <option value="3" selected>3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">12 Bulan</option>
                </select>
            </div>

            <!-- Installment Preview Box -->
            <div class="p-3 rounded-2xl bg-blue-50/70 border border-blue-200 flex items-center justify-between text-xs">
                <div>
                    <span class="text-[10px] text-slate-500 font-bold uppercase block">Estimasi Cicilan Bulanan:</span>
                    <h4 id="installmentPreview" class="text-sm font-black text-blue-700 font-mono">Rp 1.000.000 / bln</h4>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Bunga 0%</span>
            </div>

            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Keperluan Pinjaman <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="2" required placeholder="Contoh: Kebutuhan darurat keluarga..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Ajukan Permohonan Kasbon</span>
            </button>
        </form>
    </div>

    <!-- 3. MOBILE LOANS HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Pinjaman Saya</h4>

        <div class="space-y-2.5">
            @forelse($loans as $loan)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-slate-900">
                            Rp {{ number_format($loan->amount, 0, ',', '.') }}
                        </span>

                        @if($loan->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($loan->status === 'rejected')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Tenor: <strong>{{ $loan->tenor_months }} Bulan</strong></span>
                        <span>Cicilan: <strong>Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</strong></span>
                    </div>

                    <p class="text-[11px] text-slate-600 italic">"{{ $loan->reason }}"</p>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-hand-holding-dollar text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat pinjaman.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('scripts')
<script>
    function calculateInstallment() {
        const amount = parseFloat(document.getElementById('loanAmount').value) || 0;
        const tenor = parseInt(document.getElementById('loanTenor').value) || 1;
        const installment = Math.round(amount / tenor);
        document.getElementById('installmentPreview').innerText = 'Rp ' + installment.toLocaleString('id-ID') + ' / bln';
    }
</script>
@endpush
@endsection
