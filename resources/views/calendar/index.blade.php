@extends('layouts.app')

@section('title', 'Kalender Cuti Tim')
@section('page-title', 'Kalender Cuti Bersama')
@section('page-subtitle', 'Pantau jadwal ketersediaan rekan kerja dan jadwal cuti yang telah disetujui di seluruh divisi.')

@section('content')
<div class="space-y-6">

    <!-- Filter Toolbar -->
    <div class="saas-card p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route(Auth::user()->role === 'admin_hr' ? 'admin.calendar.index' : 'employee.calendar.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <div>
                <label for="month" class="block text-[11px] font-bold text-slate-600 mb-1">Periode Bulan</label>
                <input type="month" name="month" id="month" value="{{ $month }}" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="department_id" class="block text-[11px] font-bold text-slate-600 mb-1">Departemen</label>
                <select name="department_id" id="department_id" onchange="this.form.submit()"
                    class="p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">
                {{ $approvedLeaves->count() }} Permohonan Cuti Terjadwal
            </span>
        </div>
    </div>

    <!-- Calendar Grid View -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Main Monthly Grid (8 Cols) -->
        <div class="lg:col-span-8 saas-card p-6">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <h4 class="text-sm font-bold text-slate-900">
                    Kalender Bulan {{ $startOfMonth->translatedFormat('F Y') }}
                </h4>
                <div class="flex items-center gap-2 text-[11px] text-slate-500">
                    <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
                    <span>Cuti Disetujui</span>
                </div>
            </div>

            <!-- Calendar Days Grid -->
            <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-slate-400 mb-2">
                <div>Min</div>
                <div>Sen</div>
                <div>Sel</div>
                <div>Rab</div>
                <div>Kam</div>
                <div>Jum</div>
                <div>Sab</div>
            </div>

            @php
                $firstDayOfWeek = $startOfMonth->copy()->startOfMonth()->dayOfWeek; // 0 (Sun) - 6 (Sat)
                $daysInMonth = $startOfMonth->copy()->daysInMonth;
                $todayDate = now()->toDateString();
            @endphp

            <div class="grid grid-cols-7 gap-2">
                {{-- Empty cells before start of month --}}
                @for($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="min-h-[85px] p-2 bg-slate-50/50 rounded-xl border border-dashed border-slate-200/60"></div>
                @endfor

                {{-- Actual days of month --}}
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = $startOfMonth->copy()->setDay($day)->toDateString();
                        $leavesOnThisDay = $approvedLeaves->filter(function($item) use ($currentDate) {
                            return $currentDate >= $item->start_date->format('Y-m-d') && $currentDate <= $item->end_date->format('Y-m-d');
                        });
                        $isToday = ($currentDate === $todayDate);
                    @endphp

                    <div class="min-h-[85px] p-2 rounded-xl border flex flex-col justify-between transition {{ $isToday ? 'bg-blue-50/70 border-blue-400 shadow-sm' : 'bg-white border-slate-200/80 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold {{ $isToday ? 'text-blue-700 font-extrabold' : 'text-slate-700' }}">{{ $day }}</span>
                            @if($isToday)
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            @endif
                        </div>

                        <div class="space-y-1 mt-1 overflow-y-auto max-h-[55px]">
                            @foreach($leavesOnThisDay as $leave)
                                <div class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 text-[10px] font-semibold truncate text-left shadow-2xs" title="{{ $leave->user->name }} ({{ $leave->user->department->name ?? '-' }})">
                                    {{ $leave->user->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Upcoming Approved Leaves List (4 Cols) -->
        <div class="lg:col-span-4 saas-card p-6 flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                    Daftar Karyawan Cuti Bulan Ini
                </h4>

                <div class="space-y-3 overflow-y-auto max-h-[480px]">
                    @forelse($approvedLeaves as $item)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <div class="flex items-center justify-between">
                                <h5 class="text-xs font-bold text-slate-900">{{ $item->user->name }}</h5>
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold">
                                    {{ $item->total_days }} Hari
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500">{{ $item->user->department->name ?? '-' }} • {{ $item->user->position }}</p>
                            <div class="text-[11px] font-semibold text-blue-700 pt-1 flex items-center gap-1">
                                <i class="fa-regular fa-calendar text-[10px]"></i>
                                <span>{{ \Carbon\Carbon::parse($item->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">
                            <i class="fa-solid fa-umbrella-beach text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Tidak ada permohonan cuti aktif pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <p class="text-[11px] text-slate-400 text-center pt-4 border-t border-slate-100 mt-4">
                Jadwal diperbarui secara real-time dari approval HRD.
            </p>
        </div>

    </div>
</div>
@endsection
