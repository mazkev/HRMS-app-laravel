@extends('layouts.app')

@section('title', 'Pinjaman & Kasbon')
@section('page-title', 'Persetujuan Pinjaman & Kasbon Karyawan')
@section('page-subtitle', 'Tinjau permohonan pinjaman darurat dan persetujuan skema cicilan potong gaji.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pinjaman Aktif Berjalan</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-blue-600 font-mono">Rp {{ number_format($totalActiveLoans, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Sisa pokok pinjaman yang sedang dicicil</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Potongan Payroll Otomatis</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-calculator"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Auto Deduct Active</h3>
            <p class="text-[11px] text-slate-500 mt-1">Cicilan bulanan otomatis memotong gaji bersih pada modul Payroll.</p>
        </div>
    </div>

    <!-- Loan List Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Total Pinjaman</th>
                        <th class="py-3 px-4">Tenor</th>
                        <th class="py-3 px-4">Cicilan / Bulan</th>
                        <th class="py-3 px-4">Sisa Pinjaman</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $loan->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $loan->user->nik }} • {{ $loan->user->department->name ?? '-' }}</p>
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

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $loan->reason }}">
                                {{ $loan->reason }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($loan->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
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

                            <td class="py-3.5 px-4 text-center">
                                @if($loan->status === 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" onsubmit="return confirm('Setujui pinjaman Rp {{ number_format($loan->amount, 0, ',', '.') }} untuk {{ $loan->user->name }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm" title="Setujui Pinjaman">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openRejectLoanModal({{ $loan->id }}, '{{ $loan->user->name }}')" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition" title="Tolak Pinjaman">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-hand-holding-dollar text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan pinjaman atau kasbon aktif.</p>
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

<!-- Modal Reject Loan -->
<div id="rejectLoanModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Permohonan Pinjaman</h4>
        <p class="text-xs text-slate-500 mb-4" id="rejectLoanDesc">Berikan alasan penolakan pinjaman:</p>

        <form id="rejectLoanForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" required rows="3" placeholder="Contoh: Plafon pinjaman melebihi batas atau masa kerja belum mencukupi..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectLoanModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                    Tolak Pinjaman
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectLoanModal(id, name) {
        const form = document.getElementById('rejectLoanForm');
        form.action = `/admin/loans/${id}/reject`;
        document.getElementById('rejectLoanDesc').innerText = `Penolakan pinjaman untuk ${name}:`;
        document.getElementById('rejectLoanModal').classList.remove('hidden');
        document.getElementById('rejectLoanModal').classList.add('flex');
    }

    function closeRejectLoanModal() {
        document.getElementById('rejectLoanModal').classList.add('hidden');
        document.getElementById('rejectLoanModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
