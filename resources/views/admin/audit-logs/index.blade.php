@section('title', 'Admin Audit Logs')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-shield-check text-emerald-500"></i>Admin Audit Trail
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Rekam jejak seluruh tindakan administratif, intervensi akun, transaksi, dan moderasi konten</p>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[260px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari aksi / admin / IP...">
                </div>
                <select name="action" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                    <option value="all">Semua Tipe Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Waktu</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Administrator</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Target</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Rincian Perubahan</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($logs as $log)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5 text-xs text-stone-500">
                                    <div>{{ $log->created_at->format('d M Y, H:i:s') }}</div>
                                    <small class="text-stone-400 text-[10px]">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100">{{ $log->admin?->name ?? 'System' }}</div>
                                    <div class="text-[11px] text-stone-400">{{ $log->admin?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-200 font-mono">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-400">
                                    @if($log->target_type)
                                        <span class="font-semibold text-stone-700 dark:text-stone-300">{{ class_basename($log->target_type) }}</span>
                                        <span class="text-stone-400">#{{ $log->target_id }}</span>
                                    @else
                                        <span class="text-stone-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-xs font-mono text-stone-600 dark:text-stone-400 max-w-[320px] truncate" title="{{ json_encode($log->details) }}">
                                    {{ $log->details ? json_encode($log->details) : '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400 font-mono">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada riwayat audit log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
