@section('title', 'Code Review Bounty Moderation')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-code-slash text-sky-500"></i>Code Review Bounty Moderation
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Moderasi permintaan review kode, tangani laporan spam, dan kembalikan escrow</p>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.code-reviews.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[260px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari judul / pemohon...">
                </div>
                <select name="status" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                    <option value="all">Semua Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>In Review</option>
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
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Judul & PR</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Pemohon</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Bounty</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Submissions</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Tanggal</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($requests as $req)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100 max-w-[240px] truncate" title="{{ $req->title }}">
                                        {{ $req->title }}
                                    </div>
                                    @if($req->pr_url)
                                        <a href="{{ $req->pr_url }}" target="_blank" class="text-[11px] text-sky-500 hover:underline flex items-center gap-1 mt-0.5">
                                            <i class="bi bi-box-arrow-up-right text-[10px]"></i>View PR / Repo
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="text-xs font-bold text-stone-900 dark:text-stone-100">{{ $req->user?->name ?? 'Deleted' }}</div>
                                    <div class="text-[11px] text-stone-400">{{ $req->user?->email }}</div>
                                </td>
                                <td class="px-6 py-3.5 font-bold text-xs text-amber-500">
                                    <i class="bi bi-coin mr-0.5"></i>{{ $req->bounty_credits }}
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-700 dark:text-stone-300">
                                    {{ $req->submissions->count() }} ulasan
                                </td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $badgeColor = match($req->status) {
                                            'open' => 'bg-emerald-500/10 text-emerald-600',
                                            'in_review' => 'bg-sky-500/10 text-sky-600',
                                            'completed' => 'bg-teal-500/10 text-teal-600',
                                            default => 'bg-stone-500/10 text-stone-500'
                                        };
                                    @endphp
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $badgeColor }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400">
                                    {{ $req->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    @if(in_array($req->status, ['open', 'in_review']))
                                        <form method="POST" action="{{ route('admin.code-reviews.cancel', $req) }}" onsubmit="return confirm('Batalkan permintaan code review ini dan kembalikan bounty kredit ke pemohon?')" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-bold text-red-600 dark:text-red-400 bg-red-500/10 hover:bg-red-500/20 rounded-lg border-0 cursor-pointer">
                                                Batalkan & Refund
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-stone-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada data code review.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
