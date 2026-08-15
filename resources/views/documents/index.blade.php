@extends('layouts.app')

@section('title', 'Berkas Dokumen Digital')
@section('page-title', 'Brankas Dokumen Karyawan (Digital Vault)')
@section('page-subtitle', 'Penyimpanan aman salinan dokumen identitas (KTP, NPWP), kontrak kerja (PKWT), dan sertifikat.')

@section('content')
<div class="space-y-6">

    <!-- Upload Document Form Card -->
    <div class="saas-card p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-1">Unggah Dokumen Baru</h4>
        <p class="text-xs text-slate-500 mb-4">Simpan file digital resmi ke dalam database arsip (PDF, JPG, PNG, DOCX max 5MB)</p>

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @if(Auth::user()->role === 'admin_hr')
                    <div>
                        <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1">Pemilik Dokumen (Karyawan) <span class="text-rose-500">*</span></label>
                        <select name="user_id" id="user_id" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="document_type" class="block text-xs font-bold text-slate-700 mb-1">Jenis Dokumen <span class="text-rose-500">*</span></label>
                    <select name="document_type" id="document_type" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        <option value="KTP">KTP (Kartu Tanda Penduduk)</option>
                        <option value="NPWP">NPWP (Nomor Pokok Wajib Pajak)</option>
                        <option value="Contract_PKWT">Kontrak Kerja (PKWT/PKWTT)</option>
                        <option value="BPJS">Kartu BPJS Ketenagakerjaan / Kesehatan</option>
                        <option value="Certificate">Sertifikat Pelatihan / Ijazah</option>
                        <option value="Other">Dokumen Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Nama / Judul Berkas <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title" required placeholder="Contoh: Kontrak Kerja 2026-2027"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="file" class="block text-xs font-bold text-slate-700 mb-1">Pilih File Berkas <span class="text-rose-500">*</span></label>
                <input type="file" name="file" id="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-[11px]"></i>
                    <span>Unggah ke Brankas Dokumen</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Documents List Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Arsip Dokumen Tersimpan</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar berkas resmi yang tersimpan di server terenkripsi</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        @if(Auth::user()->role === 'admin_hr')
                            <th class="py-3 px-4">Karyawan</th>
                        @endif
                        <th class="py-3 px-4">Jenis Dokumen</th>
                        <th class="py-3 px-4">Nama Berkas</th>
                        <th class="py-3 px-4">Tanggal Diunggah</th>
                        <th class="py-3 px-4 text-center">Unduh / Akses</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-slate-50/80 transition">
                            @if(Auth::user()->role === 'admin_hr')
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    {{ $doc->user->name }} ({{ $doc->user->nik }})
                                </td>
                            @endif

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $doc->document_type }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <i class="fa-solid fa-file-lines text-slate-400 mr-1.5"></i>
                                {{ $doc->title }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 font-mono">
                                {{ $doc->created_at->format('d M Y, H:i') }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                    class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-300 inline-flex items-center gap-1.5 transition">
                                    <i class="fa-solid fa-arrow-down text-[11px] text-blue-600"></i>
                                    <span>Buka Dokumen</span>
                                </a>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen {{ $doc->title }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Hapus Dokumen">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada berkas dokumen yang tersimpan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
    </div>

</div>
@endsection
