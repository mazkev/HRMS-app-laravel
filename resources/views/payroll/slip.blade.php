<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->user->name }} ({{ $payroll->period_month }})</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex justify-center text-slate-800">

    <div class="w-full max-w-3xl space-y-4">

        <!-- Top Action Buttons (Hidden on Print) -->
        <div class="no-print flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
            <button onclick="window.history.back()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center gap-2 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </button>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>

        <!-- Official Slip Gaji Document Card -->
        <div class="print-card bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-sm space-y-6">

            <!-- Company Header -->
            <div class="flex items-center justify-between pb-6 border-b-2 border-slate-800">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xl">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h1 class="font-extrabold text-xl text-slate-900 tracking-tight">PT MAJU NUSANTARA</h1>
                        <p class="text-xs text-slate-500 font-medium">Jl. Sudirman No. 45, Jakarta Pusat 10220 • Telp: (021) 555-0199</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 font-extrabold text-xs tracking-wider uppercase border border-blue-200">
                        SLIP GAJI RESMI
                    </span>
                    <p class="text-xs text-slate-400 font-mono mt-1">No: PAY/{{ str_replace('-', '', $payroll->period_month) }}/{{ $payroll->id }}</p>
                </div>
            </div>

            <!-- Employee Info Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                <div>
                    <span class="text-slate-400 font-medium">Nama Karyawan:</span>
                    <p class="font-bold text-slate-900 mt-0.5">{{ $payroll->user->name }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">NIK:</span>
                    <p class="font-bold text-slate-900 font-mono mt-0.5">{{ $payroll->user->nik }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Jabatan / Dept:</span>
                    <p class="font-bold text-slate-900 mt-0.5">{{ $payroll->user->position }} ({{ $payroll->user->department->name ?? '-' }})</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Periode Gaji:</span>
                    <p class="font-bold text-blue-700 mt-0.5">{{ \Carbon\Carbon::createFromFormat('Y-m', $payroll->period_month)->translatedFormat('F Y') }}</p>
                </div>
            </div>

            <!-- Attendance Summary in Period -->
            <div class="flex items-center justify-between text-xs px-2 text-slate-600">
                <span>Total Kehadiran: <strong>{{ $payroll->total_present_days }} Hari</strong></span>
                <span>Keterlambatan: <strong>{{ $payroll->total_late_days }} Hari</strong></span>
                <span>Status Pembayaran: <strong class="text-emerald-700 uppercase">{{ $payroll->status }}</strong></span>
            </div>

            <!-- Earnings and Deductions Table -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Earnings -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 pb-1 border-b border-emerald-200">
                        A. Penerimaan (Earnings)
                    </h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Gaji Pokok</span>
                            <span class="font-mono font-bold text-slate-900">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Tunjangan Transport & Makan</span>
                            <span class="font-mono font-bold text-slate-900">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 font-bold text-emerald-700">
                            <span>Total Penerimaan</span>
                            <span class="font-mono">Rp {{ number_format($payroll->basic_salary + $payroll->allowances, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-800 pb-1 border-b border-rose-200">
                        B. Potongan (Deductions)
                    </h4>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Denda Keterlambatan ({{ $payroll->total_late_days }}x)</span>
                            <span class="font-mono font-bold text-rose-600">-Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Potongan Lainnya</span>
                            <span class="font-mono font-bold text-rose-600">-Rp {{ number_format($payroll->other_deductions, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 font-bold text-rose-700">
                            <span>Total Potongan</span>
                            <span class="font-mono">-Rp {{ number_format($payroll->late_deduction + $payroll->other_deductions, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Net Take Home Pay Highlight -->
            <div class="p-5 rounded-2xl bg-blue-50 border-2 border-blue-200 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    <span class="text-[11px] font-bold text-blue-800 uppercase tracking-wider">TOTAL GAJI BERSIH (TAKE HOME PAY)</span>
                    <p class="text-xs text-slate-500 mt-0.5">Ditransfer ke rekening terdaftar pada {{ $payroll->payment_date ? \Carbon\Carbon::parse($payroll->payment_date)->format('d M Y') : 'Akhir Bulan' }}</p>
                </div>
                <h2 class="text-2xl font-black text-blue-700 font-mono">
                    Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                </h2>
            </div>

            <!-- Signatures Block -->
            <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                    <p class="text-slate-400 font-medium">Penerima,</p>
                    <div class="h-16"></div>
                    <p class="font-bold text-slate-900 border-b border-slate-400 inline-block px-4 pb-0.5">{{ $payroll->user->name }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">NIK: {{ $payroll->user->nik }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">HRD & Finance Manager,</p>
                    <div class="h-16 flex items-center justify-center">
                        <span class="text-[10px] uppercase font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">PT MAJU VERIFIED</span>
                    </div>
                    <p class="font-bold text-slate-900 border-b border-slate-400 inline-block px-4 pb-0.5">Admin HRD PT Maju</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">HR Manager</p>
                </div>
            </div>

            <p class="text-[10px] text-slate-400 text-center pt-4 italic">
                * Dokumen ini dibuat secara otomatis oleh sistem HRMS PT Maju dan sah sebagai bukti pembayaran gaji.
            </p>
        </div>

    </div>

</body>
</html>
