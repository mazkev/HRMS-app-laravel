@extends('layouts.app')

@section('title', 'Perjalanan Dinas (SPPD) Saya')
@section('page-title', 'Surat Perintah Perjalanan Dinas (SPPD)')
@section('page-subtitle', 'Ajukan tugas perjalanan dinas luar kota/negeri, pantau status SPPD, dan cetak lembar surat tugas resmi.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Pengajuan SPPD (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pengajuan Perjalanan Dinas</h4>
            <p class="text-xs text-slate-500 mb-6">Tentukan kota tujuan, agenda dinas, dan rentang tanggal penugasan</p>

            <form action="{{ route('employee.business-trips.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="destination_city" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kota / Negara Tujuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="destination_city" id="destination_city" required placeholder="Contoh: Surabaya / Singapura / Bali"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Keberangkatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Kepulangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <div>
                    <label for="purpose" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Maksud & Agenda Penugasan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="purpose" id="purpose" rows="3" required placeholder="Jelaskan agenda kerja, klien yang ditemui, dan target hasil perjalanan dinas..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan SPPD ke Manajemen HRD</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan SPPD (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-plane-departure"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Kebijakan SPPD & Uang Harian</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Uang saku harian (*per diem*) sebesar <strong>Rp 350.000 / hari</strong>.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Tiket pesawat/kereta dan hotel dibiayai terpisah atau via Reimbursement.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Lembar SPPD resmi dapat diunduh dan dicetak setelah permohonan disetujui.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat SPPD -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Perjalanan Dinas Saya</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar penugasan dinas luar kota yang telah diajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Nomor SPPD</th>
                        <th class="py-3 px-4">Kota Tujuan</th>
                        <th class="py-3 px-4">Jadwal Tugas</th>
                        <th class="py-3 px-4">Durasi</th>
                        <th class="py-3 px-4">Uang Harian</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Lembar SPPD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trips as $trip)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $trip->sppd_number }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ $trip->destination_city }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($trip->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($trip->end_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $trip->total_days }} Hari
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">
                                Rp {{ number_format($trip->total_allowance, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($trip->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($trip->status === 'rejected')
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
                                @if($trip->status === 'approved')
                                    <a href="{{ route('business-trips.print', $trip->id) }}" target="_blank"
                                        class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1.5">
                                        <i class="fa-solid fa-print"></i>
                                        <span>Cetak SPPD</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">Dalam Proses Verifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                Belum ada riwayat permohonan perjalanan dinas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
