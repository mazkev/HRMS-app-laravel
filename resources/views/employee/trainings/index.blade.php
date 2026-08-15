@extends('layouts.app')

@section('title', 'Pelatihan & Upskilling')
@section('page-title', 'Katalog Pelatihan & Sertifikasi (LMS Lite)')
@section('page-subtitle', 'Tingkatkan kompetensi Anda melalui program pelatihan internal yang disediakan oleh perusahaan.')

@section('content')
<div class="space-y-6">

    <!-- Available Training Catalog -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-base font-bold text-slate-900">Program Pelatihan Tersedia</h4>
                <p class="text-xs text-slate-500">Pilih program pelatihan dan daftarkan diri Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableTrainings as $item)
                @php
                    $isEnrolled = $myTrainings->contains('training_id', $item->id);
                    $isFull = $item->participants_count >= $item->capacity;
                @endphp
                <div class="saas-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                {{ $item->category }}
                            </span>
                            <span class="text-xs font-bold text-slate-600">
                                <i class="fa-solid fa-users mr-1 text-blue-600"></i> {{ $item->participants_count }}/{{ $item->capacity }} Kuota
                            </span>
                        </div>

                        <h4 class="text-base font-bold text-slate-900 mb-1">{{ $item->title }}</h4>
                        <p class="text-xs text-slate-500 mb-4 line-clamp-3 leading-relaxed">{{ $item->description }}</p>

                        <div class="space-y-2 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Instruktur:</span>
                                <span class="font-bold text-slate-800">{{ $item->trainer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tanggal:</span>
                                <span class="font-semibold text-blue-700">{{ \Carbon\Carbon::parse($item->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Lokasi:</span>
                                <span class="font-medium text-slate-700 truncate max-w-[140px]">{{ $item->location }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-4">
                        @if($isEnrolled)
                            <div class="w-full py-2.5 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200 text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Sudah Terdaftar</span>
                            </div>
                        @elseif($isFull)
                            <div class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs text-center">
                                Kuota Penuh
                            </div>
                        @else
                            <form action="{{ route('employee.trainings.enroll', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-user-plus text-[11px]"></i>
                                    <span>Daftar Pelatihan</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 saas-card p-12 text-center text-slate-400">
                    <i class="fa-solid fa-graduation-cap text-4xl mb-3 text-slate-300"></i>
                    <h4 class="text-sm font-bold text-slate-700">Belum Ada Pelatihan Aktif</h4>
                    <p class="text-xs mt-1">Program pelatihan baru akan diumumkan segera oleh tim HRD.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
