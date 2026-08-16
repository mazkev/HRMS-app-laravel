@extends('layouts.app')

@section('title', 'Notifikasi & Gateway Dispatcher')
@section('page-title', 'Pusat Notifikasi WhatsApp & Email Gateway')
@section('page-subtitle', 'Kirim dan simulasikan notifikasi otomatis untuk penerbitan slip gaji, persetujuan cuti, dan pengingat presensi.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Dispatch Notification (5 Cols) -->
        <div class="lg:col-span-5 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Kirim Notifikasi / Broadcast</h4>
            <p class="text-xs text-slate-500 mb-6">Pilih saluran WhatsApp atau Email dan tentukan penerima</p>

            <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Karyawan Penerima <span class="text-rose-500">*</span></label>
                    <select name="user_id" id="user_id" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} (WA: {{ $emp->phone ?? '-' }} | {{ $emp->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="channel" class="block text-xs font-bold text-slate-700 mb-1.5">Saluran Gateway <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-emerald-50/50">
                            <input type="radio" name="channel" value="whatsapp" checked class="text-emerald-600 focus:ring-emerald-500">
                            <span class="text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-base"></i> WhatsApp
                            </span>
                        </label>
                        <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-blue-50/50">
                            <input type="radio" name="channel" value="email" class="text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-blue-700 flex items-center gap-1.5">
                                <i class="fa-regular fa-envelope text-base"></i> Email
                            </span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-xs font-bold text-slate-700 mb-1.5">Judul / Subjek Notifikasi <span class="text-rose-500">*</span></label>
                    <input type="text" name="subject" id="subject" required value="Slip Gaji Bulan Ini Telah Diterbitkan 📄"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="message" class="block text-xs font-bold text-slate-700 mb-1.5">Isi Pesan Notifikasi <span class="text-rose-500">*</span></label>
                    <textarea name="message" id="message" rows="4" required
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">Halo Rekan PT Maju, slip gaji periode bulan berjalan Anda telah resmi diterbitkan oleh HRD. Anda dapat langsung mengunduh slip PDF di portal HRMS.</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Notifikasi Sekarang</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Logs Table (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pengiriman Notifikasi (Gateway Logs)</h4>
            <p class="text-xs text-slate-500 mb-4">Log pengiriman WhatsApp dan Email yang telah dieksekusi</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Saluran</th>
                            <th class="py-3 px-3">Penerima</th>
                            <th class="py-3 px-3">Subjek & Pesan</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-3 font-mono text-slate-500 text-[11px]">
                                    {{ $log->created_at->format('d M, H:i') }}
                                </td>

                                <td class="py-3 px-3">
                                    @if($log->channel === 'whatsapp')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                            <i class="fa-brands fa-whatsapp"></i> WA
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                                            <i class="fa-regular fa-envelope"></i> Email
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 px-3">
                                    <p class="font-bold text-slate-900">{{ $log->user->name ?? 'User' }}</p>
                                    <p class="text-[10px] font-mono text-slate-400">{{ $log->recipient }}</p>
                                </td>

                                <td class="py-3 px-3">
                                    <p class="font-semibold text-slate-800">{{ $log->subject }}</p>
                                    <p class="text-[10px] text-slate-500 line-clamp-1 max-w-xs">{{ $log->message }}</p>
                                </td>

                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                        Terkirim
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">
                                    Belum ada catatan pengiriman notifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100">
                {{ $logs->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
