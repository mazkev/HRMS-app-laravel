@extends('layouts.app')

@section('title', 'Rekrutmen & Pelamar')
@section('page-title', 'Rekrutmen & Pelacakan Pelamar (ATS & Onboarding)')
@section('page-subtitle', 'Kelola lowongan kerja aktif, pipeline seleksi kandidat, dan konversi pelamar menjadi karyawan resmi.')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lowongan Aktif</span>
                <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ $jobs->count() }} <span class="text-xs text-slate-400 font-normal">Posisi Dibuka</span></h3>
        </div>

        <div class="saas-card p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Kandidat Hired (Siap Onboarding)</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-emerald-600">
                {{ $applications->where('status', 'hired')->count() }}
            </h3>
        </div>

        <!-- Button Pasang Lowongan Baru -->
        <div class="saas-card p-5 flex items-center justify-center bg-gradient-to-br from-blue-900 to-slate-900 text-white border-none shadow-md">
            <button type="button" onclick="openJobModal()" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Buka Lowongan Pekerjaan Baru</span>
            </button>
        </div>
    </div>

    <!-- Pipeline Tabs & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1">
            <a href="{{ route('admin.recruitment.index') }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($status) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Semua Pelamar
            </a>
            <a href="{{ route('admin.recruitment.index', ['status' => 'applied']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'applied' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Applied
            </a>
            <a href="{{ route('admin.recruitment.index', ['status' => 'interview']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'interview' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Interview
            </a>
            <a href="{{ route('admin.recruitment.index', ['status' => 'offering']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'offering' ? 'bg-purple-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                Offering
            </a>
            <a href="{{ route('admin.recruitment.index', ['status' => 'hired']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $status === 'hired' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-check mr-1"></i> Hired
            </a>
        </div>
    </div>

    <!-- Candidate Applications Table -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-1">Daftar Berkas Pelamar (Candidate Pool)</h4>
        <p class="text-xs text-slate-500 mb-4">Ubah status tahapan interview dan angkat kandidat menjadi karyawan</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Kandidat</th>
                        <th class="py-3 px-4">Posisi Dilamar</th>
                        <th class="py-3 px-4">Kontak</th>
                        <th class="py-3 px-4">Status Pipeline</th>
                        <th class="py-3 px-4">Jadwal Interview / Catatan</th>
                        <th class="py-3 px-4 text-center">Aksi Tahapan</th>
                        <th class="py-3 px-4 text-center">Onboarding</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $app)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $app->candidate_name }}
                            </td>

                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-slate-800">{{ $app->jobPosting->title }}</p>
                                <p class="text-[11px] text-slate-500">{{ $app->jobPosting->department->name ?? '-' }}</p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600">
                                <p>{{ $app->candidate_email }}</p>
                                <p class="text-[11px] font-mono text-slate-400">{{ $app->candidate_phone }}</p>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    @if($app->status === 'hired') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($app->status === 'interview') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($app->status === 'offering') bg-purple-50 text-purple-700 border border-purple-200
                                    @elseif($app->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                                    @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                                    {{ $app->status }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 text-[11px]">
                                @if($app->interview_date)
                                    <p class="font-bold text-blue-700">Interview: {{ \Carbon\Carbon::parse($app->interview_date)->format('d M, H:i') }} WIB</p>
                                @endif
                                <p class="italic text-slate-500">{{ $app->notes ?? '-' }}</p>
                            </td>

                            <!-- Aksi Ganti Status -->
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.recruitment.applications.status', $app->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="p-1 bg-slate-50 border border-slate-200 rounded text-[11px] text-slate-800 font-semibold focus:outline-none">
                                        <option value="applied" {{ $app->status === 'applied' ? 'selected' : '' }}>Applied</option>
                                        <option value="screening" {{ $app->status === 'screening' ? 'selected' : '' }}>Screening</option>
                                        <option value="interview" {{ $app->status === 'interview' ? 'selected' : '' }}>Interview</option>
                                        <option value="offering" {{ $app->status === 'offering' ? 'selected' : '' }}>Offering</option>
                                        <option value="hired" {{ $app->status === 'hired' ? 'selected' : '' }}>Hired</option>
                                        <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>

                            <!-- 1-Click Convert to Employee -->
                            <td class="py-3.5 px-4 text-center">
                                @if($app->status === 'hired')
                                    <form action="{{ route('admin.recruitment.applications.convert', $app->id) }}" method="POST" onsubmit="return confirm('Angkat {{ $app->candidate_name }} menjadi Karyawan resmi sekarang?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1">
                                            <i class="fa-solid fa-user-check"></i>
                                            <span>Jadikan Karyawan</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">Tahap Seleksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-users-slash text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada berkas lamaran kandidat yang masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $applications->links() }}
        </div>
    </div>

</div>

<!-- Modal Create Job -->
<div id="jobModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Buka Lowongan Pekerjaan Baru</h4>
            <button onclick="closeJobModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.recruitment.jobs.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Judul Posisi <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" required placeholder="Contoh: Senior Frontend Developer / HR Specialist"
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="department_id" class="block text-xs font-bold text-slate-700 mb-1">Departemen <span class="text-rose-500">*</span></label>
                    <select name="department_id" id="department_id" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-xs font-bold text-slate-700 mb-1">Tipe Kerja <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" required
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                        <option value="full_time">Full Time</option>
                        <option value="contract">Kontrak (Contract)</option>
                        <option value="internship">Magang (Internship)</option>
                        <option value="remote">Remote / WFH</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="experience_level" class="block text-xs font-bold text-slate-700 mb-1">Pengalaman</label>
                    <input type="text" name="experience_level" id="experience_level" value="1 - 3 Tahun"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
                <div>
                    <label for="salary_min" class="block text-xs font-bold text-slate-700 mb-1">Estimasi Gaji Pokok (Rp)</label>
                    <input type="number" name="salary_min" id="salary_min" placeholder="10000000"
                        class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 font-mono focus:bg-white focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Tugas <span class="text-rose-500">*</span></label>
                <textarea name="description" id="description" rows="2" required placeholder="Tugas utama posisi ini..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div>
                <label for="requirements" class="block text-xs font-bold text-slate-700 mb-1">Kualifikasi Pelamar <span class="text-rose-500">*</span></label>
                <textarea name="requirements" id="requirements" rows="2" required placeholder="Keahlian teknis & soft skills..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeJobModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-500/20">
                    Publikasikan Lowongan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openJobModal() {
        document.getElementById('jobModal').classList.remove('hidden');
        document.getElementById('jobModal').classList.add('flex');
    }

    function closeJobModal() {
        document.getElementById('jobModal').classList.add('hidden');
        document.getElementById('jobModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
