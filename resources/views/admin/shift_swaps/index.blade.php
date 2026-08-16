@extends('layouts.app')

@section('title', 'Persetujuan Tukar Shift')
@section('page-title', 'Persetujuan Pertukaran Shift Kerja')
@section('page-subtitle', 'Tinjau permohonan tukar jam kerja antar staf satu divisi.')

@section('content')
<div class="space-y-6">

    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Pemohon (Requester)</th>
                        <th class="py-3 px-4">Rekan Pengganti</th>
                        <th class="py-3 px-4">Tanggal Pertukaran</th>
                        <th class="py-3 px-4">Shift Pemohon</th>
                        <th class="py-3 px-4">Shift Pengganti</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($swaps as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $item->requester->name }}
                                <span class="block text-[11px] text-slate-500 font-mono">{{ $item->requester->nik }}</span>
                            </td>

                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $item->targetUser->name }}
                                <span class="block text-[11px] text-slate-500 font-mono">{{ $item->targetUser->nik }}</span>
                            </td>

                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ \Carbon\Carbon::parse($item->swap_date)->translatedFormat('d F Y') }}
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-200">
                                    {{ $item->requesterShift->name }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                                    {{ $item->targetShift->name }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $item->reason }}">
                                {{ $item->reason }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'pending_admin')
                                    <form action="{{ route('admin.shift-swaps.approve', $item->id) }}" method="POST" onsubmit="return confirm('Setujui pertukaran shift ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Setujui</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-400">
                                Tidak ada permohonan pertukaran shift aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
