@extends('layouts.app')

@section('title', 'Papan Pengumuman')
@section('page-title', 'Papan Pengumuman Perusahaan (Company Bulletin)')
@section('page-subtitle', 'Informasi resmi, memo direksi, jadwal libur nasional, dan kebijakan internal PT Maju.')

@section('content')
<div class="space-y-6">

    @if(Auth::user()->role === 'admin_hr')
        <!-- Admin Create Announcement Box -->
        <div class="saas-card p-6">
            <h4 class="text-sm font-bold text-slate-900 mb-1">Buat Pengumuman Baru</h4>
            <p class="text-xs text-slate-500 mb-4">Siarkan informasi atau memo resmi ke seluruh portal karyawan</p>

            <form action="{{ route('admin.announcements.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" id="title" required placeholder="Contoh: Jadwal Cuti Bersama Hari Raya Idul Fitri 1447 H"
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" id="category" required
                            class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                            <option value="general">Umum (General)</option>
                            <option value="holiday">Libur & Cuti Bersama (Holiday)</option>
                            <option value="policy">Kebijakan / SOP Baru (Policy)</option>
                            <option value="event">Acara / Event Perusahaan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="content" class="block text-xs font-bold text-slate-700 mb-1">Isi Pengumuman <span class="text-rose-500">*</span></label>
                    <textarea name="content" id="content" rows="3" required placeholder="Tuliskan detail pengumuman secara jelas..."
                        class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600"></textarea>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                        <span class="font-bold">Sematkan di Atas (Pin to Top)</span>
                    </label>

                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 flex items-center gap-2 transition">
                        <i class="fa-solid fa-bullhorn text-[11px]"></i>
                        <span>Publikasikan Pengumuman</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Announcement Cards List -->
    <div class="space-y-4">
        @forelse($announcements as $item)
            <div class="saas-card p-6 {{ $item->is_pinned ? 'border-2 border-blue-300 bg-blue-50/20' : '' }}">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($item->is_pinned)
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center gap-1">
                                <i class="fa-solid fa-thumbtack text-[9px]"></i> PINNED
                            </span>
                        @endif

                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                            @if($item->category === 'holiday') bg-emerald-50 text-emerald-700 border border-emerald-200
                            @elseif($item->category === 'policy') bg-rose-50 text-rose-700 border border-rose-200
                            @elseif($item->category === 'event') bg-purple-50 text-purple-700 border border-purple-200
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ $item->category }}
                        </span>

                        <span class="text-xs text-slate-400 font-medium">
                            <i class="fa-regular fa-clock mr-1"></i> {{ $item->created_at->translatedFormat('d F Y, H:i') }} WIB
                        </span>
                    </div>

                    @if(Auth::user()->role === 'admin_hr')
                        <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-600 text-xs transition" title="Hapus Pengumuman">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <h3 class="text-base font-bold text-slate-900 mb-2">{{ $item->title }}</h3>
                <div class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                    {{ $item->content }}
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Dipublikasikan oleh: <strong class="text-slate-700">{{ $item->author->name ?? 'HRD' }}</strong></span>
                </div>
            </div>
        @empty
            <div class="saas-card p-12 text-center text-slate-400">
                <i class="fa-solid fa-bullhorn text-4xl mb-3 text-slate-300"></i>
                <h4 class="text-sm font-bold text-slate-700">Belum Ada Pengumuman</h4>
                <p class="text-xs mt-1">Pengumuman penting dan memo resmi dari perusahaan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $announcements->links() }}
    </div>

</div>
@endsection
