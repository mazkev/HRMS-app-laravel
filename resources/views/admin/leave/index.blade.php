@extends('layouts.app')

@section('title', 'Persetujuan Cuti & Izin')
@section('page-title', 'Persetujuan Cuti & Izin Karyawan')
@section('page-subtitle', 'Tinjau permohonan cuti tahunan, cuti sakit (SKD), melahirkan, dan izin khusus karyawan.')

@section('content')
<div class="space-y-6">

    <!-- Filters & Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leave.index') }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($status) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Permohonan
            </a>
            <a href="{{ route('admin.leave.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Menunggu Review
            </a>
            <a href="{{ route('admin.leave.index', ['status' => 'approved']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Disetujui
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Jenis Cuti</th>
                        <th class="py-3 px-4">Rentang Tanggal</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Dokumen SKD</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($leaveRequests as $leave)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $leave->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $leave->user->nik }} • Sisa: {{ $leave->user->leave_quota }} hari</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase font-bold
                                    @if($leave->leave_type === 'sick') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($leave->leave_type === 'maternity') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($leave->leave_type === 'marriage') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ $leave->total_days }} Hari
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $leave->reason }}">
                                {{ $leave->reason }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($leave->medical_certificate)
                                    <a href="{{ asset('storage/' . $leave->medical_certificate) }}" target="_blank" class="px-2 py-1 rounded bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold inline-flex items-center gap-1 hover:bg-blue-100">
                                        <i class="fa-solid fa-file-medical"></i>
                                        <span>Lihat SKD</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                @if($leave->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($leave->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($leave->status === 'pending')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <form action="{{ route('admin.leave.approve', $leave->id) }}" method="POST" onsubmit="return confirm('Setujui permohonan cuti {{ $leave->user->name }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-sm" title="Setujui Cuti">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <button type="button" onclick="openRejectModal({{ $leave->id }}, '{{ $leave->user->name }}')" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-xs transition" title="Tolak Cuti">
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
                                <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan cuti atau izin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $leaveRequests->links() }}
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tolak Permohonan Cuti</h4>
        <p class="text-xs text-slate-500 mb-4" id="rejectDesc">Berikan alasan penolakan:</p>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="admin_notes" required rows="3" placeholder="Contoh: Jadwal bertabrakan dengan deployment penting atau staf pengganti belum tersedia..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                    Tolak Cuti
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(id, name) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/leave-requests/${id}/reject`;
        document.getElementById('rejectDesc').innerText = `Penolakan cuti untuk ${name}:`;
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
