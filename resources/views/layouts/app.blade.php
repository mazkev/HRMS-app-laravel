<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HRMS') - PT Maju</title>

    <!-- PWA Manifest & Meta -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
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
        }
        .saas-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04), 0 1px 2px -1px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
        }
        .saas-card:hover {
            box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.06);
        }
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full flex bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Mobile Drawer Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleMobileSidebar()" 
        class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- Responsive Sidebar (Drawer on Mobile, Fixed on Desktop) -->
    <aside id="appSidebar" 
        class="w-72 bg-white border-r border-slate-200/90 flex flex-col fixed inset-y-0 left-0 z-50 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none">
        
        <!-- Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-layer-group text-lg"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-base text-slate-900 tracking-tight">PT MAJU</h1>
                    <p class="text-[10px] text-blue-600 font-bold tracking-wider uppercase">Premier HR Enterprise</p>
                </div>
            </div>
            <!-- Close Button on Mobile Drawer -->
            <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3.5 py-4 space-y-1 overflow-y-auto">
            @if(Auth::user()->role === 'admin_hr')
                <!-- ADMIN MENU -->
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Operasional & Eksekutif
                </div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-line w-4 text-center {{ request()->routeIs('admin.analytics.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Executive Analytics</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-tower-broadcast w-4 text-center {{ request()->routeIs('admin.notifications.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Gateway WA / Email</span>
                </a>

                <a href="{{ route('admin.attendance.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.attendance.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-camera-retro w-4 text-center {{ request()->routeIs('admin.attendance.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Log Absensi & GPS</span>
                </a>

                <a href="{{ route('admin.leave.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.leave.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-calendar-check w-4 text-center {{ request()->routeIs('admin.leave.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Persetujuan Cuti & SKD</span>
                </a>

                <a href="{{ route('admin.business-trips.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.business-trips.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-plane-departure w-4 text-center {{ request()->routeIs('admin.business-trips.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Surat Tugas (SPPD)</span>
                </a>

                <a href="{{ route('admin.shift-swaps.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.shift-swaps.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 text-center {{ request()->routeIs('admin.shift-swaps.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Tukar Shift Kerja</span>
                </a>

                <a href="{{ route('admin.warning-letters.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.warning-letters.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center {{ request()->routeIs('admin.warning-letters.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Surat Peringatan (SP)</span>
                </a>

                <a href="{{ route('admin.resignations.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.resignations.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-xmark w-4 text-center {{ request()->routeIs('admin.resignations.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Offboarding & Paklaring</span>
                </a>

                <a href="{{ route('admin.overtime.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.overtime.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-business-time w-4 text-center {{ request()->routeIs('admin.overtime.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Persetujuan Lembur</span>
                </a>

                <a href="{{ route('admin.reimbursements.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.reimbursements.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('admin.reimbursements.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Klaim Reimbursement</span>
                </a>

                <a href="{{ route('admin.loans.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.loans.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-hand-holding-dollar w-4 text-center {{ request()->routeIs('admin.loans.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pinjaman & Kasbon</span>
                </a>

                <a href="{{ route('admin.thr.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.thr.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-gift w-4 text-center {{ request()->routeIs('admin.thr.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Tunjangan Hari Raya (THR)</span>
                </a>

                <a href="{{ route('admin.performance.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.performance.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-award w-4 text-center {{ request()->routeIs('admin.performance.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Penilaian Kinerja (KPI)</span>
                </a>

                <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.payroll.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-4 text-center {{ request()->routeIs('admin.payroll.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Penggajian & PPh 21</span>
                </a>

                <a href="{{ route('admin.calendar.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.calendar.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-calendar-days w-4 text-center {{ request()->routeIs('admin.calendar.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Kalender Cuti Tim</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Talenta & Apresiasi
                </div>

                <a href="{{ route('kudos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('kudos.*') ? 'bg-amber-50 text-amber-800 border-l-4 border-amber-500 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-trophy w-4 text-center {{ request()->routeIs('kudos.*') ? 'text-amber-500' : 'text-slate-400' }}"></i>
                    <span>Apresiasi (Wall of Fame)</span>
                </a>

                <a href="{{ route('orgchart.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('orgchart.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-sitemap w-4 text-center {{ request()->routeIs('orgchart.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Struktur Organisasi</span>
                </a>

                <a href="{{ route('admin.recruitment.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.recruitment.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-plus w-4 text-center {{ request()->routeIs('admin.recruitment.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Rekrutmen & ATS</span>
                </a>

                <a href="{{ route('admin.assets.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.assets.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-laptop w-4 text-center {{ request()->routeIs('admin.assets.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Inventaris Aset</span>
                </a>

                <a href="{{ route('admin.trainings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.trainings.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-graduation-cap w-4 text-center {{ request()->routeIs('admin.trainings.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pelatihan (LMS Lite)</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Informasi & Audit
                </div>

                <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-bullhorn w-4 text-center {{ request()->routeIs('announcements.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Papan Pengumuman</span>
                </a>

                <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('documents.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-folder-open w-4 text-center {{ request()->routeIs('documents.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Brankas Dokumen</span>
                </a>

                <a href="{{ route('admin.audit.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.audit.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-shield-halved w-4 text-center {{ request()->routeIs('admin.audit.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Audit Trail Log</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Master Data
                </div>

                <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.employees.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-group w-4 text-center {{ request()->routeIs('admin.employees.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Data Karyawan</span>
                </a>

                <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.departments.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-building-user w-4 text-center {{ request()->routeIs('admin.departments.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Departemen</span>
                </a>

                <a href="{{ route('admin.shifts.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('admin.shifts.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-clock w-4 text-center {{ request()->routeIs('admin.shifts.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Shift Kerja</span>
                </a>
            @else
                <!-- EMPLOYEE MENU -->
                <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Portal Karyawan
                </div>

                <a href="{{ route('employee.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.dashboard') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-house w-4 text-center {{ request()->routeIs('employee.dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Dashboard Saya</span>
                </a>

                <a href="{{ route('employee.attendance.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.attendance.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-camera w-4 text-center {{ request()->routeIs('employee.attendance.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Absensi Kamera & GPS</span>
                </a>

                <a href="{{ route('employee.leave.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.leave.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-umbrella-beach w-4 text-center {{ request()->routeIs('employee.leave.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pengajuan Cuti & SKD</span>
                </a>

                <a href="{{ route('employee.business-trips.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.business-trips.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-plane-departure w-4 text-center {{ request()->routeIs('employee.business-trips.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Tugas Dinas (SPPD)</span>
                </a>

                <a href="{{ route('employee.shift-swaps.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.shift-swaps.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 text-center {{ request()->routeIs('employee.shift-swaps.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Tukar Shift Kerja</span>
                </a>

                <a href="{{ route('employee.thr.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.thr.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-gift w-4 text-center {{ request()->routeIs('employee.thr.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Tunjangan Hari Raya (THR)</span>
                </a>

                <a href="{{ route('employee.warning-letters.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.warning-letters.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center {{ request()->routeIs('employee.warning-letters.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Surat Peringatan Saya</span>
                </a>

                <a href="{{ route('employee.resignations.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.resignations.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-user-xmark w-4 text-center {{ request()->routeIs('employee.resignations.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Resignasi & Paklaring</span>
                </a>

                <a href="{{ route('employee.overtime.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.overtime.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-business-time w-4 text-center {{ request()->routeIs('employee.overtime.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pengajuan Lembur</span>
                </a>

                <a href="{{ route('employee.reimbursements.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.reimbursements.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-receipt w-4 text-center {{ request()->routeIs('employee.reimbursements.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Klaim Biaya (Reimburse)</span>
                </a>

                <a href="{{ route('employee.loans.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.loans.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-hand-holding-dollar w-4 text-center {{ request()->routeIs('employee.loans.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pinjaman & Kasbon</span>
                </a>

                <a href="{{ route('employee.performance.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.performance.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-award w-4 text-center {{ request()->routeIs('employee.performance.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Rapor Kinerja KPI</span>
                </a>

                <a href="{{ route('employee.payroll.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.payroll.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-4 text-center {{ request()->routeIs('employee.payroll.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Slip Gaji Saya</span>
                </a>

                <a href="{{ route('employee.calendar.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.calendar.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-calendar-days w-4 text-center {{ request()->routeIs('employee.calendar.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Kalender Cuti Tim</span>
                </a>

                <a href="{{ route('kudos.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('kudos.*') ? 'bg-amber-50 text-amber-800 border-l-4 border-amber-500 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-trophy w-4 text-center {{ request()->routeIs('kudos.*') ? 'text-amber-500' : 'text-slate-400' }}"></i>
                    <span>Apresiasi (Wall of Fame)</span>
                </a>

                <a href="{{ route('orgchart.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('orgchart.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-sitemap w-4 text-center {{ request()->routeIs('orgchart.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Struktur Organisasi</span>
                </a>

                <a href="{{ route('employee.assets.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.assets.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-laptop w-4 text-center {{ request()->routeIs('employee.assets.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Aset yang Dipegang</span>
                </a>

                <a href="{{ route('employee.trainings.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('employee.trainings.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-graduation-cap w-4 text-center {{ request()->routeIs('employee.trainings.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Katalog Pelatihan</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Informasi & Arsip
                </div>

                <a href="{{ route('announcements.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-bullhorn w-4 text-center {{ request()->routeIs('announcements.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Pengumuman Kantor</span>
                </a>

                <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-xs transition-all {{ request()->routeIs('documents.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i class="fa-solid fa-folder-open w-4 text-center {{ request()->routeIs('documents.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Brankas Berkas Saya</span>
                </a>
            @endif
        </nav>

        <!-- User Profile footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-3 mb-3 p-2 bg-white rounded-xl border border-slate-200/70 shadow-sm">
                <div class="w-9 h-9 rounded-lg bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-flex items-center text-[10px] font-semibold text-blue-600">
                        {{ Auth::user()->role === 'admin_hr' ? 'Admin HRD' : 'Employee' }}
                    </span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl text-xs font-semibold text-slate-600 bg-white hover:bg-rose-50 hover:text-rose-600 border border-slate-200/80 transition shadow-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket text-[11px]"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area (Full width on mobile, shifted on desktop) -->
    <div class="flex-1 flex flex-col lg:pl-72 min-h-screen bg-slate-50 relative w-full overflow-x-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 sm:h-20 border-b border-slate-200/80 bg-white/90 backdrop-blur-md px-4 sm:px-8 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (Mobile Only) -->
                <button type="button" onclick="toggleMobileSidebar()" 
                    class="lg:hidden w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-base transition active:scale-95">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight leading-tight">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 hidden sm:block">@yield('page-subtitle', 'Sistem Manajemen Sumber Daya Manusia PT Maju')</p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Date/Time Pill (Hidden on phone) -->
                <div class="hidden md:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-medium text-slate-600">
                    <i class="fa-regular fa-calendar text-blue-600"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                <div class="text-right">
                    <p class="text-xs font-bold text-slate-900 truncate max-w-[120px] sm:max-w-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500 font-mono">{{ Auth::user()->nik }}</p>
                </div>
            </div>
        </header>

        <!-- Main Body (With bottom padding for Mobile Navigation Bar) -->
        <main class="flex-1 p-4 sm:p-8 pb-28 lg:pb-8 space-y-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                    <div class="flex-1 text-xs font-semibold">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-base mt-0.5"></i>
                    <div class="flex-1 text-xs font-semibold">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-xs mb-1 text-rose-700">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Mohon periksa kembali input formulir:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 ml-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-4 px-4 sm:px-8 border-t border-slate-200/70 bg-white text-center text-[11px] sm:text-xs text-slate-400 hidden lg:block">
            &copy; {{ date('Y') }} PT Maju Nusantara HR Management System. Premier Global Enterprise Edition.
        </footer>
    </div>

    <!-- NATIVE-STYLE MOBILE BOTTOM NAVIGATION BAR (MOBILE ONLY) -->
    <div class="lg:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 py-1.5 px-2 z-40 shadow-xl flex items-center justify-around">
        @if(Auth::user()->role === 'admin_hr')
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-chart-pie text-base"></i>
                <span class="text-[10px]">Home</span>
            </a>

            <a href="{{ route('admin.attendance.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('admin.attendance.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-camera text-base"></i>
                <span class="text-[10px]">Absensi</span>
            </a>

            <a href="{{ route('admin.leave.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('admin.leave.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-calendar-check text-base"></i>
                <span class="text-[10px]">Cuti</span>
            </a>

            <a href="{{ route('admin.payroll.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('admin.payroll.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-file-invoice-dollar text-base"></i>
                <span class="text-[10px]">Payroll</span>
            </a>

            <button type="button" onclick="toggleMobileSidebar()" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center text-slate-500 hover:text-blue-600">
                <i class="fa-solid fa-grid-2 text-base"></i>
                <span class="text-[10px]">Menu</span>
            </button>
        @else
            <a href="{{ route('employee.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[10px]">Home</span>
            </a>

            <a href="{{ route('employee.leave.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.leave.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-umbrella-beach text-base"></i>
                <span class="text-[10px]">Cuti</span>
            </a>

            <!-- Central Elevated Camera Attendance Button -->
            <a href="{{ route('employee.attendance.index') }}" class="flex flex-col items-center -mt-5">
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-blue-500 text-white flex items-center justify-center text-lg shadow-lg shadow-blue-500/30 border-2 border-white transform active:scale-95 transition">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 mt-0.5">Absen</span>
            </a>

            <a href="{{ route('employee.payroll.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center {{ request()->routeIs('employee.payroll.*') ? 'text-blue-600 font-bold' : 'text-slate-500' }}">
                <i class="fa-solid fa-file-invoice-dollar text-base"></i>
                <span class="text-[10px]">Gaji</span>
            </a>

            <button type="button" onclick="toggleMobileSidebar()" class="flex flex-col items-center gap-0.5 py-1 px-2 text-center text-slate-500 hover:text-blue-600">
                <i class="fa-solid fa-bars text-base"></i>
                <span class="text-[10px]">Menu</span>
            </button>
        @endif
    </div>

    <!-- FLOATING VIRTUAL AI HR HELPDESK WIDGET (Positioned above bottom nav on mobile) -->
    <div id="aiHelpdeskContainer" class="fixed bottom-20 lg:bottom-6 right-4 sm:right-6 z-50 flex flex-col items-end">
        <!-- Chat Window (Hidden by default) -->
        <div id="aiChatWindow" class="hidden w-[calc(100vw-2rem)] sm:w-96 max-w-sm bg-white rounded-2xl border border-slate-200 shadow-2xl overflow-hidden mb-3 transition-all duration-300 flex-col">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-700 to-slate-900 p-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-amber-300 text-sm">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold leading-tight">Virtual HR Assistant</h4>
                        <span class="text-[10px] text-blue-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span> Siap Menjawab 24/7
                        </span>
                    </div>
                </div>
                <button onclick="toggleAiChat()" class="text-slate-300 hover:text-white text-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Chat Logs -->
            <div id="chatMessages" class="p-4 space-y-3 h-72 overflow-y-auto bg-slate-50/50 text-xs">
                <!-- Greeting -->
                <div class="flex items-start gap-2">
                    <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">HR</div>
                    <div class="p-3 bg-white border border-slate-200 rounded-2xl rounded-tl-none shadow-sm text-slate-800 space-y-1">
                        <p>Halo, <strong>{{ Auth::user()->name }}</strong>! 👋</p>
                        <p class="text-slate-600">Saya asisten pintar HR PT Maju. Anda dapat memilih pertanyaan cepat atau ketik pertanyaan seputar kebijakan kantor di bawah ini:</p>
                    </div>
                </div>

                <!-- Quick Prompts Buttons -->
                <div class="flex flex-wrap gap-1.5 pt-1">
                    <button onclick="askAiQuestion('Berapa sisa cuti saya?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                        🏖️ Sisa cuti saya?
                    </button>
                    <button onclick="askAiQuestion('Kapan tanggal gajian dan potongan apa saja?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                        💰 Jadwal gajian & PPh 21?
                    </button>
                    <button onclick="askAiQuestion('Bagaimana perhitungan THR keagamaan?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                        🕌 Aturan THR Keagamaan?
                    </button>
                    <button onclick="askAiQuestion('Bagaimana syarat SPPD dinas luar kota?')" class="px-2.5 py-1 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-[11px] font-medium transition">
                        ✈️ Syarat SPPD luar kota?
                    </button>
                </div>
            </div>

            <!-- Input Box -->
            <div class="p-3 bg-white border-t border-slate-100 flex items-center gap-2">
                <input type="text" id="aiInput" placeholder="Ketik pertanyaan seputar HR..."
                    onkeypress="if(event.key==='Enter') sendUserAiMessage()"
                    class="flex-1 p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600">
                <button onclick="sendUserAiMessage()" class="w-8 h-8 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xs shadow-sm transition">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <!-- Floating Trigger Button -->
        <button onclick="toggleAiChat()"
            class="px-3.5 sm:px-4 py-2.5 sm:py-3 rounded-full bg-gradient-to-r from-blue-600 to-slate-900 hover:from-blue-700 hover:to-slate-800 text-white font-bold text-xs shadow-xl shadow-blue-500/25 flex items-center gap-2 transition transform hover:scale-105 active:scale-95">
            <i class="fa-solid fa-robot text-amber-300 text-sm"></i>
            <span class="hidden sm:inline">Tanya Asisten HR</span>
        </button>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Mobile Sidebar Drawer
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                }, 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                setTimeout(() => {
                    backdrop.classList.add('hidden');
                }, 300);
            }
        }

        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => {
                    console.log('SW registration skipped or failed:', err);
                });
            });
        }

        // Virtual HR AI Assistant Chat Logic
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

            // Append User Message
            const userHtml = `
                <div class="flex items-start justify-end gap-2">
                    <div class="p-3 bg-blue-600 text-white rounded-2xl rounded-tr-none shadow-sm text-xs max-w-[80%]">
                        ${q}
                    </div>
                </div>
            `;
            chat.insertAdjacentHTML('beforeend', userHtml);
            input.value = '';
            chat.scrollTop = chat.scrollHeight;

            // Generate AI Response based on Knowledge Base
            setTimeout(() => {
                let ans = "Terima kasih atas pertanyaan Anda. Untuk informasi lebih spesifik, silakan hubungi tim HRD di ruangan lantai 2.";
                const lowerQ = q.toLowerCase();

                if (lowerQ.includes('cuti') && (lowerQ.includes('sisa') || lowerQ.includes('berapa'))) {
                    ans = `Sisa kuota cuti tahunan Anda saat ini adalah <strong>{{ Auth::user()->leave_quota }} Hari</strong>. Pengajuan cuti dapat dilakukan melalui menu <strong>Pengajuan Cuti & SKD</strong>.`;
                } else if (lowerQ.includes('thr') || lowerQ.includes('hari raya')) {
                    ans = `Perhitungan <strong>Tunjangan Hari Raya (THR)</strong> mengikuti regulasi Kemnaker RI. Masa kerja &ge; 12 bulan berhak atas <strong>1x Gaji Penuh</strong>, sedangkan masa kerja &lt; 12 bulan dihitung <strong>pro-rata</strong>. Rincian dan slip dapat diakses di menu <strong>Tunjangan Hari Raya (THR)</strong>.`;
                } else if (lowerQ.includes('sppd') || lowerQ.includes('dinas') || lowerQ.includes('perjalanan')) {
                    ans = `Perjalanan dinas luar kota/negeri difasilitasi dengan uang saku harian (*per diem allowance*) sebesar <strong>Rp 350.000 / hari</strong> di luar tiket dan hotel. Lembar Surat Tugas SPPD resmi diterbitkan otomatis setelah disetujui HRD.`;
                } else if (lowerQ.includes('tukar') && lowerQ.includes('shift')) {
                    ans = `Pertukaran shift kerja dapat diajukan ke sesama rekan satu divisi melalui menu <strong>Tukar Shift Kerja</strong> dan memerlukan persetujuan Supervisor/HRD.`;
                } else if (lowerQ.includes('kudos') || lowerQ.includes('apresiasi')) {
                    ans = `Anda dapat mengirimkan lencana apresiasi Kudos ke rekan kerja melalui menu <strong>Apresiasi (Wall of Fame)</strong> untuk mengapresiasi kerja tim, inovasi, dan dedikasi rekan kerja.`;
                } else if (lowerQ.includes('gaji') || lowerQ.includes('gajian') || lowerQ.includes('payroll') || lowerQ.includes('pph')) {
                    ans = `Gaji bulanan dibayarkan pada <strong>tanggal 25 hingga akhir bulan</strong>. Rincian gaji telah menghitung otomatis <strong>Pajak PPh 21 (TER 2024)</strong>, <strong>BPJS Kesehatan (1%)</strong>, <strong>BPJS Ketenagakerjaan (3%)</strong>, dan cicilan kasbon jika ada. Anda dapat mengunduh slip resmi di menu <strong>Slip Gaji Saya</strong>.`;
                } else if (lowerQ.includes('kasbon') || lowerQ.includes('pinjam') || lowerQ.includes('pinjaman')) {
                    ans = `Fasilitas kasbon internal PT Maju memiliki <strong>bunga 0%</strong> dengan plafon s/d Rp 20.000.000 dan pilihan tenor 1 s/d 12 bulan. Potongan cicilan dilakukan otomatis melalui Payroll setiap akhir bulan.`;
                } else if (lowerQ.includes('sakit') || lowerQ.includes('dokter') || lowerQ.includes('skd') || lowerQ.includes('melahirkan')) {
                    ans = `Cuti sakit mewajibkan unggah <strong>Surat Keterangan Dokter (SKD)</strong>. Cuti Melahirkan diberikan selama <strong>90 hari (3 bulan)</strong> dan <strong>TIDAK MEMOTONG</strong> kuota cuti tahunan Anda.`;
                }

                const botHtml = `
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">HR</div>
                        <div class="p-3 bg-white border border-slate-200 rounded-2xl rounded-tl-none shadow-sm text-slate-800 space-y-1 text-xs max-w-[85%]">
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
