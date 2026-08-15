@extends('layouts.app')

@section('title', 'Klaim Reimbursement')
@section('page-title', 'Klaim Biaya Operasional (Reimbursement)')
@section('page-subtitle', 'Ajukan klaim penggantian biaya operasional, transportasi dinas, atau medis dengan melampirkan foto kuitansi.')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Form Klaim Baru (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6">
            <h4 class="text-base font-bold text-slate-900 mb-0.5">Formulir Pengajuan Klaim Baru</h4>
            <p class="text-xs text-slate-500 mb-6">Lengkapi rincian pengeluaran dan lampirkan foto struk/nota</p>

            <form action="{{ route('employee.reimbursements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kategori Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" id="category" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            <option value="transport">Transportasi / Bensin Dinas</option>
                            <option value="medical">Kesehatan & Rawat Jalan (Medis)</option>
                            <option value="meal">Konsumsi / Makan Dinas</option>
                            <option value="office_supplies">Perlengkapan Kerja / ATK</option>
                            <option value="other">Lain-lain / Operasional</option>
                        </select>
                    </div>

                    <div>
                        <label for="amount" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nominal Pengeluaran (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" required min="1000" placeholder="Contoh: 150000"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </div>

                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Judul / Perihal Klaim <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required placeholder="Contoh: Tiket Kereta & Taksi Kunjungan Klien Bandung"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label for="receipt" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Foto Bukti Kuitansi / Struk (JPG, PNG max 3MB)
                    </label>
                    <input type="file" name="receipt" id="receipt" accept="image/*"
                        class="w-full p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Uraian & Keterangan Pengeluaran <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="3" required placeholder="Jelaskan kebutuhan pengeluaran secara rinci..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                        <span>Kirim Permohonan Klaim Reimbursement</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ketentuan Klaim (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="saas-card p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Ketentuan Reimbursement</h4>
                        <p class="text-[11px] text-slate-500">PT Maju Nusantara</p>
                    </div>
                </div>

                <ul class="space-y-2.5 text-xs text-slate-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Pastikan foto bukti kuitansi jelas dan nominal terbaca.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Klaim wajib diajukan maksimal 14 hari kerja setelah tanggal transaksi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                        <span>Pencairan akan digabungkan secara otomatis ke rekening transfer gaji bulanan.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Riwayat Klaim Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Riwayat Pengajuan Klaim Saya</h4>
        <p class="text-xs text-slate-500 mb-4">Daftar permohonan penggantian biaya yang telah Anda ajukan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                        <th class="py-3 px-4">Kategori & Judul</th>
                        <th class="py-3 px-4">Nominal</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Catatan HR Finance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reimbursements as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 font-medium">
                                {{ $item->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 uppercase">
                                    {{ $item->category }}
                                </span>
                                <p class="font-bold text-slate-900 mt-1">{{ $item->title }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 max-w-xs truncate">
                                {{ $item->description }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->status === 'approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check mr-1"></i> Disetujui
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-xmark mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-regular fa-clock mr-1"></i> Menunggu Review
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $item->admin_notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">
                                Belum ada permohonan klaim reimbursement yang diajukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $reimbursements->links() }}
        </div>
    </div>

</div>
@endsection
