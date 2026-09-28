@section('title', 'Eksplorasi Proyek')
<x-app-layout>
    <div class="space-y-6" x-data="{ showFilters: false }">

        <!-- Top Header & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    Eksplorasi Proyek Mahasiswa
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Temukan proyek menarik, ajukan diri bergabung, dan bangun portofolio bersama tim.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showFilters = !showFilters"
                        class="lg:hidden inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold shadow-sm">
                    <i class="bi bi-funnel"></i>
                    <span>Filter & Kategori</span>
                </button>
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-500/20 transition-all">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat Proyek</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

            <!-- Sidebar Filters (3 cols) -->
            <div class="lg:col-span-3">
                <div class="lg:block" :class="showFilters ? 'block' : 'hidden'">
                    <form action="{{ route('projects.index') }}" method="GET" class="space-y-4 sticky top-24">
                        <!-- Search Box -->
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Kata kunci proyek..."
                                   class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all shadow-sm">
                        </div>

                        <!-- Categories Panel -->
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Kategori Proyek
                            </h3>

                            <div class="space-y-2">
                                <label class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="category" value="" onchange="this.form.submit()"
                                               {{ !request('category') ? 'checked' : '' }}
                                               class="text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
                                        <span>Semua Kategori</span>
                                    </span>
                                </label>

                                @foreach(['Development', 'Design', 'Marketing', 'Research', 'Mobile', 'AI & Data'] as $cat)
                                    <label class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 cursor-pointer text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        <span class="flex items-center gap-2">
                                            <input type="radio" name="category" value="{{ $cat }}" onchange="this.form.submit()"
                                                   {{ request('category') == $cat ? 'checked' : '' }}
                                                   class="text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800">
                                            <span>{{ $cat }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Projects Grid (9 cols) -->
            <div class="lg:col-span-9 space-y-6">
                <div class="flex items-center justify-between text-xs text-slate-400 font-medium">
                    <span>Menampilkan <strong>{{ $projects->count() }}</strong> proyek ditemukan</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($projects as $project)
                        @php
                            $statusConfig = match ($project->status) {
                                'active' => ['text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-500/10', 'label' => 'Terbuka'],
                                'planning' => ['text' => 'text-brand-600 dark:text-brand-400', 'bg' => 'bg-brand-500/10', 'label' => 'Planning'],
                                default => ['text' => 'text-slate-600 dark:text-slate-400', 'bg' => 'bg-slate-500/10', 'label' => ucfirst($project->status)],
                            };
                        @endphp

                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card flex flex-col justify-between">
                            <div class="space-y-3">
                                <!-- Top Status Strip -->
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                        {{ $project->category ?? 'General' }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-base font-bold text-slate-900 dark:text-white truncate" title="{{ $project->title }}">
                                    {{ $project->title }}
                                </h3>

                                <!-- Description -->
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 leading-relaxed">
                                    {{ $project->description }}
                                </p>
                            </div>

                            <!-- Bottom Meta & Action -->
                            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}"
                                         class="w-6 h-6 rounded-md object-cover shrink-0"
                                         alt="{{ $project->owner->name }}">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[90px]">
                                        {{ $project->owner->name }}
                                    </span>
                                </div>

                                <a href="{{ route('projects.show', $project) }}"
                                   class="px-3 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 dark:bg-brand-950/40 dark:hover:bg-brand-900/50 text-brand-700 dark:text-brand-300 text-xs font-bold transition-colors">
                                    Detail <i class="bi bi-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center space-y-4 rounded-2xl bg-white dark:bg-slate-900 border border-dashed border-slate-200 dark:border-slate-800 p-8">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-2xl">
                                <i class="bi bi-search"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tidak ada proyek ditemukan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                                    Coba ubah kata kunci pencarian atau pilih kategori proyek yang lain.
                                </p>
                            </div>
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-sm">
                                <i class="bi bi-plus-lg"></i>
                                <span>Buat Proyek Baru</span>
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($projects->hasPages())
                    <div class="pt-4 flex justify-center">
                        {{ $projects->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-app-layout>
