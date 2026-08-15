@extends('layouts.app')

@section('title', 'Persetujuan Lembur')
@section('page-title', 'Persetujuan & Manajemen Lembur')
@section('page-subtitle', 'Tinjau permohonan jam kerja lembur karyawan dan lakukan persetujuan atau penolakan.')

@section('content')
<div class="space-y-6">

    <!-- Status Tabs & Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.overtime.index') }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($status) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Status
            </a>
            <a href="{{ route('admin.overtime.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-regular fa-clock mr-1"></i> Menunggu Review
            </a>
            <a href="{{ route('admin.overtime.index', ['status' => 'approved']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-check mr-1"></i> Disetujui
            </a>
            <a href="{{ route('admin.overtime.index', ['status' => 'rejected']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-xmark mr-1"></i> Ditolak
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.overtime.index') }}" class="flex items-center gap-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / NIK..."
                class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 shadow-sm">
            <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Table Card -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Departemen</th>
                        <th class="py-3 px-4">Tanggal Lembur</th>
                        <th class="py-3 px-4">Rentang Waktu</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Alasan / Pekerjaan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan Reviewer</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($overtimes as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $item->user->department->name ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-700">
                                {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200 text-[11px]">
                                    {{ $item->duration_hours }} Jam
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate" title="{{ $item->reason }}">
                                {{ $item->reason }}
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
                                        <i class="fa-regular fa-clock mr-1"></i> Menunggu Review
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                                @if($item->approver)
                                    <p class="font-bold text-slate-700">Oleh: {{ $item->approver->name }}</p>
                                @endif
                                <p class="italic text-slate-500">{{ $item->admin_notes ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('admin.overtime.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui lembur {{ $item->user->name }} selama {{ $item->duration_hours }} jam?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm" title="Setujui Lembur">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openRejectModal({{ $item->id }}, '{{ $item->user->name }}')" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition" title="Tolak Lembur">
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
                            <td colspan="9" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-business-time text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan lembur yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $overtimes->links() }}
        </div>
    </div>
</div>

<!-- Modal Reject Overtime -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Pengajuan Lembur</h4>
        <p class="text-xs text-slate-500 mb-4" id="rejectModalDesc">Berikan alasan penolakan lembur:</p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" required rows="3" placeholder="Contoh: Pekerjaan di luar instruksi resmi..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                    Tolak Lembur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(id, name) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/overtimes/${id}/reject`;
        document.getElementById('rejectModalDesc').innerText = `Penolakan lembur untuk ${name}:`;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
