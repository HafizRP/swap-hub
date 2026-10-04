@section('title', 'Master Data Skills')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-tools text-teal-600 dark:text-teal-400"></i>Master Data Keahlian (Skills)
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Kelola standarisasi keahlian teknis & kategori platform</p>
                </div>
                <div>
                    <button type="button" onclick="document.getElementById('addSkillModal').classList.remove('hidden')"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors flex items-center gap-2 shadow-sm border-0 cursor-pointer">
                        <i class="bi bi-plus-lg"></i>Tambah Skill Baru
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <form action="{{ route('admin.skills.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[260px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari keahlian / kategori...">
                </div>
                <select name="category" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Nama Keahlian</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Kategori</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Total Pengguna</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($skills as $skill)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5">
                                    <span class="font-bold text-xs text-stone-900 dark:text-stone-100">{{ $skill->name }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
                                        {{ $skill->category ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-400 font-medium">
                                    <i class="bi bi-people mr-1"></i>{{ $skill->users_count }} mahasiswa
                                </td>
                                <td class="px-6 py-3.5 text-right space-x-2">
                                    <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}" class="inline-block" onsubmit="return confirm('Hapus keahlian {{ $skill->name }}?')">
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
                                <td colspan="4" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada data skill.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($skills->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $skills->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Add Skill -->
    <div id="addSkillModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] rounded-xl max-w-md w-full border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.skills.store') }}" method="POST">
                @csrf
                <div class="p-5 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm">Tambah Keahlian Baru</h5>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Nama Keahlian</label>
                        <input type="text" name="name" required placeholder="Contoh: Rust, Docker, Figma" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Kategori</label>
                        <input type="text" name="category" placeholder="Contoh: Backend, Design, Mobile" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                </div>
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 flex justify-end gap-2 bg-stone-50 dark:bg-[#1a1917]">
                    <button type="button" onclick="document.getElementById('addSkillModal').classList.add('hidden')" class="px-4 py-1.5 rounded-lg text-xs font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-200 dark:hover:bg-stone-800">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white shadow-sm border-0">Simpan Skill</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
