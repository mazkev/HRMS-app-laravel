<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->user->name }} - {{ $payroll->period_month }}</title>
    
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
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20 flex items-center gap-2 transition active:scale-95">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF Slip Gaji</span>
        </button>
    </div>

    <!-- Official Slip Gaji Container -->
    <div class="print-container w-full max-w-3xl bg-white border border-slate-200 rounded-2xl p-8 shadow-xl text-slate-800">
        
        <!-- Company Header / Letterhead -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-5 mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center text-2xl font-black shadow">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">PT MAJU NUSANTARA</h1>
                    <p class="text-xs text-slate-500 font-medium">Gedung Wisma Nusantara Lt. 18, Jl. M.H. Thamrin No. 59, Jakarta Pusat</p>
                    <p class="text-[11px] text-slate-400">NPWP: 01.234.567.8-012.000 • Telp: (021) 500-1234</p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase tracking-wider block mb-1">
                    Slip Gaji Resmi
                </span>
                <p class="text-xs font-bold text-slate-700">Periode: {{ \Carbon\Carbon::createFromFormat('Y-m', $payroll->period_month)->translatedFormat('F Y') }}</p>
                <p class="text-[10px] text-slate-400 font-mono">ID: SLP-{{ str_replace('-', '', $payroll->period_month) }}-{{ $payroll->user->nik }}</p>
            </div>
        </div>

        <!-- Employee Info Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs mb-6">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nama Karyawan</span>
                <span class="font-bold text-slate-900 text-sm">{{ $payroll->user->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Induk (NIK)</span>
                <span class="font-mono font-bold text-slate-800">{{ $payroll->user->nik }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Departemen / Posisi</span>
                <span class="font-semibold text-slate-800">{{ $payroll->user->department->name ?? 'General' }} • {{ $payroll->user->position ?? 'Staff' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Status PTKP Pajak</span>
                <span class="font-mono font-bold text-blue-700">{{ $payroll->user->ptkp_status ?? 'TK/0' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Hari Kehadiran</span>
                <span class="font-semibold text-slate-800">{{ $payroll->total_present_days }} Hadir ({{ $payroll->total_late_days }} Terlambat)</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Pembayaran</span>
                <span class="font-medium text-slate-700">{{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->translatedFormat('d F Y') : '-' }}</span>
            </div>
        </div>

        <!-- Earnings & Deductions Breakdown Tables -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 text-xs">
            
            <!-- Earnings (Penghasilan) -->
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200 flex items-center justify-between">
                    <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">A. Penerimaan (Earnings)</span>
                    <i class="fa-solid fa-circle-plus text-emerald-600 text-sm"></i>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-center text-slate-700">
                        <span>Gaji Pokok (Basic Salary)</span>
                        <span class="font-mono font-semibold">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700">
                        <span>Tunjangan Operasional</span>
                        <span class="font-mono font-semibold">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center font-bold text-slate-900">
                        <span>Total Penghasilan Bruto</span>
                        <span class="font-mono text-emerald-700">Rp {{ number_format($payroll->basic_salary + $payroll->allowances, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Deductions (Potongan Resmi & Kasbon) -->
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <div class="bg-slate-100/80 px-4 py-2.5 border-b border-slate-200 flex items-center justify-between">
                    <span class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">B. Potongan (Deductions)</span>
                    <i class="fa-solid fa-circle-minus text-rose-600 text-sm"></i>
                </div>
                <div class="p-4 space-y-2.5">
                    <div class="flex justify-between items-center text-slate-700">
                        <span>Pajak PPh 21 (TER 2024)</span>
                        <span class="font-mono text-rose-600">Rp {{ number_format($payroll->pph21_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700">
                        <span>BPJS Kesehatan (1%)</span>
                        <span class="font-mono text-rose-600">Rp {{ number_format($payroll->bpjs_kesehatan_deduction ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-slate-700">
                        <span>BPJS Ketenagakerjaan (3%)</span>
                        <span class="font-mono text-rose-600">Rp {{ number_format($payroll->bpjs_tk_deduction ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @if(($payroll->loan_deduction ?? 0) > 0)
                        <div class="flex justify-between items-center text-slate-700">
                            <span>Cicilan Pinjaman / Kasbon</span>
                            <span class="font-mono text-rose-600">Rp {{ number_format($payroll->loan_deduction, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($payroll->late_deduction > 0)
                        <div class="flex justify-between items-center text-slate-700">
                            <span>Denda Keterlambatan</span>
                            <span class="font-mono text-rose-600">Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="pt-2 border-t border-slate-100 flex justify-between items-center font-bold text-slate-900">
                        <span>Total Potongan</span>
                        @php
                            $totalPotongan = ($payroll->pph21_amount ?? 0) + ($payroll->bpjs_kesehatan_deduction ?? 0) + ($payroll->bpjs_tk_deduction ?? 0) + ($payroll->loan_deduction ?? 0) + $payroll->late_deduction + $payroll->other_deductions;
                        @endphp
                        <span class="font-mono text-rose-700">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Take Home Pay Highlight Box -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-900 to-slate-900 text-white flex items-center justify-between mb-8 shadow-md">
            <div>
                <span class="text-[11px] font-bold text-blue-200 uppercase tracking-wider block">Gaji Bersih Diterima (Take Home Pay)</span>
                <p class="text-xs text-slate-300">Ditransfer ke Rekening Payroll Karyawan</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl sm:text-3xl font-black text-white font-mono">
                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <!-- Signatures & Authority -->
        <div class="grid grid-cols-2 gap-8 pt-4 text-xs text-center border-t border-slate-200">
            <div>
                <p class="text-slate-500 mb-16">Penerima (Karyawan),</p>
                <p class="font-bold text-slate-900 underline">{{ $payroll->user->name }}</p>
                <p class="text-[11px] font-mono text-slate-400">{{ $payroll->user->nik }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Finance & HR Department,</p>
                <p class="font-bold text-slate-900 underline">Admin HRD PT Maju</p>
                <p class="text-[11px] font-mono text-slate-400">Head of Human Capital</p>
            </div>
        </div>

        <!-- Footer Disclaimers -->
        <div class="mt-8 pt-4 border-t border-slate-100 text-[10px] text-slate-400 text-center">
            * Dokumen ini adalah slip gaji elektronik sah yang dihasilkan oleh Sistem HRMS PT Maju Nusantara dan tidak memerlukan stempel basah.
        </div>

    </div>

</body>
</html>
