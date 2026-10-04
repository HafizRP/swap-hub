@section('title', 'Master Data Badges')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-award text-purple-500"></i>Master Data Lencana (Badges)
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Kelola penghargaan prestasi mahasiswa dan sematkan lencana secara manual</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="document.getElementById('assignBadgeModal').classList.remove('hidden')"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors flex items-center gap-2 shadow-sm border-0 cursor-pointer">
                        <i class="bi bi-person-check"></i>Sematkan ke Mahasiswa
                    </button>
                    <button type="button" onclick="document.getElementById('addBadgeModal').classList.remove('hidden')"
                        class="bg-stone-800 hover:bg-stone-900 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors flex items-center gap-2 shadow-sm border-0 cursor-pointer">
                        <i class="bi bi-plus-lg"></i>Buat Badge Baru
                    </button>
                </div>
            </div>

            <!-- Search -->
            <form action="{{ route('admin.badges.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[280px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari lencana...">
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Lencana</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Deskripsi</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Syarat Reputasi</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Penerima</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($badges as $badge)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5 flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                                        <i class="bi {{ $badge->icon ?? 'bi-award' }}"></i>
                                    </div>
                                    <span class="font-bold text-xs text-stone-900 dark:text-stone-100">{{ $badge->name }}</span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-400 max-w-[300px] truncate" title="{{ $badge->description }}">
                                    {{ $badge->description ?? 'Tidak ada deskripsi' }}
                                </td>
                                <td class="px-6 py-3.5 text-xs font-semibold text-amber-500">
                                    <i class="bi bi-star-fill mr-1"></i>{{ $badge->points_required ?? 0 }} pts
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-400 font-medium">
                                    <i class="bi bi-people mr-1"></i>{{ $badge->users_count }} mahasiswa
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}" class="inline-block" onsubmit="return confirm('Hapus badge {{ $badge->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-stone-400 hover:text-red-500 bg-transparent border-0 cursor-pointer" title="Hapus">
                                            <i class="bi bi-trash-fill text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada data badge penghargaan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($badges->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $badges->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Add Badge -->
    <div id="addBadgeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] rounded-xl max-w-md w-full border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.badges.store') }}" method="POST">
                @csrf
                <div class="p-5 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm">Buat Badge Baru</h5>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Nama Badge</label>
                        <input type="text" name="name" required placeholder="Top Reviewer 2026" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Bootstrap Icon Class</label>
                        <input type="text" name="icon" placeholder="bi-trophy-fill" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Poin Minimum Diperlukan</label>
                        <input type="number" name="points_required" placeholder="100" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Deskripsi</label>
                        <textarea name="description" rows="2" placeholder="Diberikan kepada mahasiswa yang..." class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100"></textarea>
                    </div>
                </div>
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 flex justify-end gap-2 bg-stone-50 dark:bg-[#1a1917]">
                    <button type="button" onclick="document.getElementById('addBadgeModal').classList.add('hidden')" class="px-4 py-1.5 rounded-lg text-xs font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-200 dark:hover:bg-stone-800">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white shadow-sm border-0">Simpan Badge</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Assign Badge -->
    <div id="assignBadgeModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] rounded-xl max-w-md w-full border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.badges.assign') }}" method="POST">
                @csrf
                <div class="p-5 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm">Sematkan Badge ke Mahasiswa</h5>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Pilih Mahasiswa</label>
                        <select name="user_id" required class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Pilih Badge</label>
                        <select name="badge_id" required class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                            @foreach($badges as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 flex justify-end gap-2 bg-stone-50 dark:bg-[#1a1917]">
                    <button type="button" onclick="document.getElementById('assignBadgeModal').classList.add('hidden')" class="px-4 py-1.5 rounded-lg text-xs font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-200 dark:hover:bg-stone-800">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-purple-600 hover:bg-purple-700 text-white shadow-sm border-0">Sematkan Badge</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
