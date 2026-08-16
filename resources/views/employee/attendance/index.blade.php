@extends('layouts.employee_app')

@section('title', 'Absensi Kamera & GPS')
@section('page-title', 'Absensi Kamera & GPS 📸')
@section('page-subtitle', 'Foto selfie dan verifikasi geolokasi kantor')

@section('content')
<div class="space-y-4">

    <!-- 1. MAIN CAMERA VIEWPORT & ATTENDANCE ACTION CARD -->
    <div class="saas-card p-4 sm:p-5 flex flex-col items-center shadow-md relative">
        <div class="w-full flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                    {{ (!$todayAttendance || !$todayAttendance->time_in) ? 'Absen Masuk (Clock-In)' : ((!$todayAttendance->time_out) ? 'Absen Pulang (Clock-Out)' : 'Presensi Selesai') }}
                </h3>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" onclick="switchCamera()" class="px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Kamera</span>
                </button>
                <button type="button" onclick="initCamera()" class="px-2.5 py-1 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-[11px] font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-power-off"></i>
                    <span>Reset</span>
                </button>
            </div>
        </div>

        <!-- Camera Viewfinder / Preview Frame -->
        <div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-300 relative shadow-inner flex items-center justify-center">
            <!-- Video Stream -->
            <video id="webcam" autoplay playsinline class="w-full h-full object-cover"></video>

            <!-- Hidden Canvas -->
            <canvas id="canvas" class="hidden"></canvas>

            <!-- Snapshot Review Image -->
            <img id="snapshotPreview" src="" alt="Snapshot Preview" class="w-full h-full object-cover hidden">

            <!-- Viewfinder Overlay Guide -->
            <div id="viewfinderOverlay" class="absolute inset-0 pointer-events-none m-4 rounded-2xl border-2 border-dashed border-white/60 flex flex-col items-center justify-between p-3">
                <span class="text-[10px] uppercase font-extrabold text-white bg-slate-900/60 px-3 py-1 rounded-full backdrop-blur-sm shadow">
                    Posisikan Wajah di Tengah
                </span>
                <div class="w-20 h-20 rounded-full border border-white/40"></div>
                <span class="text-[10px] text-white/90 bg-slate-900/60 px-2.5 py-0.5 rounded-full backdrop-blur-sm">
                    Pencahayaan Jelas
                </span>
            </div>

            <!-- Camera Status Message -->
            <div id="cameraStatus" class="absolute inset-0 bg-slate-900/90 hidden flex-col items-center justify-center p-6 text-center text-xs text-slate-300 z-10">
                <i class="fa-solid fa-camera-slash text-3xl text-rose-400 mb-2"></i>
                <p id="cameraStatusMsg" class="font-bold text-rose-300">Menghubungkan ke kamera...</p>
                <p class="text-[10px] text-slate-400 mt-1">Pastikan izin kamera telah diizinkan di browser Anda.</p>
            </div>
        </div>

        <!-- Live GPS Status Bar (Right below camera) -->
        <div class="w-full mt-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-blue-600 text-sm"></i>
                <span id="gpsStatusText" class="text-slate-600 font-medium text-[11px]">Mendeteksi lokasi GPS...</span>
            </div>
            <span id="gpsBadge" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">
                GPS Pending
            </span>
        </div>

        <!-- 2. PRIMARY ACTION BUTTONS (DIRECTLY ATTACHED UNDER PHOTO PREVIEW) -->
        <div class="w-full mt-3 space-y-2.5">
            @if(!$todayAttendance || !$todayAttendance->time_in)
                <!-- FORM CLOCK IN -->
                <form id="clockInForm" action="{{ route('employee.attendance.clockIn') }}" method="POST" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="image" id="clockInImage">
                    <input type="hidden" name="latitude" id="clockInLat">
                    <input type="hidden" name="longitude" id="clockInLng">

                    <!-- Button State 1: Before Taking Photo -->
                    <div id="captureActionRow" class="flex items-center gap-2">
                        <button type="button" onclick="takeSnapshot()" class="flex-1 py-3.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/30 flex items-center justify-center gap-2 active:scale-95 transition">
                            <i class="fa-solid fa-camera text-sm"></i>
                            <span>1. Ambil Foto Selfie</span>
                        </button>
                    </div>

                    <!-- Button State 2: After Taking Photo (Direct Submit & Retake) -->
                    <div id="submitActionRow" class="hidden space-y-2">
                        <button type="button" onclick="submitAttendance('clockInForm', 'clockInImage')" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-sm shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 active:scale-95 transition animate-pulse">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                            <span>2. KIRIM ABSEN MASUK SEKARANG</span>
                        </button>
                        <button type="button" onclick="retakeSnapshot()" class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center justify-center gap-1.5 transition">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Foto Ulang</span>
                        </button>
                    </div>

                    <div>
                        <input type="text" name="notes" id="notes" placeholder="Catatan opsional (misal: WFO Kantor Pusat)..."
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>
                </form>

            @elseif(!$todayAttendance->time_out)
                <!-- FORM CLOCK OUT -->
                <form id="clockOutForm" action="{{ route('employee.attendance.clockOut') }}" method="POST" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="image" id="clockOutImage">
                    <input type="hidden" name="latitude" id="clockOutLat">
                    <input type="hidden" name="longitude" id="clockOutLng">

                    <!-- Button State 1: Before Taking Photo -->
                    <div id="captureActionRow" class="flex items-center gap-2">
                        <button type="button" onclick="takeSnapshot()" class="flex-1 py-3.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/30 flex items-center justify-center gap-2 active:scale-95 transition">
                            <i class="fa-solid fa-camera text-sm"></i>
                            <span>1. Ambil Foto Selfie Pulang</span>
                        </button>
                    </div>

                    <!-- Button State 2: After Taking Photo (Direct Submit & Retake) -->
                    <div id="submitActionRow" class="hidden space-y-2">
                        <button type="button" onclick="submitAttendance('clockOutForm', 'clockOutImage')" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black text-sm shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 active:scale-95 transition animate-pulse">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                            <span>2. KIRIM ABSEN PULANG SEKARANG</span>
                        </button>
                        <button type="button" onclick="retakeSnapshot()" class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 flex items-center justify-center gap-1.5 transition">
                            <i class="fa-solid fa-rotate-left text-xs"></i>
                            <span>Foto Ulang</span>
                        </button>
                    </div>
                </form>

            @else
                <!-- COMPLETE TODAY -->
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center space-y-1">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h5 class="text-xs font-bold text-slate-900">Absensi Hari Ini Lengkap!</h5>
                    <p class="text-[11px] text-slate-600">Anda sudah menyelesaikan Absen Masuk ({{ substr($todayAttendance->time_in, 0, 5) }}) dan Pulang ({{ substr($todayAttendance->time_out, 0, 5) }}).</p>
                </div>
            @endif
        </div>
    </div>

    <!-- 3. STATUS DETAIL PRESENSI HARI INI -->
    <div class="saas-card p-4">
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Rincian Absen Hari Ini</h4>
        <div class="grid grid-cols-2 gap-3 text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                <span class="text-[10px] text-slate-400 uppercase font-bold block">Masuk (Clock-In)</span>
                <span class="font-bold text-slate-900 text-sm">{{ $todayAttendance->time_in ?? 'Belum Absen' }}</span>
                @if($todayAttendance && $todayAttendance->time_in)
                    <span class="block text-[10px] font-semibold {{ $todayAttendance->status === 'late' ? 'text-amber-600' : 'text-emerald-600' }}">
                        {{ $todayAttendance->status === 'late' ? 'Terlambat' : 'Tepat Waktu' }}
                    </span>
                @endif
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                <span class="text-[10px] text-slate-400 uppercase font-bold block">Pulang (Clock-Out)</span>
                <span class="font-bold text-slate-900 text-sm">{{ $todayAttendance->time_out ?? 'Belum Clock-Out' }}</span>
                @if($todayAttendance && $todayAttendance->time_out)
                    <span class="block text-[10px] font-semibold text-blue-600">Selesai</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 4. RIWAYAT PRESENSI BULANAN -->
    <div class="saas-card p-4">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Riwayat Bulan Ini</h4>
            <form method="GET" action="{{ route('employee.attendance.index') }}">
                <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                    class="px-2.5 py-1 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:outline-none focus:border-blue-600">
            </form>
        </div>

        <div class="space-y-2">
            @forelse($history as $item)
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2.5">
                        @if($item->image_in)
                            <img src="{{ asset('storage/' . $item->image_in) }}" alt="Foto"
                                class="w-9 h-9 rounded-xl object-cover border border-slate-200 cursor-pointer shadow-sm"
                                onclick="showPhotoModal('{{ asset('storage/' . $item->image_in) }}', 'Selfie Masuk - {{ $item->date }}')">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d M') }}</p>
                            <p class="text-[10px] text-slate-500 font-mono">
                                In: {{ substr($item->time_in ?? '-', 0, 5) }} • Out: {{ substr($item->time_out ?? '-', 0, 5) }}
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($item->status === 'present')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Tepat Waktu
                            </span>
                        @elseif($item->status === 'late')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                Terlambat
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                {{ ucfirst($item->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center py-4 text-xs text-slate-400">Belum ada riwayat absensi bulan ini.</p>
            @endforelse
        </div>
    </div>

</div>

<!-- Photo Preview Modal -->
<div id="photoModal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-4 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <h5 id="photoModalTitle" class="text-xs font-bold text-slate-900">Selfie Preview</h5>
            <button onclick="closePhotoModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <img id="photoModalImg" src="" alt="Photo" class="w-full aspect-[4/3] object-cover rounded-2xl border border-slate-200">
    </div>
</div>

@push('scripts')
<script>
    const officeLat = {{ $officeLat }};
    const officeLng = {{ $officeLng }};
    const officeRadius = {{ $officeRadius }};

    let userLat = null;
    let userLng = null;
    let currentStream = null;
    let facingMode = 'user';
    let capturedBase64 = null;

    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const previewImg = document.getElementById('snapshotPreview');
    const viewfinder = document.getElementById('viewfinderOverlay');
    const statusBox = document.getElementById('cameraStatus');
    const statusMsg = document.getElementById('cameraStatusMsg');
    const gpsStatusText = document.getElementById('gpsStatusText');
    const gpsBadge = document.getElementById('gpsBadge');
    const captureRow = document.getElementById('captureActionRow');
    const submitRow = document.getElementById('submitActionRow');

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
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
                        gpsBadge.innerText = 'Dalam Radius';
                        gpsBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
                    } else {
                        gpsBadge.innerText = `Luar Radius (${dist}m)`;
                        gpsBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300';
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
                    gpsStatusText.innerText = 'GPS offline / izin ditolak';
                    gpsBadge.innerText = 'GPS Off';
                    gpsBadge.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
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

        // Toggle Buttons Directly Attached Under Camera
        if (captureRow) captureRow.classList.add('hidden');
        if (submitRow) submitRow.classList.remove('hidden');

        // Determine if Clock-In or Clock-Out
        const isClockOut = {{ ($todayAttendance && !$todayAttendance->time_out) ? 'true' : 'false' }};
        const formId = isClockOut ? 'clockOutForm' : 'clockInForm';
        const inputId = isClockOut ? 'clockOutImage' : 'clockInImage';
        const actionLabel = isClockOut ? 'Absen Pulang (Clock-Out)' : 'Absen Masuk (Clock-In)';

        if (document.getElementById(inputId)) {
            document.getElementById(inputId).value = capturedBase64;
        }

        // Instant Confirmation Popup
        Swal.fire({
            title: 'Foto Selfie Berhasil! 📸',
            text: `Kirim data ${actionLabel} sekarang?`,
            imageUrl: capturedBase64,
            imageWidth: 220,
            imageHeight: 165,
            imageAlt: 'Selfie Preview',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fa-solid fa-paper-plane mr-1"></i> Ya, Kirim Absensi!',
            cancelButtonText: 'Foto Ulang'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                retakeSnapshot();
            }
        });
    }

    function retakeSnapshot() {
        capturedBase64 = null;
        previewImg.classList.add('hidden');
        video.classList.remove('hidden');
        viewfinder.classList.remove('hidden');

        if (captureRow) captureRow.classList.remove('hidden');
        if (submitRow) submitRow.classList.add('hidden');
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
