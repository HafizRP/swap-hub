@section('title', 'Master Data Courses')

<x-app-layout>
    <div class="space-y-6">
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-mortarboard text-sky-500"></i>Master Data Mata Kuliah
                    </h5>
                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Kelola katalog resmi mata kuliah, departemen, dan kurikulum kampus</p>
                </div>
                <div>
                    <button type="button" onclick="document.getElementById('addCourseModal').classList.remove('hidden')"
                        class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-colors flex items-center gap-2 shadow-sm border-0 cursor-pointer">
                        <i class="bi bi-plus-lg"></i>Tambah Mata Kuliah
                    </button>
                </div>
            </div>

            <!-- Search -->
            <form action="{{ route('admin.courses.index') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[280px] shadow-sm">
                    <span class="pl-3 pr-2 text-stone-400"><i class="bi bi-search text-xs"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs" 
                        placeholder="Cari kode / nama / jurusan...">
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Kode</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Nama Mata Kuliah</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Jurusan & Semester</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Proyek & Mahasiswa</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($courses as $course)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                                <td class="px-6 py-3.5 font-bold text-xs text-sky-600 dark:text-sky-400">
                                    {{ $course->code }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="font-bold text-xs text-stone-900 dark:text-stone-100">{{ $course->name }}</div>
                                    <div class="text-[11px] text-stone-400 truncate max-w-[280px]">{{ $course->description ?? 'Tidak ada deskripsi' }}</div>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-700 dark:text-stone-300">
                                    <div>{{ $course->department ?? 'Umum' }}</div>
                                    <small class="text-stone-400 text-[10px]">{{ $course->semester ?? '-' }}</small>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-400">
                                    <span class="mr-3"><i class="bi bi-kanban mr-1"></i>{{ $course->projects_count }} Proyek</span>
                                    <span><i class="bi bi-people mr-1"></i>{{ $course->users_count }} Mahasiswa</span>
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="inline-block" onsubmit="return confirm('Hapus mata kuliah {{ $course->name }}?')">
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
                                <td colspan="5" class="px-6 py-8 text-center text-xs text-stone-400">Belum ada data mata kuliah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
                <div class="p-4 border-t border-stone-100 dark:border-stone-800">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Add Course -->
    <div id="addCourseModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-stone-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] rounded-xl max-w-md w-full border border-stone-200 dark:border-stone-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="p-5 border-b border-stone-100 dark:border-stone-800">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm">Daftarkan Mata Kuliah Baru</h5>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">Kode MK</label>
                            <input type="text" name="code" required placeholder="IF301" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-500 mb-1">Semester</label>
                            <input type="text" name="semester" placeholder="Semester 5" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Nama Mata Kuliah</label>
                        <input type="text" name="name" required placeholder="Rekayasa Perangkat Lunak" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Departemen / Jurusan</label>
                        <input type="text" name="department" placeholder="Teknik Informatika" class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 mb-1">Deskripsi Ringkas</label>
                        <textarea name="description" rows="2" placeholder="Deskripsi materi atau tugas akhir..." class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-2 text-xs text-stone-900 dark:text-stone-100"></textarea>
                    </div>
                </div>
                <div class="p-4 border-t border-stone-100 dark:border-stone-800 flex justify-end gap-2 bg-stone-50 dark:bg-[#1a1917]">
                    <button type="button" onclick="document.getElementById('addCourseModal').classList.add('hidden')" class="px-4 py-1.5 rounded-lg text-xs font-medium text-stone-600 dark:text-stone-400 hover:bg-stone-200 dark:hover:bg-stone-800">Batal</button>
                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-sky-600 hover:bg-sky-700 text-white shadow-sm border-0">Simpan Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
