@extends('layouts.app')

@section('title', 'Offboarding & Resignasi')
@section('page-title', 'Manajemen Resignasi & Paklaring (Offboarding)')
@section('page-subtitle', 'Tinjau permohonan pengunduran diri 1-month notice, checklist exit clearance, dan penerbitan Surat Paklaring.')

@section('content')
<div class="space-y-6">

    <!-- Resignations Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Tgl Pengajuan</th>
                        <th class="py-3 px-4">Efektif Resign</th>
                        <th class="py-3 px-4">Alasan Resign</th>
                        <th class="py-3 px-4">No. Surat Paklaring</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($resignations as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }} • {{ $item->user->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($item->notice_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ \Carbon\Carbon::parse($item->resign_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $item->reason }}">
                                {{ $item->reason }}
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $item->paklaring_number ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'pending')
                                    <form action="{{ route('admin.resignations.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui resignasi {{ $item->user->name }} dan terbitkan Surat Pengalaman Kerja (Paklaring)?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Setujui & Terbitkan Paklaring</span>
                                        </button>
                                    </form>
                                @elseif($item->status === 'approved')
                                    <a href="{{ route('resignations.paklaring', $item->id) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-print text-slate-500"></i>
                                        <span>Cetak Paklaring</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-user-xmark text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan resignasi atau offboarding aktif.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $resignations->links() }}
        </div>
    </div>

</div>
@endsection
