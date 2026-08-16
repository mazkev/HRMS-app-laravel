@extends('layouts.employee_app')

@section('title', 'Klaim Reimbursement')
@section('page-title', 'Klaim Reimburse 🧾')
@section('page-subtitle', 'Ajukan klaim biaya operasional dan unggah bukti kuitansi')

@section('content')
<div class="space-y-4">

    <!-- 1. MOBILE REIMBURSEMENT FORM -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-1.5">
            <i class="fa-solid fa-receipt text-blue-600"></i>
            <span>Formulir Pengajuan Reimburse</span>
        </h4>

        <form action="{{ route('employee.reimbursements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div>
                <label for="category" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Kategori Pengeluaran <span class="text-rose-500">*</span>
                </label>
                <select name="category" id="category" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 font-semibold">
                    <option value="transport">🚗 Transportasi & Bensin</option>
                    <option value="medical">🏥 Kesehatan / Pengobatan</option>
                    <option value="meal">🍱 Konsumsi & Makan Lembur</option>
                    <option value="office_supplies">📎 ATK & Kebutuhan Kantor</option>
                    <option value="other">📦 Lainnya</option>
                </select>
            </div>

            <div>
                <label for="title" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Judul Klaim <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Taksi Meeting Klien SCBD"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label for="amount" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Nominal Biaya (Rp) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="amount" id="amount" required min="1000" placeholder="150000"
                        class="w-full pl-9 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="receipt_image" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Unggah Bukti Kuitansi / Struk (Foto/PDF)
                </label>
                <input type="file" name="receipt_image" id="receipt_image" accept="image/*,.pdf"
                    class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
            </div>

            <div>
                <label for="description" class="block text-[11px] font-bold text-slate-700 mb-1">
                    Keterangan Tambahan
                </label>
                <textarea name="description" id="description" rows="2" placeholder="Catatan keperluan transaksi..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-95 transition">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Ajukan Klaim Reimburse</span>
            </button>
        </form>
    </div>

    <!-- 2. MOBILE REIMBURSEMENT HISTORY CARDS -->
    <div class="saas-card p-5">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Riwayat Pengajuan Reimburse</h4>

        <div class="space-y-2.5">
            @forelse($reimbursements as $item)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 uppercase">
                            {{ ucfirst($item->category) }}
                        </span>

                        @if($item->status === 'approved')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                Disetujui
                            </span>
                        @elseif($item->status === 'rejected')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                Ditolak
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Menunggu Review
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-xs text-slate-900">{{ $item->title }}</p>
                            <p class="text-[10px] text-slate-500">{{ $item->created_at->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <h4 class="text-sm font-black font-mono text-emerald-700">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </h4>
                    </div>

                    @if($item->receipt_image)
                        <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[10px]">
                            <span class="text-slate-500">Bukti Transaksi:</span>
                            <a href="{{ asset('storage/' . $item->receipt_image) }}" target="_blank" class="text-blue-600 font-bold hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-receipt"></i> Lihat Bukti
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-6 text-slate-400 text-xs">
                    <i class="fa-solid fa-receipt text-3xl mb-1 text-slate-300"></i>
                    <p>Belum ada riwayat reimbursement.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
