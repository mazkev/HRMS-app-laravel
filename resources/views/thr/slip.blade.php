<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip THR - {{ $thr->user->name }} - {{ $thr->holiday_name }} {{ $thr->year }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        @media print {
            body {
                background-color: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex flex-col items-center justify-center min-h-screen">

    <!-- Action Bar (Hidden on Print) -->
    <div class="no-print w-full max-w-3xl flex items-center justify-between mb-4">
        <a href="javascript:history.back()" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Portal</span>
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md flex items-center gap-2 transition active:scale-95">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF Slip THR</span>
        </button>
    </div>

    <!-- Official Slip THR Container -->
    <div class="print-container w-full max-w-3xl bg-white border border-slate-200 rounded-2xl p-8 shadow-xl text-slate-800">
        
        <!-- Company Header / Letterhead -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-5 mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-2xl font-black shadow">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">PT MAJU NUSANTARA</h1>
                    <p class="text-xs text-slate-500 font-medium">Gedung Wisma Nusantara Lt. 18, Jl. M.H. Thamrin No. 59, Jakarta Pusat</p>
                    <p class="text-[11px] text-slate-400">NPWP: 01.234.567.8-012.000 • Telp: (021) 500-1234</p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wider block mb-1">
                    Slip Pembayaran THR
                </span>
                <p class="text-xs font-bold text-slate-700">{{ $thr->holiday_name }} {{ $thr->year }}</p>
                <p class="text-[10px] text-slate-400 font-mono">ID: THR-{{ $thr->year }}-{{ $thr->user->nik }}</p>
            </div>
        </div>

        <!-- Employee Info Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs mb-6">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Karyawan</span>
                <span class="font-bold text-slate-900 text-sm">{{ $thr->user->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Induk (NIK)</span>
                <span class="font-mono font-bold text-slate-800">{{ $thr->user->nik }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Departemen / Posisi</span>
                <span class="font-semibold text-slate-800">{{ $thr->user->department->name ?? 'General' }} • {{ $thr->user->position ?? 'Staff' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Masuk (Join Date)</span>
                <span class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($thr->user->join_date)->translatedFormat('d F Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Masa Kerja Efektif</span>
                <span class="font-bold text-emerald-700">{{ $thr->tenure_months }} Bulan</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Pencairan</span>
                <span class="font-medium text-slate-700">{{ $thr->payment_date ? \Carbon\Carbon::parse($thr->payment_date)->translatedFormat('d F Y') : '-' }}</span>
            </div>
        </div>

        <!-- Calculation Formula Breakdown Table -->
        <div class="border border-slate-200 rounded-xl overflow-hidden mb-6 text-xs">
            <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200 flex items-center justify-between">
                <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Rincian Perhitungan Tunjangan Hari Raya (THR Kemnaker)</span>
                <i class="fa-solid fa-calculator text-emerald-600 text-sm"></i>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-center text-slate-700">
                    <span>Gaji Pokok Acuan (1 Bulan)</span>
                    <span class="font-mono font-semibold">Rp {{ number_format($thr->basic_salary, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-slate-700">
                    <span>Formula Perhitungan Sesuai Masa Kerja</span>
                    <span class="font-semibold text-slate-800">
                        @if($thr->tenure_months >= 12)
                            1 x Gaji Penuh (Masa Kerja &ge; 12 Bulan)
                        @else
                            ({{ $thr->tenure_months }} / 12) x Gaji Pokok (Pro-Rata)
                        @endif
                    </span>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-between items-center font-bold text-slate-900">
                    <span>Keterangan Status</span>
                    <span class="text-emerald-700">{{ $thr->notes }}</span>
                </div>
            </div>
        </div>

        <!-- Net Amount Highlight Box -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-900 to-slate-900 text-white flex items-center justify-between mb-8 shadow-md">
            <div>
                <span class="text-[11px] font-bold text-emerald-200 uppercase tracking-wider block">Total THR Diterima (Net)</span>
                <p class="text-xs text-slate-300">Ditransfer ke Rekening Karyawan Tanpa Potongan</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl sm:text-3xl font-black text-white font-mono">
                    Rp {{ number_format($thr->thr_amount, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <!-- Signatures & Authority -->
        <div class="grid grid-cols-2 gap-8 pt-4 text-xs text-center border-t border-slate-200">
            <div>
                <p class="text-slate-500 mb-16">Penerima (Karyawan),</p>
                <p class="font-bold text-slate-900 underline">{{ $thr->user->name }}</p>
                <p class="text-[11px] font-mono text-slate-400">{{ $thr->user->nik }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Finance & HR Department,</p>
                <p class="font-bold text-slate-900 underline">Admin HRD PT Maju</p>
                <p class="text-[11px] font-mono text-slate-400">Head of Human Capital</p>
            </div>
        </div>

        <!-- Footer Disclaimers -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 text-center">
            * Dokumen ini adalah tanda terima resmi pembayaran Tunjangan Hari Raya (THR) yang sah dari PT Maju Nusantara.
        </div>

    </div>

</body>
</html>
