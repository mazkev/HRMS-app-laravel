<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengalaman Kerja (Paklaring) - {{ $resignation->user->name }}</title>
    
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
            <span>Cetak / Simpan PDF Surat Paklaring</span>
        </button>
    </div>

    <!-- Official Paklaring Container -->
    <div class="print-container w-full max-w-3xl bg-white border border-slate-300 rounded-lg p-12 shadow-xl">
        
        <!-- Company Header / Letterhead -->
        <div class="text-center border-b-4 border-double border-slate-900 pb-4 mb-8">
            <h1 class="text-2xl font-bold tracking-wider uppercase text-slate-900">PT MAJU NUSANTARA</h1>
            <p class="text-sm font-semibold text-slate-700">HUMAN CAPITAL & GENERAL AFFAIRS DIVISION</p>
            <p class="text-xs text-slate-600">Gedung Wisma Nusantara Lt. 18, Jl. M.H. Thamrin No. 59, Jakarta Pusat 10350</p>
            <p class="text-xs text-slate-500">Telp: (021) 500-1234 • Email: hc@ptmaju.co.id • Website: www.ptmaju.co.id</p>
        </div>

        <!-- Document Title -->
        <div class="text-center mb-8">
            <h2 class="text-lg font-bold underline uppercase tracking-widest text-slate-900">
                SURAT PENGALAMAN KERJA (PAKLARING)
            </h2>
            <p class="text-sm font-semibold font-mono text-slate-700 mt-1">Nomor: {{ $resignation->paklaring_number }}</p>
        </div>

        <!-- Body Paragraph -->
        <div class="space-y-4 text-sm leading-relaxed text-slate-800 text-justify">
            <p>Yang bertanda tangan di bawah ini, Manajer Human Resources & General Affairs PT Maju Nusantara, dengan ini menerangkan dengan sebenarnya bahwa:</p>

            <!-- Employee Table -->
            <table class="w-full text-sm my-4 font-sans">
                <tr>
                    <td class="w-48 py-1.5 font-semibold text-slate-600">Nama Lengkap</td>
                    <td class="py-1.5">: <strong>{{ $resignation->user->name }}</strong></td>
                </tr>
                <tr>
                    <td class="py-1.5 font-semibold text-slate-600">Nomor Induk Karyawan (NIK)</td>
                    <td class="py-1.5 font-mono">: {{ $resignation->user->nik }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 font-semibold text-slate-600">Jabatan Terakhir</td>
                    <td class="py-1.5">: {{ $resignation->user->position ?? 'Staff' }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 font-semibold text-slate-600">Departemen</td>
                    <td class="py-1.5">: {{ $resignation->user->department->name ?? 'General' }}</td>
                </tr>
                <tr>
                    <td class="py-1.5 font-semibold text-slate-600">Masa Kerja</td>
                    <td class="py-1.5">: {{ \Carbon\Carbon::parse($resignation->user->join_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($resignation->resign_date)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <p>Adalah benar pernah bekerja di <strong>PT Maju Nusantara</strong> dalam kurun waktu tersebut di atas dengan jabatan terakhir sebagai <strong>{{ $resignation->user->position ?? 'Staff' }}</strong> pada divisi <strong>{{ $resignation->user->department->name ?? 'General' }}</strong>.</p>

            <p>Selama masa pengabdiannya, yang bersangkutan telah menunjukkan loyalitas, integritas, dan dedikasi yang tinggi dalam menjalankan tugas-tugas pekerjaannya. Hubungan kerja berakhir atas dasar permohonan pengunduran diri secara baik-baik dari yang bersangkutan.</p>

            <p>Atas nama Manajemen PT Maju Nusantara, kami menyampaikan ucapan terima kasih dan apresiasi setinggi-tingginya atas segala kontribusi dan kerja sama yang telah diberikan selama ini. Semoga kesuksesan senantiasa menyertai langkah karir beliau di masa depan.</p>

            <p class="pt-4">Demikian Surat Keterangan Pengalaman Kerja ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <!-- Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-16 text-sm text-center">
            <div></div>
            <div>
                <p class="mb-20 text-slate-700">Jakarta, {{ \Carbon\Carbon::parse($resignation->resign_date)->translatedFormat('d F Y') }}<br>PT Maju Nusantara,</p>
                <p class="font-bold underline text-slate-900">{{ $resignation->approver->name ?? 'Admin HRD PT Maju' }}</p>
                <p class="text-xs text-slate-500">Head of Human Capital</p>
            </div>
        </div>

    </div>

</body>
</html>
