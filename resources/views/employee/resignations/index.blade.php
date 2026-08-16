@extends('layouts.app')

@section('title', 'Resignasi & Paklaring')
@section('page-title', 'Pengunduran Diri (Resignasi) & Surat Pengalaman Kerja')
@section('page-subtitle', 'Ajukan surat pengunduran diri 1-month notice dan unduh Surat Paklaring resmi setelah masa kerja berakhir.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Pengajuan Resign (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pengajuan 1-Month Notice</h4>
            <p class="text-xs text-slate-500 mb-6">Sesuai Peraturan Perusahaan, pengajuan pengunduran diri diajukan minimal 30 hari sebelum hari kerja terakhir</p>

            <form action="{{ route('employee.resignations.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="resign_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tanggal Efektif Hari Kerja Terakhir (Last Working Day) <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="resign_date" id="resign_date" required min="{{ date('Y-m-d', strtotime('+30 days')) }}"
                        value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-semibold focus:bg-white focus:outline-none focus:border-blue-600">
                    <p class="text-[11px] text-slate-400 mt-1">Minimal 30 hari dari tanggal hari ini.</p>
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan Pengunduran Diri <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required placeholder="Tuliskan alasan pengunduran diri dan ucapan serah terima..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Resignasi ke Manajemen HRD</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan Offboarding (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Prosedur Exit Clearance</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Menyelesaikan seluruh *handover* pekerjaan ke rekan tim.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Mengembalikan laptop, ID card, dan aset kantor ke IT & GA.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Surat Pengalaman Kerja (Paklaring) otomatis dapat diunduh setelah disetujui HRD.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat Pengajuan Resign -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Status Pengajuan Resignasi & Surat Paklaring</h4>
        <p class="text-xs text-slate-500 mb-4">Dokumen paklaring resmi akan diterbitkan oleh departemen HRD</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tgl Pengajuan</th>
                        <th class="py-3 px-4">Efektif Resign</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Nomor Paklaring</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Dokumen Paklaring</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($resignations as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($item->notice_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ \Carbon\Carbon::parse($item->resign_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $item->reason }}">
                                {{ $item->reason }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $item->paklaring_number ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($item->status === 'rejected')
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
                                @if($item->status === 'approved')
                                    <a href="{{ route('resignations.paklaring', $item->id) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-print text-slate-300"></i>
                                        <span>Unduh / Cetak Paklaring</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">Dalam Proses Verifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">
                                Belum ada riwayat permohonan pengunduran diri.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
