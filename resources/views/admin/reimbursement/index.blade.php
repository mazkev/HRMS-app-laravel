@extends('layouts.app')

@section('title', 'Klaim Reimbursement')
@section('page-title', 'Persetujuan Klaim Reimbursement')
@section('page-subtitle', 'Tinjau bukti kuitansi pengeluaran operasional dan lakukan persetujuan pencairan.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Klaim Menunggu (Pending)</span>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-amber-600 font-mono">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Perlu ditinjau oleh HR Finance</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Integrasi Payroll</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-link"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Auto-Disbursement Ready</h3>
            <p class="text-[11px] text-slate-500 mt-1">Klaim disetujui akan diintegrasikan langsung ke slip gaji bulan berjalan.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.reimbursements.index') }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($status) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.reimbursements.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Menunggu Review
            </a>
            <a href="{{ route('admin.reimbursements.index', ['status' => 'approved']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.reimbursements.index', ['status' => 'rejected']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Ditolak
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Kategori & Judul</th>
                        <th class="py-3 px-4">Nominal Klaim</th>
                        <th class="py-3 px-4 text-center">Bukti Kuitansi</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan Review</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reimbursements as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }} • {{ $item->user->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ $item->category }}
                                </span>
                                <p class="font-semibold text-slate-900 mt-1">{{ $item->title }}</p>
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->receipt_image)
                                    <button type="button" onclick="showReceiptModal('{{ asset('storage/' . $item->receipt_image) }}', '{{ $item->title }} - Rp {{ number_format($item->amount, 0, ',', '.') }}')"
                                        class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] border border-blue-200 inline-flex items-center gap-1 transition">
                                        <i class="fa-solid fa-file-invoice"></i>
                                        <span>Lihat Struk</span>
                                    </button>
                                @else
                                    <span class="text-slate-400 text-[10px]">Tanpa Foto</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate" title="{{ $item->description }}">
                                {{ $item->description }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-regular fa-clock mr-1"></i> Menunggu
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 text-[11px] italic">
                                {{ $item->admin_notes ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('admin.reimbursements.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui klaim Rp {{ number_format($item->amount, 0, ',', '.') }} untuk {{ $item->user->name }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm" title="Setujui Klaim">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openRejectClaimModal({{ $item->id }}, '{{ $item->user->name }}')" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition" title="Tolak Klaim">
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
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan klaim reimbursement.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $reimbursements->links() }}
        </div>
    </div>
</div>

<!-- Modal Struk -->
<div id="receiptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 id="receiptModalTitle" class="text-sm font-bold text-slate-900">Bukti Kuitansi / Struk</h4>
            <button onclick="closeReceiptModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
            <img id="receiptModalImg" src="" alt="Struk Kuitansi" class="w-full max-h-[460px] object-contain">
        </div>
    </div>
</div>

<!-- Modal Reject Claim -->
<div id="rejectClaimModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Klaim Reimbursement</h4>
        <p class="text-xs text-slate-500 mb-4" id="rejectClaimDesc">Berikan alasan penolakan klaim:</p>

        <form id="rejectClaimForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" required rows="3" placeholder="Contoh: Bukti kuitansi buram atau tidak sesuai pos anggaran..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectClaimModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                    Tolak Klaim
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showReceiptModal(src, title) {
        document.getElementById('receiptModalImg').src = src;
        document.getElementById('receiptModalTitle').innerText = title;
        document.getElementById('receiptModal').classList.remove('hidden');
        document.getElementById('receiptModal').classList.add('flex');
    }

    function closeReceiptModal() {
        document.getElementById('receiptModal').classList.add('hidden');
        document.getElementById('receiptModal').classList.remove('flex');
    }

    function openRejectClaimModal(id, name) {
        const form = document.getElementById('rejectClaimForm');
        form.action = `/admin/reimbursements/${id}/reject`;
        document.getElementById('rejectClaimDesc').innerText = `Penolakan klaim untuk ${name}:`;
        document.getElementById('rejectClaimModal').classList.remove('hidden');
        document.getElementById('rejectClaimModal').classList.add('flex');
    }

    function closeRejectClaimModal() {
        document.getElementById('rejectClaimModal').classList.add('hidden');
        document.getElementById('rejectClaimModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
