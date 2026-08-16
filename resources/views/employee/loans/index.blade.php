@extends('layouts.employee_app')

@section('title', 'Pinjaman & Kasbon')
@section('page-title', 'Pinjaman Karyawan & Kasbon Darurat')
@section('page-subtitle', 'Ajukan fasilitas pinjaman internal perusahaan dengan skema cicilan potong gaji transparan.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Pengajuan Pinjaman (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Permohonan Pinjaman</h4>
            <p class="text-xs text-slate-500 mb-6">Tentukan nominal dan jangka waktu tenor cicilan bulanan</p>

            <form action="{{ route('employee.loans.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nominal Pinjaman (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" required min="500000" max="20000000" step="500000"
                            placeholder="Contoh: 3000000" oninput="calculateInstallment()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="tenor_months" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tenor Cicilan <span class="text-rose-500">*</span>
                        </label>
                        <select name="tenor_months" id="tenor_months" required onchange="calculateInstallment()"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            <option value="1">1 Bulan (Potong Gaji Penuh)</option>
                            <option value="2">2 Bulan</option>
                            <option value="3" selected>3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                        </select>
                    </div>
                </div>

                <!-- Live Installment Preview -->
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-blue-900 block">Estimasi Potongan Gaji:</span>
                        <p class="text-[11px] text-blue-700">Dipotong setiap akhir bulan pada slip gaji</p>
                    </div>
                    <h3 id="monthlyInstallmentLabel" class="text-lg font-black text-blue-700 font-mono">
                        Rp 0 / Bulan
                    </h3>
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan / Keperluan Pinjaman <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan kebutuhan pengajuan kasbon darurat..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Kasbon ke HR Finance</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan Pinjaman (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Kebijakan Kasbon & Pinjaman</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Bunga 0% (Fasilitas Kesejahteraan Karyawan Internal).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Maksimal pinjaman aktif adalah 1 permohonan dalam satu waktu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Cicilan otomatis didebet pada laporan penggajian bulanan.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat Pinjaman Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pinjaman Saya</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar permohonan pinjaman kasbon yang telah diajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                        <th class="py-3 px-4">Total Pinjaman</th>
                        <th class="py-3 px-4">Tenor</th>
                        <th class="py-3 px-4">Cicilan / Bulan</th>
                        <th class="py-3 px-4">Sisa Pokok</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan HRD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 font-medium">
                                {{ $loan->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                Rp {{ number_format($loan->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">
                                    {{ $loan->tenor_months }} Bulan
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-blue-700">
                                Rp {{ number_format($loan->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($loan->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui (Aktif)
                                    </span>
                                @elseif($loan->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @elseif($loan->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $loan->admin_notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                Belum ada riwayat permohonan pinjaman atau kasbon.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
    function calculateInstallment() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const tenor = parseInt(document.getElementById('tenor_months').value) || 1;
        const label = document.getElementById('monthlyInstallmentLabel');

        if (amount > 0) {
            const installment = Math.round(amount / tenor);
            label.innerText = `Rp ${installment.toLocaleString('id-ID')} / Bulan`;
        } else {
            label.innerText = 'Rp 0 / Bulan';
        }
    }
</script>
@endpush
@endsection
