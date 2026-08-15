@extends('layouts.app')

@section('title', 'Struktur Organisasi')
@section('page-title', 'Struktur Organisasi Perusahaan (Org Chart)')
@section('page-subtitle', 'Bagan hierarki interaktif garis komando, pimpinan divisi, dan distribusi staf di PT Maju Nusantara.')

@section('content')
<div class="space-y-8">

    <!-- Top Level: Board & CEO Card -->
    <div class="flex flex-col items-center">
        <div class="saas-card p-6 border-2 border-blue-600 bg-gradient-to-br from-blue-900 to-slate-900 text-white max-w-sm w-full text-center shadow-lg relative">
            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white text-2xl mx-auto mb-3">
                <i class="fa-solid fa-crown text-amber-400"></i>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-500/30 text-blue-200 border border-blue-400/40">
                Pimpinan Tertinggi
            </span>
            <h3 class="text-base font-black text-white mt-1.5">Dewan Direksi & CEO</h3>
            <p class="text-xs text-blue-200">PT Maju Nusantara</p>
            <p class="text-[11px] text-slate-400 mt-2">Menetapkan arah strategis dan tata kelola perusahaan.</p>
        </div>

        <!-- Vertical Connecting Line -->
        <div class="w-0.5 h-8 bg-blue-300"></div>

        <!-- Management Node: HR & Operations Directorate -->
        <div class="saas-card p-5 border-2 border-slate-300 bg-white max-w-sm w-full text-center shadow-md">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 font-black flex items-center justify-center text-sm mx-auto mb-2">
                HR
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                Management Lead
            </span>
            <h4 class="text-sm font-bold text-slate-900 mt-1">{{ $adminHR->name ?? 'Admin HR Manager' }}</h4>
            <p class="text-xs text-slate-500">{{ $adminHR->position ?? 'HR Manager' }} • {{ $adminHR->nik ?? 'HR001' }}</p>
        </div>

        <!-- Vertical Connecting Line to Departments -->
        <div class="w-0.5 h-8 bg-slate-300"></div>
    </div>

    <!-- Department Grid Branches -->
    <div>
        <div class="flex items-center justify-center mb-6">
            <span class="px-4 py-1.5 rounded-full bg-slate-100 border border-slate-300 text-xs font-bold text-slate-700 shadow-sm">
                Divisi & Unit Kerja Operasional ({{ $departments->count() }} Departemen)
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($departments as $dept)
                <div class="saas-card p-6 border-t-4 border-t-blue-600 flex flex-col justify-between">
                    <div>
                        <!-- Department Title -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                                <i class="fa-solid fa-building-user"></i>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                                {{ $dept->users->count() }} Anggota
                            </span>
                        </div>

                        <h4 class="text-sm font-bold text-slate-900 mb-1">{{ $dept->name }}</h4>
                        <p class="text-[11px] text-slate-500 mb-4">{{ $dept->description ?? 'Unit kerja operasional.' }}</p>

                        <!-- Employee List under this Department -->
                        <div class="space-y-2.5 pt-3 border-t border-slate-100">
                            @forelse($dept->users as $member)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 hover:bg-white hover:shadow-sm transition">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900">{{ $member->name }}</p>
                                            <p class="text-[10px] text-blue-700 font-semibold">{{ $member->position ?? 'Staff' }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-200">
                                        {{ $member->nik }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 text-center py-4 italic">Belum ada staf yang terdaftar.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 mt-4 text-[10px] text-slate-400 text-center">
                        Terhubung ke sistem absensi & payroll
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
