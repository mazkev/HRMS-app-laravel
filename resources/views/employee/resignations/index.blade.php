@extends('layouts.employee_app')

@section('title', 'Resignasi & Paklaring')
@section('page-title', 'Resignasi & Paklaring 🚪')
@section('page-subtitle', 'Pengajuan pengunduran diri (1-Month Notice) dan cetak Surat Paklaring')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE RESIGNATION FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-user-xmark text-rose-600"></i>
            <span>Formulir Pengajuan Pengunduran Diri</span>
        </h4>

        <form action="{{ route('employee.resignations.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label for="notice_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Tgl Pengajuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="notice_date" id="notice_date" required value="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-600 font-semibold">
                </div>

                <div>
                    <label for="resign_date" class="block text-[11px] font-bold text-slate-700 mb-1">
                        Hari Terakhir Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="resign_date" id="resign_date" required min="{{ date('Y-m-d') }}" value="{{ \Carbon\Carbon::now()->addDays(30)->toDateString() }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-600 font-semibold">
                </div>
            </div>

            <div class="p-3 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                <span>Kebijakan perusahaan mewajibkan pemberitahuan minimal <strong>30 hari kerja (*1-Month Notice*)</strong>.</span>
            </div>

            <div>
                <label for="reason" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Alasan Pengunduran Diri <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan alasan pengunduran diri dan rencana serah terima tugas..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-rose-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 text-white font-extrabold text-xs shadow-md shadow-rose-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Kirim Permohonan Resignasi</span>
            </button>
        </form>
    </div>

    <!-- 2. MOBILE RESIGNATION HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Status Pengajuan Resignasi</h4>

        <div class="space-y-3">
            @forelse($resignations as $item)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                        <span class="font-bold text-xs text-slate-900">
                            Terakhir: {{ \Carbon\Carbon::parse($item->resign_date)->translatedFormat('d F Y') }}
                        </span>

                        @if($item->status === 'approved')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($item->status === 'rejected')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-slate-700 italic">"{{ $item->reason }}"</p>

                    @if($item->paklaring_number)
                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] text-emerald-700 font-bold block uppercase">No. Surat Paklaring:</span>
                                <span class="font-mono font-bold text-slate-900">{{ $item->paklaring_number }}</span>
                            </div>
                            <a href="{{ route('resignations.paklaring', $item->id) }}" target="_blank"
                                class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-print"></i>
                                <span>Paklaring</span>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-user-check text-3xl mb-1 text-emerald-400"></i>
                    <p>Status Karyawan Aktif.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
