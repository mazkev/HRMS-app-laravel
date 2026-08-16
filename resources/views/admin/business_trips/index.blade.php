@extends('layouts.app')

@section('title', 'Perjalanan Dinas (SPPD)')
@section('page-title', 'Persetujuan Surat Perintah Perjalanan Dinas (SPPD)')
@section('page-subtitle', 'Tinjau permohonan dinas luar kota/negeri, alokasi uang saku harian (per diem), dan penerbitan SPPD resmi.')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Uang Saku Dinas Disetujui</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-plane-departure"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-blue-600 font-mono">Rp {{ number_format($totalAllowanceDisbursed, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">Pagu uang saku harian (per diem allowance)</p>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Standar Uang Harian</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Rp 350.000 / Hari</h3>
            <p class="text-[11px] text-slate-500 mt-1">Pagu standar operasional dinas luar kota PT Maju Nusantara.</p>
        </div>
    </div>

    <!-- SPPD Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Kota Tujuan</th>
                        <th class="py-3 px-4">Agenda / Keperluan</th>
                        <th class="py-3 px-4">Jadwal Tugas</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Uang Saku (Per Diem)</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trips as $trip)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $trip->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $trip->user->nik }} • {{ $trip->user->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                <i class="fa-solid fa-location-dot mr-1 text-rose-500"></i> {{ $trip->destination_city }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $trip->purpose }}">
                                {{ $trip->purpose }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($trip->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($trip->end_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $trip->total_days }} Hari
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">
                                Rp {{ number_format($trip->total_allowance, 0, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($trip->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($trip->status === 'rejected')
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
                                @if($trip->status === 'pending')
                                    <form action="{{ route('admin.business-trips.approve', $trip->id) }}" method="POST" onsubmit="return confirm('Setujui tugas perjalanan dinas {{ $trip->user->name }} dan terbitkan SPPD resmi?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Setujui & Terbitkan SPPD</span>
                                        </button>
                                    </form>
                                @elseif($trip->status === 'approved')
                                    <a href="{{ route('business-trips.print', $trip->id) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fa-solid fa-print text-slate-500"></i>
                                        <span>Cetak Lembar SPPD</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-plane-slash text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada permohonan perjalanan dinas atau SPPD aktif.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $trips->links() }}
        </div>
    </div>

</div>
@endsection
