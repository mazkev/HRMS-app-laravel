@extends('layouts.app')

@section('title', 'Audit Trail & Keamanan')
@section('page-title', 'Log Audit & Jejak Aktivitas Sistem (Audit Trail)')
@section('page-subtitle', 'Pencatatan riwayat perubahan data sensitif, aksi persetujuan, dan integritas keamanan sistem.')

@section('content')
<div class="space-y-6">

    <!-- Filters Bar -->
    <div class="saas-card p-5">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi aktivitas / NIK / nama..."
                    class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600">
            </div>

            <button type="submit" class="py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-filter text-[11px]"></i>
                <span>Cari Log</span>
            </button>
            <a href="{{ route('admin.audit.index') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition" title="Reset">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="saas-card rounded-2xl p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-4">Waktu (WIB)</th>
                        <th class="py-3 px-4">User Pelaksana</th>
                        <th class="py-3 px-4">Jenis Aksi</th>
                        <th class="py-3 px-4">Uraian Aktivitas</th>
                        <th class="py-3 px-4">IP Address & Browser</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-slate-500 font-mono">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>

                            <td class="py-3.5 px-4">
                                @if($log->user)
                                    <p class="font-bold text-slate-900">{{ $log->user->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $log->user->nik }} • {{ $log->user->role }}</p>
                                @else
                                    <span class="text-slate-400">Sistem Otomatis</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-700 max-w-md">
                                {{ $log->description }}
                            </td>

                            <td class="py-3.5 px-4 font-mono text-slate-500 text-[11px]">
                                <p>{{ $log->ip_address ?? '127.0.0.1' }}</p>
                                <p class="text-[10px] text-slate-400 truncate max-w-xs">{{ $log->user_agent ?? 'Mozilla/5.0' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400">
                                <i class="fa-solid fa-shield-halved text-3xl mb-2 text-slate-300"></i>
                                <p class="text-xs font-medium">Belum ada catatan log aktivitas audit.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection
