@extends('layouts.app')

@section('title', 'Apresiasi Rekan Kerja (Kudos)')
@section('page-title', 'Apresiasi Rekan Kerja & Wall of Fame (Peer Kudos)')
@section('page-subtitle', 'Kirim lencana apresiasi untuk kerja sama tim yang luar biasa dan rayakan pencapaian bersama!')

@section('content')
<div class="space-y-8">

    <!-- Top Recognized Leaderboard (Wall of Fame) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-500"></i>
                    <span>Top Wall of Fame (Bulan Ini)</span>
                </h4>
                <p class="text-xs text-slate-500">Rekan kerja dengan perolehan apresiasi terbanyak</p>
            </div>
            <button type="button" onclick="openKudosModal()"
                class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold text-xs shadow-md shadow-amber-500/20 flex items-center gap-2 transition active:scale-95">
                <i class="fa-solid fa-award text-sm"></i>
                <span>Kirim Apresiasi Kudos</span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($topEmployees as $index => $top)
                <div class="saas-card p-5 text-center relative overflow-hidden border-2 {{ $index === 0 ? 'border-amber-400 bg-amber-50/30' : 'border-slate-200' }}">
                    @if($index === 0)
                        <div class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-amber-400 flex items-end justify-start p-1.5 text-white text-xs font-black shadow">
                            👑
                        </div>
                    @endif

                    <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white font-black text-lg flex items-center justify-center mx-auto mb-3 shadow-md">
                        {{ strtoupper(substr($top->name, 0, 2)) }}
                    </div>

                    <h5 class="text-xs font-bold text-slate-900 truncate">{{ $top->name }}</h5>
                    <p class="text-[10px] text-slate-500 font-mono mb-2">{{ $top->department->name ?? 'Staff' }}</p>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-extrabold border border-amber-300">
                        <i class="fa-solid fa-star text-amber-500 text-[11px]"></i>
                        <span>{{ $top->kudos_received_count }} Kudos</span>
                    </span>
                </div>
            @empty
                <p class="col-span-5 text-xs text-slate-400 text-center py-4">Belum ada perolehan apresiasi bulan ini.</p>
            @endforelse
        </div>
    </div>

    <!-- Kudos Feed -->
    <div class="saas-card rounded-2xl p-6">
        <h4 class="text-sm font-bold text-slate-900 mb-0.5">Linimasa Apresiasi Perusahaan (Kudos Feed)</h4>
        <p class="text-xs text-slate-500 mb-6">Pesan ucapan terima kasih dan apresiasi terbuka antar tim</p>

        <div class="space-y-4">
            @forelse($feed as $item)
                <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:bg-white hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                                {{ strtoupper(substr($item->sender->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">
                                    {{ $item->sender->name }}
                                    <span class="text-slate-400 font-normal">memberikan apresiasi kepada</span>
                                    <span class="text-blue-700 font-extrabold">{{ $item->receiver->name }}</span>
                                </p>
                                <span class="text-[10px] font-mono text-slate-400">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Badge Type Pill -->
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                            @if($item->badge_type === 'problem_solver') bg-purple-100 text-purple-800 border border-purple-300
                            @elseif($item->badge_type === 'innovator') bg-indigo-100 text-indigo-800 border border-indigo-300
                            @elseif($item->badge_type === 'customer_hero') bg-rose-100 text-rose-800 border border-rose-300
                            @elseif($item->badge_type === 'leadership') bg-blue-100 text-blue-800 border border-blue-300
                            @else bg-amber-100 text-amber-800 border border-amber-300 @endif">
                            🌟 {{ str_replace('_', ' ', $item->badge_type) }}
                        </span>
                    </div>

                    <p class="text-xs text-slate-700 italic pl-13 pt-1 border-l-2 border-l-amber-400 ml-5 my-1">
                        "{{ $item->message }}"
                    </p>
                </div>
            @empty
                <div class="text-center py-10 text-slate-400">
                    <i class="fa-regular fa-comments text-4xl mb-3 text-slate-300"></i>
                    <p class="text-xs font-medium">Jadilah yang pertama mengirimkan apresiasi Kudos kepada rekan kerja Anda hari ini!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $feed->links() }}
        </div>
    </div>

</div>

<!-- Modal Send Kudos -->
<div id="kudosModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 border border-slate-200 shadow-2xl relative">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h4 class="text-base font-bold text-slate-900">Kirim Lencana Apresiasi (Kudos)</h4>
            <button onclick="closeKudosModal()" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('kudos.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="receiver_id" class="block text-xs font-bold text-slate-700 mb-1">Pilih Rekan Kerja <span class="text-rose-500">*</span></label>
                <select name="receiver_id" id="receiver_id" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-amber-500 font-semibold">
                    @foreach($colleagues as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->department->name ?? 'Staff' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="badge_type" class="block text-xs font-bold text-slate-700 mb-1">Kategori Lencana <span class="text-rose-500">*</span></label>
                <select name="badge_type" id="badge_type" required
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-amber-500 font-semibold">
                    <option value="team_player">🤝 Team Player (Kerja Sama Hebat)</option>
                    <option value="problem_solver">💡 Problem Solver (Solutif & Cepat)</option>
                    <option value="innovator">🚀 Innovator (Ide Segar & Kreatif)</option>
                    <option value="customer_hero">⭐ Customer Hero (Pelayanan Luar Biasa)</option>
                    <option value="leadership">👑 Leadership (Inspiratif & Mengayomi)</option>
                </select>
            </div>

            <div>
                <label for="message" class="block text-xs font-bold text-slate-700 mb-1">Pesan Apresiasi <span class="text-rose-500">*</span></label>
                <textarea name="message" id="message" rows="3" required placeholder="Tuliskan ucapan terima kasih atas bantuan atau dedikasi rekan kerja..."
                    class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-amber-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeKudosModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-xs font-bold shadow-md shadow-amber-500/20">
                    Kirim Kudos 🎉
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openKudosModal() {
        document.getElementById('kudosModal').classList.remove('hidden');
        document.getElementById('kudosModal').classList.add('flex');
    }

    function closeKudosModal() {
        document.getElementById('kudosModal').classList.add('hidden');
        document.getElementById('kudosModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
