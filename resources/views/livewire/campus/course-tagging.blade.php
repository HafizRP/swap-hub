<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <div>
            <h1 class="text-xl font-bold text-stone-900 dark:text-stone-100 flex items-center gap-2 mb-0">
                <i class="bi bi-book-half text-teal-600 dark:text-teal-400"></i> Course Tagging & Mata Kuliah
            </h1>
            <p class="text-stone-500 dark:text-stone-400 text-xs mt-1 mb-0">
                Filter proyek & skill swap berdasarkan mata kuliah kampus Anda.
            </p>
        </div>
        <button @click="showCreateModal = true" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs rounded-lg transition-colors flex items-center gap-2 border-0 cursor-pointer shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search & Course List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-4">
            <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Daftar Mata Kuliah</h2>
            
            <input type="text" wire:model.live="search" placeholder="Cari kode/nama mata kuliah..." 
                class="w-full px-3.5 py-2 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">

            <div class="space-y-2 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
                @forelse($courses as $course)
                    <div wire:key="course-{{ $course->id }}" wire:click="selectCourse('{{ $course->code }}')" 
                         class="p-3 rounded-xl border cursor-pointer transition-all flex items-center justify-between {{ $selectedCourseCode === $course->code ? 'bg-teal-50 border-teal-300 dark:bg-teal-950/40 dark:border-teal-700' : 'bg-stone-50 dark:bg-[#1a1917] border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700' }}">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-stone-900 dark:text-stone-100 text-xs">{{ $course->code }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-stone-200 dark:bg-stone-800 text-stone-700 dark:text-stone-300">{{ $course->department ?? 'Umum' }}</span>
                            </div>
                            <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 mb-0">{{ $course->name }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click.stop="toggleUserCourse({{ $course->id }})" title="Simpan ke mata kuliah saya"
                                class="p-1.5 rounded-lg border-0 bg-transparent cursor-pointer {{ in_array($course->id, $myCourseIds) ? 'text-teal-600 bg-teal-100 dark:bg-teal-900/50' : 'text-stone-400 hover:text-stone-600' }}">
                                <i class="bi {{ in_array($course->id, $myCourseIds) ? 'bi-bookmark-check-fill' : 'bi-bookmark' }}"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 text-center py-4">Belum ada mata kuliah terdaftar.</p>
                @endforelse
            </div>
        </div>

        <!-- Filtered Results -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">
                        Proyek {{ $selectedCourse ? 'Mata Kuliah: ' . $selectedCourse->code : 'Terkait' }}
                    </h2>
                    @if($selectedCourseCode)
                        <button wire:click="selectCourse('{{ $selectedCourseCode }}')" class="text-xs text-teal-600 dark:text-teal-400 hover:underline border-0 bg-transparent cursor-pointer font-medium">Hapus Filter</button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($filteredProjects as $proj)
                        <div class="p-4 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#1a1917] flex flex-col justify-between" wire:key="proj-{{ $proj->id }}">
                            <div>
                                <h3 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-1">{{ $proj->title }}</h3>
                                <p class="text-xs text-stone-500 dark:text-stone-400 line-clamp-2 mb-0">{{ $proj->description }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-stone-200 dark:border-stone-800 flex items-center justify-between text-xs">
                                <span class="text-stone-400">Pemilik: {{ $proj->user->name ?? 'Anonim' }}</span>
                                <a href="{{ route('projects.show', $proj->id) }}" class="text-teal-600 dark:text-teal-400 hover:underline font-medium no-underline">Lihat Detail &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-2 text-xs text-stone-400 text-center py-6">Tidak ada proyek yang sesuai dengan mata kuliah ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
                <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-4">
                    Skill Swap {{ $selectedCourse ? 'dari Mahasiswa ' . $selectedCourse->code : 'Terkait' }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($filteredSkillSwaps as $swap)
                        <div class="p-4 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#1a1917]" wire:key="swap-{{ $swap->id }}">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-semibold text-stone-900 dark:text-stone-100 text-xs">{{ $swap->user->name ?? 'Mahasiswa' }}</span>
                            </div>
                            <p class="text-xs text-stone-600 dark:text-stone-300 font-medium mb-1">Menawarkan: <span class="text-teal-600 dark:text-teal-400">{{ $swap->skill_offered }}</span></p>
                            <p class="text-xs text-stone-600 dark:text-stone-300 font-medium mb-0">Mencari: <span class="text-emerald-600 dark:text-emerald-400">{{ $swap->skill_wanted }}</span></p>
                        </div>
                    @empty
                        <p class="col-span-2 text-xs text-stone-400 text-center py-6">Tidak ada penawaran skill swap untuk mata kuliah ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form create course -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] w-full max-w-md p-4 rounded-xl shadow-xl space-y-4 border border-stone-200 dark:border-stone-800">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-stone-900 dark:text-stone-100 mb-0">Tambah Mata Kuliah</h3>
                <button @click="showCreateModal = false" class="text-stone-400 hover:text-stone-600 border-0 bg-transparent cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <form wire:submit.prevent="createCourse" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Kode MK (mis. IF101)</label>
                    <input type="text" wire:model="newCode" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('newCode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Nama Mata Kuliah</label>
                    <input type="text" wire:model="newName" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('newName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Departemen / Jurusan</label>
                    <input type="text" wire:model="newDepartment" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                    <button type="submit" wire:key="save-course-btn" class="px-4 py-2 text-xs bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium border-0 cursor-pointer shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
