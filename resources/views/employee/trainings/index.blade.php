@extends('layouts.employee_app')

@section('title', 'Pelatihan & Upskilling')
@section('page-title', 'Katalog Pelatihan 🎓')
@section('page-subtitle', 'Tingkatkan kompetensi dengan program pelatihan & sertifikasi internal')

@section('content')
<div class="space-y-4">

    <div class="space-y-3">
        @forelse($trainings as $item)
            <div class="saas-card p-4 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">
                        {{ $item->category ?? 'General' }}
                    </span>

                    @if(in_array($item->id, $enrolledTrainingIds))
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
                            <i class="fa-solid fa-check text-[9px]"></i> Terdaftar
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            Buka Pendaftaran
                        </span>
                    @endif
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-900">{{ $item->title }}</h4>
                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $item->description }}</p>
                </div>

                <div class="space-y-1 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600">
                    <div class="flex justify-between">
                        <span>Trainer:</span>
                        <span class="font-bold text-slate-800">{{ $item->trainer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jadwal:</span>
                        <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($item->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($item->end_date)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Lokasi / Ruangan:</span>
                        <span class="text-slate-800 font-medium">{{ $item->location ?? 'Online Zoom' }}</span>
                    </div>
                </div>

                @if(!in_array($item->id, $enrolledTrainingIds))
                    <form action="{{ route('employee.trainings.enroll', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md shadow-indigo-500/20 flex items-center justify-center gap-1.5 transition active:scale-95">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>Daftar Pelatihan Ini</span>
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="saas-card p-6 text-center text-slate-400 text-xs">
                <i class="fa-solid fa-graduation-cap text-3xl mb-1 text-slate-300"></i>
                <p>Belum ada program pelatihan baru yang dibuka.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
