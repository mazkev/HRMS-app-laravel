<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PT Maju Mobile') - Employee App</title>

    <!-- PWA Manifest & Mobile Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #0f172a; /* Deep slate background for desktop framing */
        }
        .mobile-container {
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            min-height: 100vh;
            background-color: #f8fafc;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
        }
        .saas-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 2px 4px -1px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
        }
        .quick-action-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .quick-action-btn:active {
            transform: scale(0.92);
        }
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased selection:bg-blue-600 selection:text-white">

    <!-- Mobile Device Framing Container (Native App Feel) -->
    <div class="mobile-container overflow-x-hidden">
        
        <!-- Mobile App Top Bar -->
        <header class="bg-gradient-to-r from-blue-700 via-blue-800 to-slate-900 text-white px-5 pt-6 pb-6 sticky top-0 z-30 shadow-md">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center text-white font-bold shadow-inner">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h2 class="text-sm font-bold tracking-tight text-white leading-none">{{ Auth::user()->name }}</h2>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
                        </div>
                        <p class="text-[11px] text-blue-200/90 font-mono mt-0.5">{{ Auth::user()->nik }} • {{ Auth::user()->department->name ?? 'Staff' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('announcements.index') }}" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 flex items-center justify-center text-white text-xs transition">
                        <i class="fa-solid fa-bell"></i>
                    </a>
                    <button type="button" onclick="openMenuSheet()" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 flex items-center justify-center text-white text-xs transition">
                        <i class="fa-solid fa-grid-2"></i>
                    </button>
                </div>
            </div>

            <!-- Page Title Subheader -->
            <div class="flex items-center justify-between pt-1">
                <div>
                    <h3 class="text-lg font-black tracking-tight text-white">@yield('page-title', 'Dashboard Karyawan')</h3>
                    <p class="text-[11px] text-blue-200/80">@yield('page-subtitle', 'PT Maju Mobile Self-Service')</p>
                </div>
                <div class="text-right">
                    <span class="px-2.5 py-1 rounded-full bg-blue-900/60 border border-blue-400/30 text-[10px] font-bold text-blue-100 uppercase tracking-wider">
                        {{ Auth::user()->shift->name ?? 'Regular Shift' }}
                    </span>
                </div>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="flex-1 p-4 pb-32 space-y-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-2.5 shadow-sm text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0"></i>
                    <div class="font-semibold flex-1">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-2.5 shadow-sm text-xs">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm mt-0.5 shrink-0"></i>
                    <div class="font-semibold flex-1">{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm text-xs">
                    <div class="flex items-center gap-1.5 font-bold mb-1 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Periksa input formulir:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700 ml-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- NATIVE-STYLE MOBILE BOTTOM NAVIGATION BAR -->
        <div class="fixed bottom-0 inset-x-0 max-w-[480px] mx-auto bg-white/95 backdrop-blur-md border-t border-slate-200/90 py-1.5 px-3 z-40 shadow-2xl flex items-center justify-around">
            <a href="{{ route('employee.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[10px]">Home</span>
            </a>

            <a href="{{ route('employee.leave.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.leave.*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-umbrella-beach text-base"></i>
                <span class="text-[10px]">Cuti</span>
            </a>

            <!-- Central Elevated Camera Attendance Button -->
            <a href="{{ route('employee.attendance.index') }}" class="flex flex-col items-center -mt-6">
                <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-blue-700 via-blue-600 to-blue-500 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-500/40 border-4 border-slate-50 transform active:scale-90 transition">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <span class="text-[10px] font-extrabold text-blue-600 mt-0.5">Absen</span>
            </a>

            <a href="{{ route('employee.payroll.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.payroll.*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-file-invoice-dollar text-base"></i>
                <span class="text-[10px]">Gaji</span>
            </a>

            <button type="button" onclick="openMenuSheet()" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center text-slate-500 hover:text-blue-600">
                <i class="fa-solid fa-grid-2 text-base"></i>
                <span class="text-[10px]">Lainnya</span>
            </button>
        </div>

        <!-- FLOATING VIRTUAL AI HR HELPDESK WIDGET -->
        <div id="aiHelpdeskContainer" class="fixed bottom-20 right-4 z-50 flex flex-col items-end max-w-[480px]">
            <!-- Chat Window (Hidden by default) -->
            <div id="aiChatWindow" class="hidden w-[calc(100vw-2rem)] max-w-[360px] bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden mb-3 transition-all duration-300 flex-col">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-700 to-slate-900 p-4 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-amber-300 text-sm">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold leading-tight">Virtual HR Assistant</h4>
                            <span class="text-[10px] text-blue-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span> Online 24/7
                            </span>
                        </div>
                    </div>
                    <button onclick="toggleAiChat()" class="text-slate-300 hover:text-white text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Chat Logs -->
                <div id="chatMessages" class="p-4 space-y-3 h-64 overflow-y-auto bg-slate-50/50 text-xs">
                    <!-- Greeting -->
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">HR</div>
                        <div class="p-3 bg-white border border-slate-200 rounded-2xl rounded-tl-none shadow-sm text-slate-800 space-y-1">
                            <p>Halo, <strong>{{ Auth::user()->name }}</strong>! 👋</p>
                            <p class="text-slate-600">Ada yang bisa saya bantu terkait cuti, gajian, atau kebijakan kantor?</p>
                        </div>
                    </div>

                    <!-- Quick Prompts -->
                    <div class="flex flex-wrap gap-1.5 pt-1">
                        <button onclick="askAiQuestion('Berapa sisa cuti saya?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                            🏖️ Sisa cuti?
                        </button>
                        <button onclick="askAiQuestion('Kapan tanggal gajian dan potongan apa saja?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                            💰 Jadwal gajian?
                        </button>
                        <button onclick="askAiQuestion('Bagaimana perhitungan THR keagamaan?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                            🕌 Aturan THR?
                        </button>
                    </div>
                </div>

                <!-- Input Box -->
                <div class="p-2.5 bg-white border-t border-slate-100 flex items-center gap-2">
                    <input type="text" id="aiInput" placeholder="Tanya kebijakan HR..."
                        onkeypress="if(event.key==='Enter') sendUserAiMessage()"
                        class="flex-1 p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600">
                    <button onclick="sendUserAiMessage()" class="w-8 h-8 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xs shadow-sm transition">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </div>

            <!-- Floating Trigger Button -->
            <button onclick="toggleAiChat()"
                class="px-3.5 py-2.5 rounded-full bg-gradient-to-r from-blue-600 to-slate-900 text-white font-bold text-xs shadow-xl shadow-blue-500/30 flex items-center gap-2 transition transform active:scale-95">
                <i class="fa-solid fa-robot text-amber-300 text-sm"></i>
                <span>Tanya HR</span>
            </button>
        </div>

        <!-- FULL MOBILE MENU SHEET MODAL (Quick Actions Directory) -->
        <div id="mobileMenuSheet" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden items-end justify-center p-0 transition-opacity">
            <div class="w-full max-w-[480px] bg-white rounded-t-3xl max-h-[85vh] overflow-y-auto p-6 space-y-6 shadow-2xl border-t border-slate-200 animate-in slide-in-from-bottom duration-200">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-grid-2"></i>
                        </div>
                        <h4 class="text-base font-extrabold text-slate-900">Semua Menu Karyawan</h4>
                    </div>
                    <button onclick="closeMenuSheet()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Category 1: Operasional & Lapangan -->
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Kehadiran & Tugas</span>
                    <div class="grid grid-cols-4 gap-3 text-center">
                        <a href="{{ route('employee.attendance.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Absensi GPS</span>
                        </a>

                        <a href="{{ route('employee.leave.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
                                <i class="fa-solid fa-umbrella-beach"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Cuti & SKD</span>
                        </a>

                        <a href="{{ route('employee.business-trips.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-sm border border-indigo-100">
                                <i class="fa-solid fa-plane-departure"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Dinas SPPD</span>
                        </a>

                        <a href="{{ route('employee.shift-swaps.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm border border-amber-100">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Tukar Shift</span>
                        </a>
                    </div>
                </div>

                <!-- Category 2: Finansial & Benefit -->
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Keuangan & Benefit</span>
                    <div class="grid grid-cols-4 gap-3 text-center">
                        <a href="{{ route('employee.payroll.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Slip Gaji</span>
                        </a>

                        <a href="{{ route('employee.thr.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm border border-amber-100">
                                <i class="fa-solid fa-gift"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Slip THR</span>
                        </a>

                        <a href="{{ route('employee.loans.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg shadow-sm border border-teal-100">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Kasbon 0%</span>
                        </a>

                        <a href="{{ route('employee.reimbursements.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm border border-rose-100">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Reimburse</span>
                        </a>
                    </div>
                </div>

                <!-- Category 3: Perusahaan & Dokumen -->
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-3">Pengembangan & Informasi</span>
                    <div class="grid grid-cols-4 gap-3 text-center">
                        <a href="{{ route('kudos.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm border border-amber-100">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Wall of Fame</span>
                        </a>

                        <a href="{{ route('orgchart.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Org Chart</span>
                        </a>

                        <a href="{{ route('employee.performance.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-sm border border-purple-100">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Rapor KPI</span>
                        </a>

                        <a href="{{ route('employee.assets.index') }}" class="quick-action-btn flex flex-col items-center gap-1.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg shadow-sm border border-slate-200">
                                <i class="fa-solid fa-laptop"></i>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700">Aset Saya</span>
                        </a>
                    </div>
                </div>

                <!-- Category 4: Dokumen & Lainnya -->
                <div class="pt-2 border-t border-slate-100">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <a href="{{ route('employee.warning-letters.index') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center gap-2 text-slate-700 font-semibold">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                            <span>Surat Peringatan</span>
                        </a>

                        <a href="{{ route('employee.resignations.index') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center gap-2 text-slate-700 font-semibold">
                            <i class="fa-solid fa-user-xmark text-rose-500"></i>
                            <span>Resign & Paklaring</span>
                        </a>

                        <a href="{{ route('documents.index') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center gap-2 text-slate-700 font-semibold">
                            <i class="fa-solid fa-folder-open text-blue-500"></i>
                            <span>Brankas Berkas</span>
                        </a>

                        <a href="{{ route('employee.trainings.index') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center gap-2 text-slate-700 font-semibold">
                            <i class="fa-solid fa-graduation-cap text-indigo-500"></i>
                            <span>Pelatihan (LMS)</span>
                        </a>
                    </div>
                </div>

                <!-- Logout Button -->
                <div class="pt-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-2xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs flex items-center justify-center gap-2 transition active:scale-95">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script>
        function openMenuSheet() {
            const sheet = document.getElementById('mobileMenuSheet');
            sheet.classList.remove('hidden');
            sheet.classList.add('flex');
        }

        function closeMenuSheet() {
            const sheet = document.getElementById('mobileMenuSheet');
            sheet.classList.add('hidden');
            sheet.classList.remove('flex');
        }

        function toggleAiChat() {
            const win = document.getElementById('aiChatWindow');
            win.classList.toggle('hidden');
            win.classList.toggle('flex');
        }

        function askAiQuestion(question) {
            document.getElementById('aiInput').value = question;
            sendUserAiMessage();
        }

        function sendUserAiMessage() {
            const input = document.getElementById('aiInput');
            const q = input.value.trim();
            if (!q) return;

            const chat = document.getElementById('chatMessages');

            const userHtml = `
                <div class="flex items-start justify-end gap-2">
                    <div class="p-2.5 bg-blue-600 text-white rounded-2xl rounded-tr-none shadow-sm text-xs max-w-[85%]">
                        ${q}
                    </div>
                </div>
            `;
            chat.insertAdjacentHTML('beforeend', userHtml);
            input.value = '';
            chat.scrollTop = chat.scrollHeight;

            setTimeout(() => {
                let ans = "Terima kasih. Untuk pertanyaan lebih rinci, silakan hubungi HRD PT Maju.";
                const lowerQ = q.toLowerCase();

                if (lowerQ.includes('cuti')) {
                    ans = `Sisa kuota cuti tahunan Anda: <strong>{{ Auth::user()->leave_quota }} Hari</strong>. Pengajuan cuti bisa melalui menu <strong>Cuti & SKD</strong>.`;
                } else if (lowerQ.includes('thr')) {
                    ans = `Perhitungan THR mengikuti regulasi Kemnaker RI (Masa kerja &ge; 12 bulan dapat 1x gaji penuh, < 12 bulan dihitung pro-rata). Akses di menu <strong>Slip THR</strong>.`;
                } else if (lowerQ.includes('gaji') || lowerQ.includes('gajian') || lowerQ.includes('payroll')) {
                    ans = `Gaji dibayarkan tanggal 25 s/d akhir bulan. Rincian PPh 21 TER, BPJS, dan cicilan kasbon dapat dilihat di menu <strong>Slip Gaji</strong>.`;
                }

                const botHtml = `
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">HR</div>
                        <div class="p-2.5 bg-white border border-slate-200 rounded-2xl rounded-tl-none shadow-sm text-slate-800 text-xs max-w-[85%]">
                            <p>${ans}</p>
                        </div>
                    </div>
                `;
                chat.insertAdjacentHTML('beforeend', botHtml);
                chat.scrollTop = chat.scrollHeight;
            }, 500);
        }
    </script>
    @stack('scripts')
</body>
</html>
