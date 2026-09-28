@section('title', 'Jelajahi Proyek')
<x-app-layout>
    <div class="container mx-auto py-6" x-data="{ showFilters: false }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Sidebar Filters -->
            <div class="lg:col-span-3">
                <!-- Mobile Filter Toggle Button -->
                <button @click="showFilters = !showFilters" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm lg:hidden mb-4 flex items-center justify-center gap-2 border-0 cursor-pointer transition-colors">
                    <i class="bi bi-funnel-fill"></i>
                    <span>Filter & Pencarian Proyek</span>
                </button>
 
                <div class="lg:block" :class="showFilters ? 'block' : 'hidden'">
                    <div class="flex flex-col gap-4 sticky top-[90px] z-10">
                        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-5 shadow-card">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm tracking-tight mb-0 flex items-center gap-2">
                                    <i class="bi bi-sliders text-indigo-600 dark:text-indigo-400"></i>
                                    <span>Filter Proyek</span>
                                </h5>
                                @if(request('search') || request('category'))
                                    <a href="{{ route('projects.index') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline no-underline">
                                        Reset
                                    </a>
                                @endif
                            </div>
                            
                            <form action="{{ route('projects.index') }}" method="GET" class="space-y-4">
                                <!-- Search Input -->
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                                        Kata Kunci
                                    </label>
                                    <div class="relative">
                                        <span class="absolute top-1/2 left-3 -translate-y-1/2 text-slate-400">
                                            <i class="bi bi-search text-xs"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}" 
                                            class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl pl-9 pr-3.5 py-2 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" 
                                            placeholder="Cari judul, teknologi...">
                                    </div>
                                </div>
 
                                <!-- Categories -->
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                                        Kategori Proyek
                                    </label>
                                    <div class="space-y-1.5">
                                        <label class="flex items-center justify-between p-2 rounded-xl cursor-pointer text-xs font-semibold transition-colors {{ !request('category') ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/30' }}">
                                            <div class="flex items-center gap-2">
                                                <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-800" 
                                                    type="radio" name="category" value="" id="cat-all" 
                                                    {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                                <span>Semua Kategori</span>
                                            </div>
                                        </label>
                                        @foreach(['Development', 'Design', 'Marketing', 'Research'] as $cat)
                                            <label class="flex items-center justify-between p-2 rounded-xl cursor-pointer text-xs font-semibold transition-colors {{ request('category') == $cat ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/30' }}">
                                                <div class="flex items-center gap-2">
                                                    <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-800" 
                                                        type="radio" name="category" value="{{ $cat }}" id="cat-{{ $cat }}" 
                                                        {{ request('category') == $cat ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <span>{{ $cat }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
 
                                <div class="pt-2">
                                    <button type="submit" 
                                        class="w-full py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm transition-all duration-150 active:scale-[0.98] flex items-center justify-center gap-1.5 border-0 cursor-pointer">
                                        <i class="bi bi-funnel"></i>
                                        <span>Terapkan Filter</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Main Content: Projects Grid -->
            <div class="lg:col-span-9">
                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight mb-1">
                            Eksplorasi Proyek Mahasiswa
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-0">
                            Temukan tim yang tepat untuk kolaborasi dan realisasikan inovasi Anda.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700">
                            {{ $projects->total() ?? $projects->count() }} Proyek Tersedia
                        </span>
                        <a href="{{ route('projects.create') }}" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl shadow-sm flex items-center gap-1.5 text-xs no-underline transition-all duration-150 active:scale-[0.98]">
                            <i class="bi bi-plus-lg"></i>
                            <span>Buat Proyek</span>
                        </a>
                    </div>
                </div>
 
                <!-- Project Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($projects as $project)
                        <div class="col-span-1">
                            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 h-full hover-lift transition-all flex flex-col justify-between overflow-hidden group">
                                <div class="p-5 flex flex-col flex-grow">
                                    
                                    <!-- Card Header: Title & Status -->
                                    <div class="flex justify-between items-start gap-2 mb-3">
                                        <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100 truncate flex-1 mb-0 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors" title="{{ $project->title }}">
                                            <a href="{{ route('projects.show', $project) }}" class="no-underline text-inherit">
                                                {{ $project->title }}
                                            </a>
                                        </h3>
                                        @php
                                            $statusBadge = match ($project->status) {
                                                'active' => ['text' => 'text-emerald-700 dark:text-emerald-300', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60', 'label' => 'Open'],
                                                'planning' => ['text' => 'text-indigo-700 dark:text-indigo-300', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800/60', 'label' => 'Planning'],
                                                default => ['text' => 'text-slate-700 dark:text-slate-300', 'bg' => 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700', 'label' => ucfirst($project->status)],
                                            };
                                        @endphp
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border shrink-0 {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>
 
                                    <!-- Description -->
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 line-clamp-3 leading-relaxed flex-grow">
                                        {{ Str::limit($project->description, 120) }}
                                    </p>
 
                                    <!-- Metadata Badges -->
                                    <div class="flex flex-wrap gap-1.5 mb-4">
                                        <span class="bg-indigo-50/70 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 rounded-lg text-[10px] font-bold px-2 py-0.5 border border-indigo-100 dark:border-indigo-800/50 flex items-center gap-1">
                                            <i class="bi bi-tag-fill text-[9px]"></i>
                                            {{ $project->category ?? 'General' }}
                                        </span>
                                        @if($project->start_date)
                                            <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 rounded-lg text-[10px] font-semibold px-2 py-0.5 border border-slate-200 dark:border-slate-600 flex items-center gap-1">
                                                <i class="bi bi-calendar-event text-[9px]"></i>
                                                {{ $project->start_date->format('M Y') }}
                                            </span>
                                        @endif
                                        @if($project->github_repo_url)
                                            <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-semibold px-2 py-0.5 border border-slate-200 dark:border-slate-600 flex items-center gap-1">
                                                <i class="bi bi-github text-[10px]"></i>
                                                Linked
                                            </span>
                                        @endif
                                    </div>
                                </div>
 
                                <!-- Card Footer: Owner Info & Action -->
                                <div class="px-5 py-3.5 bg-slate-50/60 dark:bg-slate-800/60 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}" 
                                             class="w-6 h-6 rounded-full object-cover border border-slate-200 dark:border-slate-700 shrink-0" alt="{{ $project->owner->name }}">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[100px]">{{ $project->owner->name }}</span>
                                    </div>
                                    <a href="{{ route('projects.show', $project) }}" 
                                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 no-underline transition-colors">
                                        <span>Detail</span>
                                        <i class="bi bi-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 p-8">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 text-2xl">
                                <i class="bi bi-folder-x"></i>
                            </div>
                            <h4 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-1">Tidak Ada Proyek yang Cocok</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-5 leading-relaxed">
                                Coba ubah kata kunci pencarian atau bersihkan filter kategori untuk menemukan proyek lain.
                            </p>
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs no-underline hover:bg-slate-200 transition-colors">
                                    Reset Filter
                                </a>
                                <a href="{{ route('projects.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs no-underline shadow-sm transition-all">
                                    Buat Proyek Baru
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
 
                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    {{ $projects->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>