@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Data Karyawan')
@section('page-subtitle', 'Perbarui informasi data diri, jabatan, gaji, atau kuota cuti karyawan.')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.employees.index') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold flex items-center gap-1.5 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Karyawan</span>
        </a>
    </div>

    <div class="saas-card p-8">
        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Pribadi & Akun -->
            <div>
                <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-lock"></i>
                    <span>Informasi Akun & Akses ({{ $employee->name }})</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor Induk Karyawan (NIK) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nik" id="nik" required value="{{ old('nik', $employee->nik) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 uppercase focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap Pegawai <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $employee->name) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Email Resmi <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required value="{{ old('email', $employee->email) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi Baru (Kosongkan jika tidak diubah)
                        </label>
                        <input type="password" name="password" id="password" placeholder="Minimal 6 karakter"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor WhatsApp / Telepon
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Informasi Pekerjaan & Penggajian</span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="department_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Departemen Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <select name="department_id" id="department_id" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            <option value="">Pilih Departemen...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="position" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Jabatan / Posisi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="position" id="position" required value="{{ old('position', $employee->position) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="join_date" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Tanggal Bergabung (Join Date) <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="join_date" id="join_date" required value="{{ old('join_date', $employee->join_date ? $employee->join_date->format('Y-m-d') : '') }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="salary" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Gaji Pokok (IDR) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="1000" name="salary" id="salary" required value="{{ old('salary', $employee->salary) }}"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="leave_quota" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Sisa Kuota Cuti Tahunan (Hari) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="leave_quota" id="leave_quota" required value="{{ old('leave_quota', $employee->leave_quota) }}" min="0" max="60"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
