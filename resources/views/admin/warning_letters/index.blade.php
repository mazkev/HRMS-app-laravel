@extends('layouts.app')

@section('title', 'Surat Peringatan & Disiplin')
@section('page-title', 'Kedisiplinan & Surat Peringatan (Disciplinary SP)')
@section('page-subtitle', 'Penerbitan surat peringatan (SP 1, SP 2, SP 3), catatan masa berlaku, dan dokumen hukum resmi.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="saas-card p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Total Surat Peringatan Diterbitkan: {{ $warningLetters->total() }}</h4>
            <p class="text-xs text-slate-500">Masa berlaku aktif SP adalah 6 bulan sejak tanggal diterbitkan</p>
        </div>

        <button type="button" onclick="openSpModal()"
            class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-500/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
            <span>Terbitkan Surat Peringatan Baru</span>
        </button>
    </div>

    <!-- SP Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">No. Surat & Tingkat</th>
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Jenis Pelanggaran</th>
                        <th class="py-3 px-4">Tanggal Terbit</th>
                        <th class="py-3 px-4">Berlaku Sampai</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Dokumen Cetak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($warningLetters as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold 
                                    @if($item->level === 'SP 3') bg-rose-100 text-rose-800 border border-rose-300
                                    @elseif($item->level === 'SP 2') bg-amber-100 text-amber-800 border border-amber-300
                                    @else bg-blue-100 text-blue-800 border border-blue-300 @endif">
                                    {{ $item->level }}
                                </span>
                                <p class="font-mono text-xs font-bold text-slate-800 mt-1">{{ $item->letter_number }}</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }} • {{ $item->user->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-slate-800">{{ $item->violation_type }}</p>
                                <p class="text-[11px] text-slate-500 line-clamp-1 max-w-xs">{{ $item->description }}</p>
                            </td>

                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($item->issued_date)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ \Carbon\Carbon::parse($item->valid_until)->translatedFormat('d M Y') }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-circle text-[7px] mr-1"></i> Aktif Berlaku
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Kedaluwarsa
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('warning-letters.print', $item->id) }}" target="_blank"
                                    class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-300 transition inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-print text-slate-500"></i>
                                    <span>Cetak SP</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-shield-check text-3xl mb-2 text-emerald-400"></i>
                                <p class="text-xs font-medium">Tidak ada data surat peringatan kedisiplinan yang diterbitkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $warningLetters->links() }}
        </div>
    </div>

</div>

<!-- Modal Issue SP -->
<div id="spModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Terbitkan Surat Peringatan (SP) Resmi</h4>
            <button onclick="closeSpModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.warning-letters.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1">Pilih Karyawan <span class="text-rose-500">*</span></label>
                    <select name="user_id" id="user_id" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="level" class="block text-xs font-bold text-slate-700 mb-1">Tingkat Surat <span class="text-rose-500">*</span></label>
                    <select name="level" id="level" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500">
                        <option value="SP 1">Surat Peringatan Pertama (SP 1)</option>
                        <option value="SP 2">Surat Peringatan Kedua (SP 2)</option>
                        <option value="SP 3">Surat Peringatan Ketiga / Terakhir (SP 3)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="violation_type" class="block text-xs font-bold text-slate-700 mb-1">Kategori Pelanggaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="violation_type" id="violation_type" required placeholder="Contoh: Keterlambatan Berulang / Pelanggaran SOP"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500">
                </div>

                <div>
                    <label for="issued_date" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Terbit <span class="text-rose-500">*</span></label>
                    <input type="date" name="issued_date" id="issued_date" required value="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Uraian Pelanggaran & Peringatan <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan detail tindakan indisipliner dan komitmen perbaikan yang diharapkan..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeSpModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-md shadow-rose-500/20">
                    Terbitkan Dokumen SP
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openSpModal() {
        document.getElementById('spModal').classList.remove('hidden');
        document.getElementById('spModal').classList.add('flex');
    }

    function closeSpModal() {
        document.getElementById('spModal').classList.add('hidden');
        document.getElementById('spModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
