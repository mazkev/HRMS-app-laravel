@extends('layouts.employee_app')

@section('title', 'Aset Saya')
@section('page-title', 'Aset & Inventaris 💻')
@section('page-subtitle', 'Perangkat kerja dan fasilitas operasional yang Anda pegang')

@section('content')
<div class="space-y-4">

    <div class="space-y-3">
        @forelse($assets as $item)
            <div class="saas-card p-4 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            @if($item->category === 'laptop') <i class="fa-solid fa-laptop"></i>
                            @elseif($item->category === 'vehicle') <i class="fa-solid fa-car"></i>
                            @elseif($item->category === 'monitor') <i class="fa-solid fa-desktop"></i>
                            @else <i class="fa-solid fa-box-open"></i> @endif
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-800">{{ $item->asset_code }}</span>
                    </div>

                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                        In Use
                    </span>
                </div>

                <div>
                    <h4 class="text-xs font-extrabold text-slate-900">{{ $item->name }}</h4>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">S/N: {{ $item->serial_number ?? '-' }}</p>
                </div>

                <div class="space-y-1 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600">
                    <div class="flex justify-between">
                        <span>Kondisi Fisik:</span>
                        <span class="font-bold text-emerald-700">{{ ucfirst($item->condition) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tgl Serah Terima:</span>
                        <span class="font-semibold text-slate-700">{{ $item->assigned_date ? \Carbon\Carbon::parse($item->assigned_date)->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="saas-card p-6 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-laptop text-3xl mb-1 text-slate-300"></i>
                <p>Belum ada aset terdaftar atas nama Anda.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
