@extends('layouts.app')

@section('title', 'Manajemen Aset Kantor')
@section('page-title', 'Inventaris & Aset Perusahaan (Asset Tracker)')
@section('page-subtitle', 'Pencatatan hardware laptop, kendaraan operasional, monitor, dan serah terima ke karyawan.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Metric -->
    <div class="saas-card p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h4 class="text-sm font-bold text-slate-900">Total Aset Terdaftar: {{ $assets->total() }} Unit</h4>
            <p class="text-xs text-slate-500">Monitoring status penggunaan, peminjam, dan kondisi fisik aset</p>
        </div>

        <button type="button" onclick="openAssetModal()"
            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-plus text-[11px]"></i>
            <span>Daftarkan Aset Baru</span>
        </button>
    </div>

    <!-- Asset Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Kode & Nama Aset</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Nomor Serial</th>
                        <th class="py-3 px-4">Kondisi</th>
                        <th class="py-3 px-4">Status Penggunaan</th>
                        <th class="py-3 px-4">Peminjam (Karyawan)</th>
                        <th class="py-3 px-4 text-center">Serah Terima</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assets as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-mono text-[11px] font-bold text-slate-700">{{ $item->asset_code }}</span>
                                <p class="font-bold text-slate-900 mt-1">{{ $item->name }}</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                    {{ $item->category }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $item->serial_number ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->condition === 'good')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Kondisi Baik</span>
                                @elseif($item->condition === 'fair')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Cukup Baik</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ ucfirst($item->condition) }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->status === 'in_use')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Sedang Digunakan</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Tersedia (Ready)</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                @if($item->user)
                                    <p class="font-bold text-slate-900">{{ $item->user->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $item->user->nik }} • {{ $item->user->department->name ?? '-' }}</p>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Di Gudang Inventaris</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($item->status === 'in_use')
                                    <form action="{{ route('admin.assets.assign', $item->id) }}" method="POST" onsubmit="return confirm('Tarik kembali aset {{ $item->name }} dari karyawan?')">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="user_id" value="">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[11px] border border-rose-200 transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-rotate-left"></i>
                                            <span>Kembalikan</span>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" onclick="openAssignModal({{ $item->id }}, '{{ $item->name }}')" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-[11px] border border-blue-200 transition inline-flex items-center gap-1">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <span>Pinjamkan</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-laptop text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada aset terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $assets->links() }}
        </div>
    </div>
</div>

<!-- Modal Register Asset -->
<div id="assetModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Daftarkan Aset Baru Perusahaan</h4>
            <button onclick="closeAssetModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.assets.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="asset_code" class="block text-xs font-bold text-slate-700 mb-1">Kode Aset <span class="text-rose-500">*</span></label>
                    <input type="text" name="asset_code" id="asset_code" required placeholder="AST-LAP-005"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="category" class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category" id="category" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        <option value="laptop">Laptop / Komputer</option>
                        <option value="vehicle">Kendaraan Operasional</option>
                        <option value="monitor">Monitor / Display</option>
                        <option value="device">Smartphone / Tablet</option>
                        <option value="furniture">Furniture / Meja Kursi</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Barang / Model <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" required placeholder="Contoh: MacBook Pro 14 M3 Pro (18GB/512GB)"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="serial_number" class="block text-xs font-bold text-slate-700 mb-1">Nomor Serial (S/N)</label>
                    <input type="text" name="serial_number" id="serial_number" placeholder="C02G..."
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="condition" class="block text-xs font-bold text-slate-700 mb-1">Kondisi Fisik <span class="text-rose-500">*</span></label>
                    <select name="condition" id="condition" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        <option value="good">Kondisi Baik (Good)</option>
                        <option value="fair">Cukup Baik (Fair)</option>
                        <option value="maintenance">Dalam Perbaikan (Maintenance)</option>
                        <option value="damaged">Rusak (Damaged)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1">Serahkan ke Karyawan (Opsional)</label>
                <select name="user_id" id="user_id"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">-- Simpan di Gudang / Belum Dipinjamkan --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeAssetModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Daftarkan Aset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Assign Asset -->
<div id="assignModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <h4 class="text-base font-bold text-slate-900 mb-1">Serah Terima Aset</h4>
        <p class="text-xs text-slate-500 mb-4" id="assignModalDesc">Pilih karyawan pemegang aset:</p>

        <form id="assignForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Karyawan <span class="text-rose-500">*</span></label>
                <select name="user_id" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }} - {{ $emp->department->name ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeAssignModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm">
                    Serah Terima
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAssetModal() {
        document.getElementById('assetModal').classList.remove('hidden');
        document.getElementById('assetModal').classList.add('flex');
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.add('hidden');
        document.getElementById('assetModal').classList.remove('flex');
    }

    function openAssignModal(id, name) {
        const form = document.getElementById('assignForm');
        form.action = `/admin/assets/${id}/assign`;
        document.getElementById('assignModalDesc').innerText = `Pilih staf penerima aset ${name}:`;
        document.getElementById('assignModal').classList.remove('hidden');
        document.getElementById('assignModal').classList.add('flex');
    }

    function closeAssignModal() {
        document.getElementById('assignModal').classList.add('hidden');
        document.getElementById('assignModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
