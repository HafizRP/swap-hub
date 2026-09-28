@section('title', 'Jelajahi Proyek')
<x-app-layout>
    <div class="py-2 space-y-6" x-data="{ showFilters: false }">
        
        <!-- Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 border border-indigo-900/50 shadow-xl">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-subtle-grid opacity-30 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-bold mb-3">
                        <i class="bi bi-compass"></i>
                        <span>Direktori Kolaborasi Kampus</span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight mb-2">
                        Eksplorasi Proyek Mahasiswa
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-xl mb-0">
                        Temukan partner dengan keahlian yang saling melengkapi. Gabung ke proyek nyata atau inisiasi ide barumu.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-xs font-bold text-slate-300 bg-white/10 px-3.5 py-2 rounded-xl border border-white/15 backdrop-blur-sm tabular-nums">
                        {{ $projects->total() ?? $projects->count() }} Proyek Tersedia
                    </span>
                    <a href="{{ route('projects.create') }}" 
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-2 text-xs no-underline transition-all duration-150 active:scale-[0.98]">
                        <i class="bi bi-plus-lg text-sm"></i>
                        <span>Buat Proyek Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-4 shadow-sm">
            <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-center justify-between">
                <!-- Search Box -->
                <div class="relative w-full md:w-96">
                    <span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-slate-400">
                        <i class="bi bi-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl pl-9 pr-3.5 py-2.5 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all font-medium" 
                        placeholder="Cari berdasarkan judul, teknologi, atau deskripsi...">
                </div>

                <!-- Category Chips Horizontal Scroll -->
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 custom-scrollbar">
                    <a href="{{ route('projects.index', array_filter(['search' => request('search')])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold no-underline whitespace-nowrap transition-all {{ !request('category') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                        Semua Kategori
                    </a>
                    @foreach(['Development', 'Design', 'Marketing', 'Research'] as $cat)
                        <a href="{{ route('projects.index', array_filter(['search' => request('search'), 'category' => $cat])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold no-underline whitespace-nowrap transition-all {{ request('category') == $cat ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($projects as $project)
                @php
                    $statusStyles = match ($project->status) {
                        'active', 'ongoing' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800', 'dot' => 'bg-emerald-500', 'label' => 'Merekrut Tim'],
                        'planning' => ['bg' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800', 'dot' => 'bg-indigo-500', 'label' => 'Perencanaan'],
                        'completed' => ['bg' => 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600', 'dot' => 'bg-slate-400', 'label' => 'Selesai'],
                        default => ['bg' => 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700', 'dot' => 'bg-slate-400', 'label' => ucfirst($project->status)],
                    };
                @endphp
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm hover-lift transition-all flex flex-col justify-between group">
                    <div class="space-y-4">
                        <!-- Top Meta -->
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusStyles['bg'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyles['dot'] }}"></span>
                                {{ $statusStyles['label'] }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                                {{ $project->category ?? 'Tech' }}
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">
                                <a href="{{ route('projects.show', $project) }}" class="no-underline text-inherit">
                                    {{ $project->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0 line-clamp-2 leading-relaxed">
                                {{ $project->description ?? 'Proyek kolaboratif mahasiswa.' }}
                            </p>
                        </div>

                        <!-- Team & Campus Meta -->
                        <div class="flex items-center justify-between pt-2 text-xs text-slate-500 dark:text-slate-400">
                            <div class="flex items-center gap-2">
                                <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}" 
                                     class="w-6 h-6 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-700" alt="{{ $project->owner->name }}">
                                <span class="font-medium text-slate-800 dark:text-slate-200 truncate max-w-[110px]">
                                    {{ $project->owner->name }}
                                </span>
                            </div>
                            @if($project->members->count() > 0)
                                <span class="font-semibold text-slate-600 dark:text-slate-400">
                                    {{ $project->members->count() }} Anggota
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
                        @if($project->end_date)
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                                Tenggat: {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d M') }}
                            </span>
                        @else
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Terbuka</span>
                        @endif
                        <a href="{{ route('projects.show', $project) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-150 active:scale-[0.98] no-underline">
                            <span>Detail</span>
                            <i class="bi bi-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700 p-8 space-y-4">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl">
                        <i class="bi bi-folder-x"></i>
                    </div>
                    <div class="max-w-sm mx-auto space-y-1">
                        <h4 class="text-base font-bold text-slate-900 dark:text-slate-100">Tidak Ada Proyek yang Cocok</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">
                            Coba ubah kata kunci pencarian atau reset filter kategori untuk menemukan proyek lain.
                        </p>
                    </div>
                    <div class="flex justify-center gap-3 pt-2">
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
        <div class="flex justify-center pt-4">
            {{ $projects->withQueryString()->links('pagination::tailwind') }}
        </div>

    </div>
</x-app-layout>
