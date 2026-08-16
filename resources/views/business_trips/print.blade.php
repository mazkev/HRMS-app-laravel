<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar SPPD - {{ $trip->sppd_number }} - {{ $trip->user->name }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f8fafc;
            color: #0f172a;
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
        <a href="javascript:history.back()" class="text-xs font-bold text-slate-600 hover:text-slate-900 flex items-center gap-2 font-sans">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Portal</span>
        </a>
        <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold font-sans shadow-md flex items-center gap-2 transition active:scale-95">
            <i class="fa-solid fa-print"></i>
            <span>Cetak / Simpan PDF Lembar SPPD</span>
        </button>
    </div>

    <!-- Official SPPD Container -->
    <div class="print-container w-full max-w-3xl bg-white border border-slate-300 rounded-lg p-12 shadow-xl">
        
        <!-- Company Header / Letterhead -->
        <div class="text-center border-b-4 border-double border-slate-900 pb-4 mb-8">
            <h1 class="text-2xl font-bold tracking-wider uppercase text-slate-900">PT MAJU NUSANTARA</h1>
            <p class="text-sm font-semibold text-slate-700">GENERAL AFFAIRS & OPERATIONAL DIVISION</p>
            <p class="text-xs text-slate-600">Gedung Wisma Nusantara Lt. 18, Jl. M.H. Thamrin No. 59, Jakarta Pusat 10350</p>
            <p class="text-xs text-slate-500">Telp: (021) 500-1234 • Email: ga@ptmaju.co.id</p>
        </div>

        <!-- Document Title -->
        <div class="text-center mb-8">
            <h2 class="text-lg font-bold underline uppercase tracking-widest text-slate-900">
                SURAT PERINTAH PERJALANAN DINAS (SPPD)
            </h2>
            <p class="text-sm font-semibold font-mono text-slate-700 mt-1">Nomor: {{ $trip->sppd_number }}</p>
        </div>

        <!-- SPPD Table Layout -->
        <table class="w-full text-sm border-collapse border border-slate-800 font-sans mb-8">
            <tbody>
                <tr class="border-b border-slate-800">
                    <td class="w-10 p-2 text-center font-bold border-r border-slate-800">1.</td>
                    <td class="w-56 p-2 font-semibold border-r border-slate-800">Pejabat Yang Memberi Perintah</td>
                    <td class="p-2 font-bold">{{ $trip->approver->name ?? 'Admin HRD PT Maju' }} (Head of HC)</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">2.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Nama Pegawai Yang Diperintahkan</td>
                    <td class="p-2 font-bold">{{ $trip->user->name }} (NIK: {{ $trip->user->nik }})</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">3.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Jabatan & Departemen</td>
                    <td class="p-2">{{ $trip->user->position ?? 'Staff' }} / {{ $trip->user->department->name ?? 'General' }}</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">4.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Maksud / Agenda Perjalanan Dinas</td>
                    <td class="p-2">{{ $trip->purpose }}</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">5.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Kota / Tempat Tujuan</td>
                    <td class="p-2 font-bold">{{ $trip->destination_city }}</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">6.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Lama Perjalanan Dinas</td>
                    <td class="p-2 font-bold">{{ $trip->total_days }} ({{ $trip->total_days }} Hari Kerja)</td>
                </tr>
                <tr class="border-b border-slate-800">
                    <td class="p-2 text-center font-bold border-r border-slate-800">7.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Tanggal Berangkat s/d Kembali</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($trip->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($trip->end_date)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="p-2 text-center font-bold border-r border-slate-800">8.</td>
                    <td class="p-2 font-semibold border-r border-slate-800">Alokasi Uang Saku Harian (Per Diem)</td>
                    <td class="p-2 font-mono font-bold">
                        Rp {{ number_format($trip->daily_allowance_rate, 0, ',', '.') }} x {{ $trip->total_days }} Hari = 
                        <span class="text-blue-700">Rp {{ number_format($trip->total_allowance, 0, ',', '.') }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures & Authority -->
        <div class="grid grid-cols-2 gap-8 pt-6 text-sm text-center">
            <div>
                <p class="mb-20 text-slate-700">Pegawai Yang Melaksanakan Tugas,</p>
                <p class="font-bold underline text-slate-900">{{ $trip->user->name }}</p>
                <p class="text-xs font-mono text-slate-500">{{ $trip->user->nik }}</p>
            </div>
            <div>
                <p class="mb-20 text-slate-700">Dikeluarkan di: Jakarta, {{ \Carbon\Carbon::parse($trip->start_date)->subDays(1)->translatedFormat('d F Y') }}<br>PT Maju Nusantara,</p>
                <p class="font-bold underline text-slate-900">{{ $trip->approver->name ?? 'Admin HRD PT Maju' }}</p>
                <p class="text-xs text-slate-500">Head of Human Capital</p>
            </div>
        </div>

    </div>

</body>
</html>
