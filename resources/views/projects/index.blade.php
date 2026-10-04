@section('title', 'Jelajahi Proyek')
<x-app-layout>
    <div class="py-2 space-y-4" x-data="{ showFilters: false }">
        
        <!-- Header Banner -->
        <div class="overflow-hidden rounded-lg bg-[#1a1917] text-white p-4 sm:p-4 border border-[#2e2c29]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/15 text-teal-300 border border-teal-500/20 text-xs font-semibold mb-3">
                        <i class="bi bi-compass"></i>
                        <span>Direktori Kolaborasi Kampus</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mb-2">
                        Eksplorasi Proyek Mahasiswa
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-400 max-w-xl mb-0">
                        Temukan partner dengan keahlian yang saling melengkapi. Gabung ke proyek nyata atau inisiasi ide barumu.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs font-semibold text-stone-400 bg-white/10 px-3.5 py-2 rounded-xl border border-white/15 tabular-nums">
                        {{ $projects->total() ?? $projects->count() }} Proyek Tersedia
                    </span>
                    <a href="{{ route('projects.create') }}"
                        class="bg-teal-600 hover:bg-teal-500 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm flex items-center gap-2 text-xs no-underline transition-all duration-150 active:scale-[0.98]">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Buat Proyek Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4">
            <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center justify-between">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <!-- Search Box -->
                <div class="relative w-full md:w-96 flex items-center">
                    <span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-stone-400">
                        <i class="bi bi-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-800 rounded-xl pl-9 pr-16 py-2.5 text-xs text-stone-800 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all font-medium" 
                        placeholder="Cari berdasarkan judul, teknologi, atau deskripsi...">
                    <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2.5 py-1 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-lg text-[10px] transition-colors shadow-xs border-0 cursor-pointer">
                        Cari
                    </button>
                </div>

                <!-- Category Chips Horizontal Scroll -->
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 custom-scrollbar">
                    <a href="{{ route('projects.index', array_filter(['search' => request('search')])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold no-underline whitespace-nowrap transition-all {{ !request('category') ? 'bg-teal-600 text-white shadow-sm' : 'bg-stone-100 dark:bg-[#2e2c29]/60 text-stone-600 dark:text-stone-300 hover:bg-stone-200' }}">
                        Semua Kategori
                    </a>
                    @foreach(['Development', 'Design', 'Marketing', 'Research'] as $cat)
                        <a href="{{ route('projects.index', array_filter(['search' => request('search'), 'category' => $cat])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold no-underline whitespace-nowrap transition-all {{ request('category') == $cat ? 'bg-teal-600 text-white shadow-sm' : 'bg-stone-100 dark:bg-[#2e2c29]/60 text-stone-600 dark:text-stone-300 hover:bg-stone-200' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                    @if(request('search') || request('category'))
                        <a href="{{ route('projects.index') }}" 
                           class="text-xs font-bold text-rose-500 hover:text-rose-600 px-2 py-1 no-underline whitespace-nowrap flex items-center gap-1">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($projects as $project)
                @php
                    $statusStyles = match ($project->status) {
                        'active', 'ongoing' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800', 'dot' => 'bg-emerald-500', 'label' => 'Merekrut Tim'],
                        'planning' => ['bg' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800', 'dot' => 'bg-teal-500', 'label' => 'Perencanaan'],
                        'completed' => ['bg' => 'bg-stone-100 dark:bg-[#2e2c29] text-stone-600 dark:text-stone-400 border-stone-200 dark:border-stone-800', 'dot' => 'bg-stone-400', 'label' => 'Selesai'],
                        default => ['bg' => 'bg-stone-50 dark:bg-[#2e2c29]/50 text-stone-600 dark:text-stone-400 border-stone-200 dark:border-stone-800', 'dot' => 'bg-stone-400', 'label' => ucfirst($project->status)],
                    };
                @endphp
                <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 hover-lift transition-all flex flex-col justify-between group">
                    <div class="space-y-4">
                        <!-- Top Meta -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusStyles['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $statusStyles['label'] }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if(isset($project->skill_match) && $project->skill_match['required_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $project->skill_match['badge_class'] }}" title="Kecocokan Jaccard: {{ $project->skill_match['jaccard_index'] }}">
                                        <i class="bi bi-stars"></i>
                                        {{ $project->skill_match['match_percentage'] }}% Cocok
                                    </span>
                                @endif
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                                    {{ $project->category ?? 'Tech' }}
                                </span>
                            </div>
                        </div>

                        <!-- Title & Description -->
                        <div>
                            <h3 class="text-base font-bold text-stone-900 dark:text-stone-100 mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-1">
                                <a href="{{ route('projects.show', $project) }}" class="no-underline text-inherit" wire:navigate.hover>
                                    {{ $project->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-stone-500 dark:text-stone-400 mb-0 line-clamp-2 leading-relaxed">
                                {{ $project->description ?? 'Proyek kolaboratif mahasiswa.' }}
                            </p>
                        </div>

                        @if($project->skills->isNotEmpty())
                            <div class="flex flex-wrap gap-1 pt-1">
                                @foreach($project->skills->take(3) as $s)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">
                                        {{ $s->name }}
                                    </span>
                                @endforeach
                                @if($project->skills->count() > 3)
                                    <span class="px-1.5 py-0.5 text-[10px] text-slate-400 font-bold">+{{ $project->skills->count() - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        <!-- Team & Campus Meta -->
                        <div class="flex items-center justify-between pt-2 text-xs text-stone-500 dark:text-stone-400">
                            <div class="flex items-center gap-2">
                                <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=0d9488&color=fff' }}" 
                                     class="w-6 h-6 rounded-full object-cover ring-1 ring-stone-200 dark:ring-[#2e2c29]" alt="{{ $project->owner->name }}">
                                <span class="font-medium text-stone-800 dark:text-stone-200 truncate max-w-[110px]">
                                    {{ $project->owner->name }}
                                </span>
                            </div>
                            @if($project->members->count() > 0)
                                <span class="font-semibold text-stone-600 dark:text-stone-400">
                                    {{ $project->members->count() }} Anggota
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-4 mt-4 border-t border-stone-100 dark:border-stone-800/50 flex items-center justify-between">
                        @if($project->end_date)
                            <span class="text-[11px] text-stone-400 dark:text-stone-500 font-medium">
                                Tenggat: {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d M') }}
                            </span>
                        @else
                            <span class="text-[11px] text-stone-400 dark:text-stone-500">Terbuka</span>
                        @endif
                        <a href="{{ route('projects.show', $project) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white transition-all duration-150 active:scale-[0.98] no-underline">
                            <span>Detail</span>
                            <i class="bi bi-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white dark:bg-[#141414] rounded-xl border-2 border-dashed border-stone-200 dark:border-stone-800 p-4 space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-2xl">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <div class="max-w-sm mx-auto space-y-1">
                        <h4 class="text-base font-bold text-stone-900 dark:text-stone-100">Tidak Ada Proyek yang Cocok</h4>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">
                            Coba ubah kata kunci pencarian atau reset filter kategori untuk menemukan proyek lain.
                        </p>
                    </div>
                    <div class="flex justify-center gap-3 pt-2">
                        <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-xl bg-stone-100 dark:bg-[#2e2c29] text-stone-700 dark:text-stone-200 font-bold text-xs no-underline hover:bg-stone-200 transition-colors">
                            Reset Filter
                        </a>
                        <a href="{{ route('projects.create') }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs no-underline shadow-sm transition-all">
                            Buat Proyek Baru
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="flex justify-center pt-4">
            {{ $projects->withQueryString()->links('pagination::tailwind') }}
        </div>

    </div>
</x-app-layout>
