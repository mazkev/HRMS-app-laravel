@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page-title', 'Manajemen Data Karyawan')
@section('page-subtitle', 'Kelola informasi profil, departemen, jabatan, gaji, dan kuota cuti karyawan PT Maju.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search Filters -->
    <div class="saas-card p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Search & Department Filter -->
        <form method="GET" action="{{ route('admin.employees.index') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-1">
            <div class="w-full sm:w-72">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, email, jabatan..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="w-full sm:w-56">
                <select name="department_id" onchange="this.form.submit()"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition shadow-sm">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
            </button>
        </form>

        <!-- Add Button -->
        <a href="{{ route('admin.employees.create') }}" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Karyawan Baru</span>
        </a>
    </div>

    <!-- Employees Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Karyawan</th>
                        <th class="py-3 px-4">Kontak & NIK</th>
                        <th class="py-3 px-4">Departemen & Jabatan</th>
                        <th class="py-3 px-4">Tanggal Masuk</th>
                        <th class="py-3 px-4">Gaji Pokok</th>
                        <th class="py-3 px-4 text-center">Sisa Cuti</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Name & Avatar -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-bold flex items-center justify-center text-xs border border-blue-100 shadow-sm">
                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-xs">{{ $emp->name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $emp->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Phone & NIK -->
                            <td class="py-3.5 px-4">
                                <p class="font-mono font-bold text-blue-700">{{ $emp->nik }}</p>
                                <p class="text-[11px] text-slate-500">{{ $emp->phone ?? '-' }}</p>
                            </td>

                            <!-- Dept & Position -->
                            <td class="py-3.5 px-4">
                                <p class="text-slate-900 font-bold">{{ $emp->department->name ?? 'Belum Ditentukan' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $emp->position }}</p>
                            </td>

                            <!-- Join Date -->
                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $emp->join_date ? \Carbon\Carbon::parse($emp->join_date)->format('d M Y') : '-' }}
                            </td>

                            <!-- Salary -->
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                Rp {{ number_format($emp->salary, 0, ',', '.') }}
                            </td>

                            <!-- Leave Quota -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $emp->leave_quota }} Hari
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.employees.edit', $emp->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition" title="Edit Karyawan">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('admin.employees.destroy', $emp->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan {{ $emp->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition" title="Hapus Karyawan">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-users-slash text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Tidak ada data karyawan yang cocok dengan pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
