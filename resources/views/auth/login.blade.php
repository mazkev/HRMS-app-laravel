<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal - PT Maju HRMS</title>
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
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="h-full flex antialiased bg-slate-50 selection:bg-blue-600 selection:text-white">

    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <!-- Left Column: Enterprise Brand Showcase (55% width on desktop) -->
        <div class="hidden lg:flex lg:w-7/12 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 p-12 lg:p-16 flex-col justify-between relative overflow-hidden text-white">
            <!-- Background Decorative Grid -->
            <div class="absolute inset-0 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:24px_24px] opacity-20 pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-layer-group text-xl"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-xl tracking-tight text-white">PT MAJU</h2>
                    <p class="text-xs text-blue-400 font-semibold tracking-wider uppercase">Human Resource Management System</p>
                </div>
            </div>

            <!-- Center Content -->
            <div class="relative z-10 max-w-xl my-auto py-12">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-semibold mb-6 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span>Platform HR Terintegrasi & Modern</span>
                </div>

                <h1 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    Solusi Cerdas Absensi Kamera & Manajemen Cuti Terpusat.
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed mb-8">
                    Permudah operasional tim HRD dan berikan pengalaman mandiri (*self-service*) terbaik bagi seluruh karyawan PT Maju dengan verifikasi kehadiran berbasis kamera langsung.
                </p>

                <!-- Value Props Grid -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-800/80">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 shrink-0 text-sm">
                            <i class="fa-solid fa-camera-retro"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Live Camera Attendance</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Bukti foto selfie valid saat clock-in & out.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 text-sm">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Automated Leave Quota</h4>
                            <p class="text-[11px] text-slate-400 mt-0.5">Approval cuti instan dengan auto-deduct.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="relative z-10 flex items-center justify-between text-xs text-slate-500 border-t border-slate-800/80 pt-6">
                <span>&copy; {{ date('Y') }} PT Maju Nusantara. All rights reserved.</span>
                <span class="font-medium text-slate-400">Enterprise Security Grade 256-bit</span>
            </div>
        </div>

        <!-- Right Column: Clean Authentication Form (45% width on desktop) -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-white">
            <div class="w-full max-w-md space-y-8">

                <!-- Mobile Logo Header -->
                <div class="lg:hidden flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base text-slate-900">PT MAJU</h2>
                        <p class="text-[11px] text-blue-600 font-semibold uppercase">HRMS PORTAL</p>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
                    <p class="text-xs text-slate-500 mt-1">Masukkan NIK atau Alamat Email untuk mengakses akun Anda</p>
                </div>

                @if(session('success'))
                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                                <span class="font-medium">{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="login" class="block text-xs font-bold text-slate-700 mb-1.5">
                            NIK atau Alamat Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-user text-xs"></i>
                            </div>
                            <input type="text" name="login" id="login" required autofocus
                                value="{{ old('login') }}"
                                placeholder="Contoh: HR001 atau admin@hrms.local"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi (Password)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="fa-regular fa-eye text-xs" id="password-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs text-slate-600 font-medium">Ingat perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-500/20 active:scale-[0.99] transition flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    </button>
                </form>

                <!-- Clean Corporate Demo Login Segment -->
                <div class="pt-6 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-3">
                        Akses Cepat Pengujian (1-Click Demo)
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="fillCredentials('admin@hrms.local', 'password')"
                            class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 text-left transition group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-700">Admin HRD</span>
                                <i class="fa-solid fa-shield-halved text-slate-400 group-hover:text-blue-600 text-xs"></i>
                            </div>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">admin@hrms.local</p>
                        </button>

                        <button type="button" onclick="fillCredentials('budi@hrms.local', 'password')"
                            class="p-3 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 text-left transition group">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 group-hover:text-blue-700">Karyawan</span>
                                <i class="fa-solid fa-user text-slate-400 group-hover:text-blue-600 text-xs"></i>
                            </div>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">budi@hrms.local</p>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const eye = document.getElementById('password-eye');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                eye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                eye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function fillCredentials(login, password) {
            document.getElementById('login').value = login;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
