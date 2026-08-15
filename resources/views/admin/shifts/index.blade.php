@extends('layouts.app')

@section('title', 'Manajemen Shift Kerja')
@section('page-title', 'Manajemen Shift Kerja (Work Shifts)')
@section('page-subtitle', 'Kelola jadwal jam kerja, jam mulai/selesai, serta batas toleransi keterlambatan.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Daftar Shift Kerja Perusahaan</h4>
            <p class="text-xs text-slate-500">Konfigurasi jadwal kerja yang dapat dihubungkan ke data karyawan</p>
        </div>

        <button type="button" onclick="openShiftModal()"
            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Tambah Shift Baru</span>
        </button>
    </div>

    <!-- Shifts Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($shifts as $shift)
            <div class="saas-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $shift->users_count }} Karyawan
                        </span>
                    </div>

                    <h4 class="text-base font-bold text-slate-900 mb-1">{{ $shift->name }}</h4>
                    <p class="text-xs text-slate-500 mb-4">{{ $shift->description ?? 'Jadwal operasional kerja.' }}</p>

                    <div class="space-y-2 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jam Operasional:</span>
                            <span class="font-mono font-bold text-slate-800">{{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Batas Tepat Waktu:</span>
                            <span class="font-mono font-bold text-amber-700">Maks. {{ substr($shift->late_threshold_time, 0, 5) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 mt-4">
                    <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" onsubmit="return confirm('Hapus shift {{ $shift->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-slate-400 hover:text-rose-600 text-xs font-semibold hover:bg-rose-50 transition">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 saas-card p-10 text-center text-slate-400">
                <i class="fa-regular fa-clock text-3xl mb-2 text-slate-300"></i>
                <p class="text-xs font-medium">Belum ada shift kerja terdaftar. Klik "Tambah Shift Baru".</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Create Shift -->
<div id="shiftModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Tambah Shift Kerja Baru</h4>
            <button onclick="closeShiftModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.shifts.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Shift <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" required placeholder="Contoh: Shift 1 (Pagi) / Regular Office"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_time" class="block text-xs font-bold text-slate-700 mb-1">Jam Mulai <span class="text-rose-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" required value="08:00"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="end_time" class="block text-xs font-bold text-slate-700 mb-1">Jam Pulang <span class="text-rose-500">*</span></label>
                    <input type="time" name="end_time" id="end_time" required value="17:00"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="late_threshold_time" class="block text-xs font-bold text-slate-700 mb-1">Batas Waktu Terlambat <span class="text-rose-500">*</span></label>
                <input type="time" name="late_threshold_time" id="late_threshold_time" required value="08:30"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Keterangan Shift</label>
                <textarea name="description" id="description" rows="2" placeholder="Catatan peruntukan shift..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeShiftModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Simpan Shift
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openShiftModal() {
        document.getElementById('shiftModal').classList.remove('hidden');
        document.getElementById('shiftModal').classList.add('flex');
    }

    function closeShiftModal() {
        document.getElementById('shiftModal').classList.add('hidden');
        document.getElementById('shiftModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
