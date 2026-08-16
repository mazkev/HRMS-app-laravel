@extends('layouts.employee_app')

@section('title', 'Absensi Kamera & GPS')
@section('page-title', 'Absensi Kamera & Verifikasi GPS')
@section('page-subtitle', 'Lakukan clock-in dan clock-out dengan verifikasi foto selfie dan validasi geolokasi radius kantor.')

@section('content')
<div class="space-y-6">

    <!-- Camera Attendance Card -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left: Webcam Viewport & Controls (7 Cols) -->
        <div class="lg:col-span-7 saas-card p-6 flex flex-col items-center">
            <div class="w-full flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Pemindai Wajah Biometrik</h3>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="switchCamera()" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                        <span>Ganti Kamera</span>
                    </button>
                    <button type="button" onclick="initCamera()" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="fa-solid fa-power-off text-[11px]"></i>
                        <span>Restart</span>
                    </button>
                </div>
            </div>

            <!-- Video / Canvas / Preview Container -->
            <div class="w-full max-w-md aspect-[4/3] rounded-2xl overflow-hidden bg-slate-900 border border-slate-300 relative shadow-inner flex items-center justify-center">
                <!-- Video Stream -->
                <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>

                <!-- Hidden Canvas -->
                <canvas id="canvas" class="hidden"></canvas>

                <!-- Snapshot Review Image (Shown when photo taken) -->
                <img id="snapshotPreview" src="" alt="Snapshot Preview" class="w-full h-full object-cover hidden">

                <!-- Viewfinder overlay guide (Modern Biometric Frame) -->
                <div id="viewfinderOverlay" class="absolute inset-0 pointer-events-none m-6 rounded-2xl border-2 border-dashed border-white/50 flex flex-col items-center justify-between p-4">
                    <span class="text-[10px] uppercase font-bold text-white/90 bg-black/40 px-3 py-1 rounded-full backdrop-blur-sm">
                        Posisi Wajah di Tengah Frame
                    </span>
                    <div class="w-24 h-24 rounded-full border border-white/30"></div>
                    <span class="text-[10px] text-white/80 bg-black/40 px-2.5 py-0.5 rounded-full backdrop-blur-sm">
                        Pencahayaan Cukup
                    </span>
                </div>

                <!-- Camera Error / Loading Message -->
                <div id="cameraStatus" class="absolute inset-0 bg-slate-900/90 hidden flex-col items-center justify-center p-6 text-center text-xs text-slate-300 z-10">
                    <i class="fa-solid fa-camera-slash text-3xl text-rose-400 mb-2"></i>
                    <p id="cameraStatusMsg" class="font-bold text-rose-300">Menghubungkan ke kamera...</p>
                    <p class="text-[11px] text-slate-400 mt-1">Pastikan izin akses kamera telah diizinkan di browser Anda.</p>
                </div>
            </div>

            <!-- Live GPS Status Badge -->
            <div class="w-full max-w-md mt-4 p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-blue-600"></i>
                    <span id="gpsStatusText" class="text-slate-600 font-medium">Mendeteksi lokasi GPS...</span>
                </div>
                <span id="gpsBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">
                    GPS Pending
                </span>
            </div>

            <!-- Capture / Retake Buttons -->
            <div class="mt-4 flex items-center gap-3 w-full max-w-md justify-center">
                <button type="button" id="btnCapture" onclick="takeSnapshot()" class="flex-1 py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 active:scale-95 transition">
                    <i class="fa-solid fa-camera"></i>
                    <span>Ambil Foto Selfie</span>
                </button>

                <button type="button" id="btnRetake" onclick="retakeSnapshot()" class="hidden flex-1 py-3 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-300 flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Ambil Ulang Foto</span>
                </button>
            </div>
        </div>

        <!-- Right: Attendance Status & Submit Action (5 Cols) -->
        <div class="lg:col-span-5 saas-card p-6 flex flex-col justify-between">
            <div>
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h4 class="text-base font-bold text-slate-900 mb-0.5">Status Absensi Hari Ini</h4>
                    <p class="text-xs text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>

                <!-- Clock-in / Clock-out Status Details -->
                <div class="space-y-3 mb-6">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Clock-In (Masuk)</p>
                                <p class="text-sm font-bold text-slate-900">{{ $todayAttendance->time_in ?? 'Belum Absen' }}</p>
                            </div>
                        </div>
                        @if($todayAttendance && $todayAttendance->time_in)
                            <div class="text-right">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $todayAttendance->status === 'late' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $todayAttendance->status === 'late' ? 'Terlambat (> 08:30)' : 'Tepat Waktu' }}
                                </span>
                                @if($todayAttendance->distance_meters !== null)
                                    <p class="text-[10px] text-slate-400 mt-0.5">Jarak: {{ $todayAttendance->distance_meters }}m</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Clock-Out (Pulang)</p>
                                <p class="text-sm font-bold text-slate-900">{{ $todayAttendance->time_out ?? 'Belum Clock-Out' }}</p>
                            </div>
                        </div>
                        @if($todayAttendance && $todayAttendance->time_out)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                Selesai
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Submission Form Area -->
                @if(!$todayAttendance || !$todayAttendance->time_in)
                    <!-- FORM CLOCK IN -->
                    <form id="clockInForm" action="{{ route('employee.attendance.clockIn') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="image" id="clockInImage">
                        <input type="hidden" name="latitude" id="clockInLat">
                        <input type="hidden" name="longitude" id="clockInLng">

                        <div>
                            <label for="notes" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Catatan Kehadiran (Opsional)
                            </label>
                            <input type="text" name="notes" id="notes" placeholder="Contoh: Bekerja di kantor pusat / WFO"
                                class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">
                        </div>

                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2">
                            <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                            <span>Batas waktu tepat waktu: <strong>08:30 WIB</strong>. Radius kantor maksimal: <strong>{{ $officeRadius }} meter</strong>.</span>
                        </div>

                        <button type="button" onclick="submitAttendance('clockInForm', 'clockInImage')" class="w-full py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                            <i class="fa-solid fa-fingerprint"></i>
                            <span>Kirim Absen Masuk (Clock-In)</span>
                        </button>
                    </form>

                @elseif(!$todayAttendance->time_out)
                    <!-- FORM CLOCK OUT -->
                    <form id="clockOutForm" action="{{ route('employee.attendance.clockOut') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="image" id="clockOutImage">
                        <input type="hidden" name="latitude" id="clockOutLat">
                        <input type="hidden" name="longitude" id="clockOutLng">

                        <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-xs">
                            <p class="font-bold mb-1">Sudah Absen Masuk pukul {{ $todayAttendance->time_in }}</p>
                            <p class="text-slate-600 text-[11px]">Silakan ambil foto selfie untuk menyelesaikan Absen Pulang (Clock-Out) hari ini.</p>
                        </div>

                        <button type="button" onclick="submitAttendance('clockOutForm', 'clockOutImage')" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 transition active:scale-95">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Kirim Absen Pulang (Clock-Out)</span>
                        </button>
                    </form>

                @else
                    <!-- COMPLETE TODAY -->
                    <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-xl">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h5 class="text-sm font-bold text-slate-900">Absensi Hari Ini Lengkap!</h5>
                        <p class="text-xs text-slate-600">Anda telah menyelesaikan absensi masuk dan pulang untuk hari ini. Terima kasih atas dedikasi Anda!</p>
                    </div>
                @endif
            </div>

            <p class="text-[11px] text-slate-400 text-center mt-6">
                <i class="fa-solid fa-shield-halved text-blue-600 mr-1"></i> Verifikasi kamera biometrik & GPS terenkripsi aman.
            </p>
        </div>
    </div>

    <!-- Monthly Attendance History Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Riwayat Absensi Saya</h4>
                <p class="text-xs text-slate-500">Log kehadiran, foto selfie, dan verifikasi geolokasi</p>
            </div>

            <!-- Month Filter -->
            <form method="GET" action="{{ route('employee.attendance.index') }}" class="flex items-center gap-2">
                <label for="month" class="text-xs text-slate-600 font-bold">Bulan:</label>
                <input type="month" name="month" id="month" value="{{ $month }}" onchange="this.form.submit()"
                    class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4 text-center">Foto Masuk</th>
                        <th class="py-3 px-4">Jam Masuk</th>
                        <th class="py-3 px-4 text-center">Foto Pulang</th>
                        <th class="py-3 px-4">Jam Pulang</th>
                        <th class="py-3 px-4">Status & Radius</th>
                        <th class="py-3 px-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d M Y') }}
                            </td>

                            <!-- Foto In -->
                            <td class="py-3.5 px-4 text-center">
                                @if($item->image_in)
                                    <img src="{{ asset('storage/' . $item->image_in) }}" alt="Selfie Masuk"
                                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 mx-auto cursor-pointer hover:opacity-80 shadow-sm transition"
                                        onclick="showPhotoModal('{{ asset('storage/' . $item->image_in) }}', 'Selfie Masuk - {{ $item->date }}')">
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $item->time_in ?? '-' }}
                            </td>

                            <!-- Foto Out -->
                            <td class="py-3.5 px-4 text-center">
                                @if($item->image_out)
                                    <img src="{{ asset('storage/' . $item->image_out) }}" alt="Selfie Pulang"
                                        class="w-10 h-10 rounded-lg object-cover border border-slate-200 mx-auto cursor-pointer hover:opacity-80 shadow-sm transition"
                                        onclick="showPhotoModal('{{ asset('storage/' . $item->image_out) }}', 'Selfie Pulang - {{ $item->date }}')">
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
                                {{ $item->time_out ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1">
                                    <div>
                                        @if($item->status === 'present')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Tepat Waktu</span>
                                        @elseif($item->status === 'late')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Terlambat</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </div>
                                    @if($item->distance_meters !== null)
                                        <span class="text-[10px] text-slate-500">
                                            <i class="fa-solid fa-location-dot text-blue-600 text-[9px]"></i> {{ $item->distance_meters }}m dari kantor
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">
                                {{ $item->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                Belum ada riwayat absensi untuk bulan yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Foto Preview -->
<div id="photoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 id="photoModalTitle" class="text-sm font-bold text-slate-900">Bukti Foto Absensi</h4>
            <button onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Bukti Foto Absensi" class="w-full max-h-[420px] object-contain">
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStream = null;
    let facingMode = 'user';
    let capturedBase64 = null;
    let userLat = null;
    let userLng = null;

    const officeLat = {{ $officeLat }};
    const officeLng = {{ $officeLng }};
    const officeRadius = {{ $officeRadius }};

    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const previewImg = document.getElementById('snapshotPreview');
    const btnCapture = document.getElementById('btnCapture');
    const btnRetake = document.getElementById('btnRetake');
    const statusBox = document.getElementById('cameraStatus');
    const statusMsg = document.getElementById('cameraStatusMsg');
    const viewfinder = document.getElementById('viewfinderOverlay');
    const gpsStatusText = document.getElementById('gpsStatusText');
    const gpsBadge = document.getElementById('gpsBadge');

    // Haversine Formula Client-Side Distance
    function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return Math.round(R * c);
    }

    function initGeolocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    userLat = pos.coords.latitude;
                    userLng = pos.coords.longitude;

                    const dist = getDistanceFromLatLonInM(userLat, userLng, officeLat, officeLng);
                    const isInside = dist <= officeRadius;

                    gpsStatusText.innerText = `Jarak: ${dist}m dari Kantor Pusat`;
                    if (isInside) {
                        gpsBadge.innerText = 'Di Dalam Radius';
                        gpsBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                    } else {
                        gpsBadge.innerText = `Luar Radius (> ${officeRadius}m)`;
                        gpsBadge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200';
                    }

                    if (document.getElementById('clockInLat')) {
                        document.getElementById('clockInLat').value = userLat;
                        document.getElementById('clockInLng').value = userLng;
                    }
                    if (document.getElementById('clockOutLat')) {
                        document.getElementById('clockOutLat').value = userLat;
                        document.getElementById('clockOutLng').value = userLng;
                    }
                },
                (err) => {
                    console.warn('Geolocation error:', err);
                    gpsStatusText.innerText = 'GPS tidak aktif / izin ditolak';
                    gpsBadge.innerText = 'GPS Offline';
                    gpsBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            gpsStatusText.innerText = 'Browser tidak mendukung GPS';
        }
    }

    async function initCamera() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        statusBox.classList.remove('hidden');
        statusBox.classList.add('flex');
        statusMsg.innerText = 'Mengakses kamera perangkat...';

        try {
            const constraints = {
                video: {
                    facingMode: facingMode,
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            };

            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = currentStream;
            video.onloadedmetadata = () => {
                video.play();
                statusBox.classList.add('hidden');
                statusBox.classList.remove('flex');
            };
        } catch (err) {
            console.error('Camera access error:', err);
            statusBox.classList.remove('hidden');
            statusBox.classList.add('flex');
            statusMsg.innerText = 'Gagal mengakses kamera: ' + (err.message || 'Izin ditolak.');
        }
    }

    function switchCamera() {
        facingMode = (facingMode === 'user') ? 'environment' : 'user';
        initCamera();
    }

    function takeSnapshot() {
        if (!video.videoWidth) {
            Swal.fire({
                icon: 'warning',
                title: 'Kamera Belum Siap',
                text: 'Harap tunggu hingga stream kamera aktif.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        capturedBase64 = canvas.toDataURL('image/jpeg', 0.85);

        previewImg.src = capturedBase64;
        previewImg.classList.remove('hidden');
        video.classList.add('hidden');
        viewfinder.classList.add('hidden');

        btnCapture.classList.add('hidden');
        btnRetake.classList.remove('hidden');
    }

    function retakeSnapshot() {
        capturedBase64 = null;
        previewImg.classList.add('hidden');
        video.classList.remove('hidden');
        viewfinder.classList.remove('hidden');

        btnCapture.classList.remove('hidden');
        btnRetake.classList.add('hidden');
    }

    function submitAttendance(formId, inputId) {
        if (!capturedBase64) {
            Swal.fire({
                icon: 'warning',
                title: 'Foto Selfie Diperlukan',
                text: 'Silakan ambil foto selfie Anda terlebih dahulu sebelum mengirimkan absensi.',
                confirmButtonColor: '#2563eb'
            });
            return;
        }

        document.getElementById(inputId).value = capturedBase64;
        document.getElementById(formId).submit();
    }

    function showPhotoModal(src, title) {
        document.getElementById('photoModalImg').src = src;
        document.getElementById('photoModalTitle').innerText = title;
        document.getElementById('photoModal').classList.remove('hidden');
        document.getElementById('photoModal').classList.add('flex');
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.getElementById('photoModal').classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', () => {
        initGeolocation();
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            initCamera();
        } else {
            statusBox.classList.remove('hidden');
            statusBox.classList.add('flex');
            statusMsg.innerText = 'Browser Anda tidak mendukung Web Camera API.';
        }
    });
</script>
@endpush
@endsection
