@extends('layouts.app')

@section('title', 'Aset Saya')
@section('page-title', 'Inventaris & Aset yang Saya Pegang')
@section('page-subtitle', 'Daftar perangkat laptop, smartphone dinas, dan fasilitas operasional yang diserahterimakan ke Anda.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assets as $item)
            <div class="saas-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                            @if($item->category === 'laptop') <i class="fa-solid fa-laptop"></i>
                            @elseif($item->category === 'vehicle') <i class="fa-solid fa-car"></i>
                            @elseif($item->category === 'monitor') <i class="fa-solid fa-desktop"></i>
                            @else <i class="fa-solid fa-box-open"></i> @endif
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Aktif Digunakan
                        </span>
                    </div>

                    <span class="px-2 py-0.5 rounded bg-slate-100 font-mono text-[10px] font-bold text-slate-700">{{ $item->asset_code }}</span>
                    <h4 class="text-base font-bold text-slate-900 mt-1 mb-1">{{ $item->name }}</h4>

                    <div class="space-y-2 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs mt-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Nomor Serial:</span>
                            <span class="font-mono font-bold text-slate-800">{{ $item->serial_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Kondisi Fisik:</span>
                            <span class="font-bold text-emerald-700">{{ ucfirst($item->condition) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal Serah Terima:</span>
                            <span class="font-medium text-slate-700">{{ $item->assigned_date ? \Carbon\Carbon::parse($item->assigned_date)->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 flex items-center gap-2 text-[11px] text-slate-500">
                    <i class="fa-solid fa-shield-halved text-blue-600"></i>
                    <span>Tanggung Jawab Pemegang Aset</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 saas-card p-12 text-center text-slate-400">
                <i class="fa-solid fa-laptop text-4xl mb-3 text-slate-300"></i>
                <h4 class="text-sm font-bold text-slate-700">Belum Ada Aset Terdaftar</h4>
                <p class="text-xs mt-1">Perangkat atau fasilitas yang dipinjamkan oleh kantor akan tercatat di sini.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
