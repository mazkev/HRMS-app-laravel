@extends('layouts.employee_app')

@section('title', 'Tukar Shift Kerja')
@section('page-title', 'Tukar Shift Kerja 🔄')
@section('page-subtitle', 'Tukar jadwal jam kerja dengan rekan sesama divisi')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE SHIFT SWAP FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
            <span>Formulir Tukar Shift</span>
        </h4>

        <form action="{{ route('employee.shift-swaps.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label for="target_user_id" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Pilih Rekan Pengganti (Satu Divisi) <span class="text-rose-500">*</span>
                </label>
                <select name="target_user_id" id="target_user_id" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                    @foreach($colleagues as $colleague)
                        <option value="{{ $colleague->id }}">{{ $colleague->name }} ({{ $colleague->nik }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="swap_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Tanggal Pertukaran <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="swap_date" id="swap_date" required min="{{ date('Y-m-d') }}"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="requester_shift_id" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Shift Anda <span class="text-rose-500">*</span>
                    </label>
                    <select name="requester_shift_id" id="requester_shift_id" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        @foreach($shifts as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="target_shift_id" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Shift Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <select name="target_shift_id" id="target_shift_id" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-[11px] text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        @foreach($shifts as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Alasan Tukar Shift <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="2" required placeholder="Contoh: Ada keperluan keluarga mendadak..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Ajukan Tukar Shift</span>
            </button>
        </form>
    </div>

    <!-- 2. MOBILE SHIFT SWAP HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Pertukaran Shift</h4>

        <div class="space-y-2.5">
            @forelse($mySwaps as $swap)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-blue-700">
                            {{ \Carbon\Carbon::parse($swap->swap_date)->translatedFormat('l, d M Y') }}
                        </span>

                        @if($swap->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="text-xs text-slate-700">
                        @if($swap->requester_id === $user->id)
                            <p>Tukar ke: <strong>{{ $swap->targetUser->name }}</strong></p>
                        @else
                            <p>Diminta oleh: <strong>{{ $swap->requester->name }}</strong></p>
                        @endif
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $swap->requesterShift->name }} &rarr; {{ $swap->targetShift->name }}</p>
                    </div>

                    <p class="text-[11px] text-slate-600 italic">"{{ $swap->reason }}"</p>
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-clock-rotate-left text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat tukar shift.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
