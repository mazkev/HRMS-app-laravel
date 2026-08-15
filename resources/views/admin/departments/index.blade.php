@extends('layouts.app')

@section('title', 'Departemen & Divisi')
@section('page-title', 'Manajemen Departemen & Divisi')
@section('page-subtitle', 'Kelola struktur unit kerja dan penempatan divisi karyawan PT Maju.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Daftar Seluruh Departemen</h4>
            <p class="text-xs text-slate-500">Total terdaftar: {{ $departments->count() }} unit kerja</p>
        </div>
        <button type="button" onclick="openCreateDeptModal()" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Departemen Baru</span>
        </button>
    </div>

    <!-- Departments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($departments as $dept)
            <div class="saas-card p-6 flex flex-col justify-between group hover:border-blue-300 transition">
                <div>
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $dept->users_count }} Karyawan
                        </span>
                    </div>

                    <h4 class="text-sm font-bold text-slate-900 mb-1.5">{{ $dept->name }}</h4>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                        {{ $dept->description ?: 'Tidak ada deskripsi rincian untuk divisi ini.' }}
                    </p>
                </div>

                <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
                    <span class="text-[11px] text-slate-400 font-medium">Dibuat: {{ $dept->created_at->format('d M Y') }}</span>

                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="openEditDeptModal({{ $dept->id }}, '{{ addslashes($dept->name) }}', '{{ addslashes($dept->description ?? '') }}')"
                            class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition" title="Edit Departemen">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus departemen {{ $dept->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition" title="Hapus Departemen">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 saas-card p-10 text-center text-slate-400">
                <i class="fa-solid fa-building-circle-xmark text-4xl mb-3 text-slate-300"></i>
                <p class="text-xs font-medium">Belum ada departemen yang terdaftar. Silakan tambahkan departemen baru.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Create Department -->
<div id="createDeptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Tambah Departemen Baru</h4>
        <p class="text-xs text-slate-500 mb-4">Masukkan nama divisi atau unit kerja baru</p>

        <form action="{{ route('admin.departments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="create_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Departemen <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="create_name" required placeholder="Contoh: Quality Assurance"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="create_desc" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi / Tanggung Jawab</label>
                <textarea name="description" id="create_desc" rows="3" placeholder="Rincian lingkup kerja divisi..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeCreateDeptModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm">
                    Simpan Departemen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Department -->
<div id="editDeptModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Edit Data Departemen</h4>
        <p class="text-xs text-slate-500 mb-4">Perbarui informasi divisi</p>

        <form id="editDeptForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Departemen <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="edit_desc" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" id="edit_desc" rows="3"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditDeptModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateDeptModal() {
        document.getElementById('createDeptModal').classList.remove('hidden');
        document.getElementById('createDeptModal').classList.add('flex');
    }

    function closeCreateDeptModal() {
        document.getElementById('createDeptModal').classList.add('hidden');
        document.getElementById('createDeptModal').classList.remove('flex');
    }

    function openEditDeptModal(id, name, desc) {
        document.getElementById('editDeptForm').action = `/admin/departments/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_desc').value = desc;
        document.getElementById('editDeptModal').classList.remove('hidden');
        document.getElementById('editDeptModal').classList.add('flex');
    }

    function closeEditDeptModal() {
        document.getElementById('editDeptModal').classList.add('hidden');
        document.getElementById('editDeptModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
