@section('title', 'Skill Swap Moderation')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-arrow-left-right text-teal-600 dark:text-teal-400"></i>Skill Swap Moderation
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Pantau transaksi barter keahlian mahasiswa, moderasi sengketa dan intervensi status</p>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.swaps.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[260px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari nama mahasiswa...">
                </div>
                <select name="status" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Pemohon (Offers)</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Penyedia (Requests)</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Pertukaran Skill</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Tanggal</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($swaps as $swap)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100">{{ $swap->requester?->name ?? 'Deleted' }}</div>
                                    <div class="text-[11px] text-stone-400">{{ $swap->requester?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100">{{ $swap->provider?->name ?? 'Deleted' }}</div>
                                    <div class="text-[11px] text-stone-400">{{ $swap->provider?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5 text-xs">
                                    <span class="text-teal-600 dark:text-teal-400 font-semibold">{{ $swap->offeredSkill?->name ?? 'N/A' }}</span>
                                    <i class="bi bi-arrow-left-right mx-1 text-stone-400"></i>
                                    <span class="text-sky-600 dark:text-sky-400 font-semibold">{{ $swap->requestedSkill?->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $badgeClass = match($swap->status) {
                                            'pending' => 'bg-amber-500/10 text-amber-600',
                                            'accepted' => 'bg-sky-500/10 text-sky-600',
                                            'completed' => 'bg-emerald-500/10 text-emerald-600',
                                            default => 'bg-stone-500/10 text-stone-500'
                                        };
                                    @endphp
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $badgeClass }}">
                                        {{ $swap->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400">
                                    {{ $swap->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3.5 text-right space-x-2">
                                    @if($swap->status === 'accepted')
                                        <form method="POST" action="{{ route('admin.swaps.complete', $swap) }}" class="inline-block" onsubmit="return confirm('Paksa selesaikan sesi swap ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 rounded-lg border-0 cursor-pointer">
                                                Selesaikan
                                            </button>
                                        </form>
                                    @endif
                                    @if(in_array($swap->status, ['pending', 'accepted']))
                                        <form method="POST" action="{{ route('admin.swaps.cancel', $swap) }}" class="inline-block" onsubmit="return confirm('Batalkan sesi swap ini?')">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-bold text-red-600 dark:text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded-lg border-0 cursor-pointer">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada transaksi swap.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($swaps->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $swaps->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
