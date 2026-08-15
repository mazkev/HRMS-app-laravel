@extends('layouts.app')

@section('title', 'Pelatihan & Pengembangan')
@section('page-title', 'Pelatihan & Pengembangan Karyawan (LMS Lite)')
@section('page-subtitle', 'Kelola jadwal workshop, sertifikasi keahlian, dan kuota pendaftaran pelatihan internal.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Daftar Program Pelatihan Aktif</h4>
            <p class="text-xs text-slate-500">Program upskilling teknis dan pengembangan manajerial</p>
        </div>

        <button type="button" onclick="openTrainingModal()"
            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Buka Program Pelatihan Baru</span>
        </button>
    </div>

    <!-- Trainings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($trainings as $item)
            <div class="saas-card p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                            {{ $item->category }}
                        </span>
                        <span class="text-xs font-bold text-slate-600">
                            <i class="fa-solid fa-users mr-1 text-blue-600"></i> {{ $item->participants_count }}/{{ $item->capacity }} Peserta
                        </span>
                    </div>

                    <h4 class="text-base font-bold text-slate-900 mb-1">{{ $item->title }}</h4>
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $item->description }}</p>

                    <div class="space-y-2 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Instruktur:</span>
                            <span class="font-bold text-slate-800">{{ $item->trainer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tanggal:</span>
                            <span class="font-semibold text-blue-700">{{ \Carbon\Carbon::parse($item->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Lokasi:</span>
                            <span class="font-medium text-slate-700 truncate max-w-[140px]">{{ $item->location }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4 flex items-center justify-between">
                    <span class="text-[11px] font-bold {{ $item->status === 'upcoming' ? 'text-blue-600' : 'text-emerald-600' }}">
                        <i class="fa-solid fa-circle text-[8px] mr-1"></i> {{ ucfirst($item->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-3 saas-card p-12 text-center text-slate-400">
                <i class="fa-solid fa-graduation-cap text-4xl mb-3 text-slate-300"></i>
                <h4 class="text-sm font-bold text-slate-700">Belum Ada Program Pelatihan</h4>
                <p class="text-xs mt-1">Buat program workshop atau pelatihan baru untuk karyawan.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Create Training -->
<div id="trainingModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Buka Program Pelatihan Baru</h4>
            <button onclick="closeTrainingModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.trainings.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Nama Program Pelatihan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Modern Cloud Architecture & Microservices"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="trainer_name" class="block text-xs font-bold text-slate-700 mb-1">Nama Trainer / Instruktur <span class="text-rose-500">*</span></label>
                    <input type="text" name="trainer_name" id="trainer_name" required placeholder="Dr. Irwan Santoso"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="category" class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" id="category" required value="Technical & IT"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input type="date" name="start_date" id="start_date" required value="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                    <input type="date" name="end_date" id="end_date" required value="{{ date('Y-m-d') }}"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="location" class="block text-xs font-bold text-slate-700 mb-1">Lokasi / Media <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" id="location" required value="Ruang Training Lt. 3 / Zoom"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="capacity" class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Peserta <span class="text-rose-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" required value="20" min="1"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Silabus & Deskripsi Pelatihan <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="3" required placeholder="Tuliskan materi dan target kompetensi yang dicapai..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeTrainingModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Publikasikan Pelatihan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTrainingModal() {
        document.getElementById('trainingModal').classList.remove('hidden');
        document.getElementById('trainingModal').classList.add('flex');
    }

    function closeTrainingModal() {
        document.getElementById('trainingModal').classList.add('hidden');
        document.getElementById('trainingModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
