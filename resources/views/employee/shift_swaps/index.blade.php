@extends('layouts.employee_app')

@section('title', 'Tukar Shift Kerja')
@section('page-title', 'Pengajuan Pertukaran Shift Kerja')
@section('page-subtitle', 'Tukar jadwal shift dengan rekan kerja satu divisi jika berhalangan hadir.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Pengajuan Tukar Shift (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pertukaran Shift</h4>
            <p class="text-xs text-slate-500 mb-6">Pilih rekan pengganti dan jadwal shift yang akan ditukar</p>

            <form action="{{ route('employee.shift-swaps.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="target_user_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Pilih Rekan Pengganti (Satu Divisi) <span class="text-rose-500">*</span>
                        </label>
                        <select name="target_user_id" id="target_user_id" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            @foreach($colleagues as $colleague)
                                <option value="{{ $colleague->id }}">{{ $colleague->name }} ({{ $colleague->nik }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="swap_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Pertukaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="swap_date" id="swap_date" required min="{{ date('Y-m-d') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="requester_shift_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Shift Anda Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <select name="requester_shift_id" id="requester_shift_id" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="target_shift_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Shift yang Diinginkan <span class="text-rose-500">*</span>
                        </label>
                        <select name="target_shift_id" id="target_shift_id" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            @foreach($shifts as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ substr($s->start_time, 0, 5) }} - {{ substr($s->end_time, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="reason" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Alasan Pertukaran Shift <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="3" required placeholder="Jelaskan kebutuhan pertukaran shift kerja..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Tukar Shift</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan Tukar Shift (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Ketentuan Tukar Shift</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Pastikan telah berkoordinasi terlebih dahulu dengan rekan pengganti.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Pertukaran shift hanya berlaku antar staf dalam divisi yang sama.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Persetujuan akhir dilakukan oleh Supervisor/HRD.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat Tukar Shift -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pertukaran Shift Saya</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar permohonan tukar shift yang diajukan atau diterima</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tgl Pertukaran</th>
                        <th class="py-3 px-4">Rekan Terkait</th>
                        <th class="py-3 px-4">Shift Pemohon</th>
                        <th class="py-3 px-4">Shift Pengganti</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mySwaps as $swap)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-blue-700">
                                {{ \Carbon\Carbon::parse($swap->swap_date)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($swap->requester_id === $user->id)
                                    <span>Menggantikan ke: <strong>{{ $swap->targetUser->name }}</strong></span>
                                @else
                                    <span>Diminta oleh: <strong>{{ $swap->requester->name }}</strong></span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                {{ $swap->requesterShift->name }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-700">
                                {{ $swap->targetShift->name }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 max-w-xs truncate" title="{{ $swap->reason }}">
                                {{ $swap->reason }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($swap->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">
                                Belum ada riwayat permohonan tukar shift.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
