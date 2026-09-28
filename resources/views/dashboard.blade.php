@section('title', 'Dashboard')
<x-app-layout>
    <div class="py-2 space-y-8">

        <!-- ================= 1. HERO COMMAND BANNER ================= -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 border border-indigo-900/50 shadow-2xl">
            <!-- Background Radial Glows -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 bg-subtle-grid opacity-30 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- User Greeting & Profile Highlights -->
                <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                    <div class="relative shrink-0">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                             alt="{{ $user->name }}"
                             class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover ring-2 ring-indigo-400/40 shadow-xl">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 border-2 border-slate-900 rounded-full ring-2 ring-emerald-400/30"></span>
                    </div>

                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-0">
                                Selamat Datang, {{ explode(' ', $user->name)[0] }} 👋
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                <i class="bi bi-shield-check text-indigo-300"></i>
                                Tier: {{ $user->reputation_points > 500 ? 'Master Collaborator' : ($user->reputation_points > 100 ? 'Lead Contributor' : 'Junior Creator') }}
                            </span>
                        </div>
                        <p class="text-slate-300 text-sm font-normal max-w-xl mb-0">
                            {{ $user->university ?? 'Mahasiswa' }} • {{ $user->major ?? 'Teknologi Informasi' }}
                            @if($activeProjects->count() > 0)
                                — <span class="text-emerald-300 font-semibold">{{ $activeProjects->count() }} proyek aktif</span> sedang berjalan minggu ini.
                            @else
                                — Siap memulai proyek baru hari ini?
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('projects.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all duration-150 active:scale-[0.98] no-underline">
                        <i class="bi bi-plus-lg text-base"></i>
                        <span>Buat Proyek</span>
                    </a>
                    <a href="{{ route('projects.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold bg-white/10 hover:bg-white/15 text-slate-200 border border-white/15 backdrop-blur-sm transition-all duration-150 active:scale-[0.98] no-underline">
                        <i class="bi bi-compass"></i>
                        <span>Cari Tim</span>
                    </a>
                    @if(Route::has('resume.download'))
                        <a href="{{ route('resume.download', $user) }}"
                           title="Unduh Resume Portofolio PDF"
                           class="inline-flex items-center justify-center p-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/15 backdrop-blur-sm transition-all duration-150 active:scale-[0.98] no-underline">
                            <i class="bi bi-file-earmark-pdf text-base text-rose-400"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= 2. BENTO METRICS KPI ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Card 1: Reputasi -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover-lift transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Poin Reputasi</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">
                        {{ number_format($user->reputation_points) }}
                    </h3>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-full">
                        pts
                    </span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                        <span>Target Badge Level</span>
                        <span>{{ ($user->reputation_points % 100) }} / 100</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-400 h-1.5 rounded-full" style="width: {{ min(100, max(15, ($user->reputation_points % 100))) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Proyek Selesai -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover-lift transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Proyek Tuntas</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">
                        {{ $completedProjectsCount }}
                    </h3>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                        Portofolio siap
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-patch-check-fill text-emerald-500"></i>
                    <span>Tervalidasi peer-review dosen & tim</span>
                </p>
            </div>

            <!-- Card 3: Kolaborasi Aktif -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover-lift transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sprint Berjalan</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                        <i class="bi bi-kanban"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">
                        {{ $activeProjects->count() }}
                    </h3>
                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                        Sedang aktif
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-calendar-event text-indigo-500"></i>
                    <span>Tersinkron Google Calendar</span>
                </p>
            </div>

            <!-- Card 4: Permintaan Kolaborasi -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/60 shadow-sm hover-lift transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Undangan Masuk</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg">
                        <i class="bi bi-envelope-open-fill"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">
                        {{ $collaborationInvitesCount }}
                    </h3>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                        Permintaan
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-bell text-sky-500"></i>
                    <span>Cek berkala untuk rekrutmen baru</span>
                </p>
            </div>

        </div>

        <!-- ================= 3. MAIN WORKSPACE + SIDEBAR ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT AREA: Projects & Recommendations (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Section Header: Active Squad Projects -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mb-0">
                                Proyek Kolaborasi Aktif
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Pantau perkembangan tim dan akses langsung workspace.</p>
                        </div>
                        <a href="{{ route('projects.index') }}"
                           class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 no-underline">
                            <span>Semua Proyek</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Active Project Cards Grid -->
                    <div class="grid grid-cols-1 gap-4">
                        @forelse($activeProjects as $project)
                            @php
                                $totalTasks = $project->tasks?->count() ?? 10;
                                $doneTasks = $project->tasks?->where('status', 'done')->count() ?? 6;
                                $progressPercent = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 60;
                            @endphp
                            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm hover-lift transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                    <div class="flex items-start gap-3.5 min-w-0">
                                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xl shrink-0 border border-indigo-200/50 dark:border-indigo-800/40">
                                            {{ strtoupper(substr($project->title, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-0 truncate">
                                                    {{ $project->title }}
                                                </h3>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0 line-clamp-1">
                                                {{ $project->description ?? 'Tidak ada deskripsi singkat.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Quick Sprint Date Badge -->
                                    @if($project->end_date)
                                        <div class="shrink-0 text-right">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700/50 px-3 py-1 rounded-lg">
                                                <i class="bi bi-clock-history text-slate-400"></i>
                                                Tenggat: {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Progress Bar & Squad Meta -->
                                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-medium text-slate-500 dark:text-slate-400">Kemajuan Proyek</span>
                                        <span class="font-bold text-slate-900 dark:text-slate-100 tabular-nums">{{ $progressPercent }}% selesai</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                                    </div>

                                    <!-- Action Strip -->
                                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                                        <!-- Member Avatars Stack -->
                                        <div class="flex items-center gap-2">
                                            <div class="flex -space-x-2">
                                                @foreach($project->members->take(4) as $m)
                                                    <img src="{{ $m->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($m->name) . '&background=random' }}"
                                                         title="{{ $m->name }}"
                                                         alt="{{ $m->name }}"
                                                         class="w-7 h-7 rounded-full border-2 border-white dark:border-slate-800 object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                                @endforeach
                                            </div>
                                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                                {{ $project->members->count() }} Anggota Tim
                                            </span>
                                        </div>

                                        <!-- Fast Jump Actions -->
                                        <div class="flex items-center gap-2">
                                            @if($project->conversation)
                                                <a href="{{ route('chat', $project->conversation) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors no-underline">
                                                    <i class="bi bi-chat-dots-fill"></i>
                                                    <span>Chat</span>
                                                </a>
                                            @endif
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white dark:bg-slate-100 dark:hover:bg-white dark:text-slate-900 transition-colors no-underline">
                                                <span>Detail Workspace</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700 p-10 text-center space-y-4">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 mx-auto flex items-center justify-center text-2xl">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div class="max-w-sm mx-auto space-y-1">
                                    <h4 class="text-base font-bold text-slate-900 dark:text-slate-100">Belum Ada Proyek Aktif</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Kamu belum tergabung dalam proyek apa pun. Mulai ide barumu atau daftar ke proyek mahasiswa lain.</p>
                                </div>
                                <div class="flex justify-center gap-3 pt-2">
                                    <a href="{{ route('projects.create') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white no-underline">
                                        <i class="bi bi-plus-lg"></i>
                                        <span>Buat Proyek Pertama</span>
                                    </a>
                                    <a href="{{ route('projects.index') }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 no-underline">
                                        <span>Jelajahi Proyek</span>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section: Rekomendasi Kolaborasi Tim -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mb-0">
                                Rekomendasi Proyek Terbuka
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Proyek dari kampus lain yang membutuhkan kontributor baru.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($recommendedProjects as $rec)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm hover-lift transition-all flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-800/40">
                                            {{ $rec->category ?? 'Tech' }}
                                        </span>
                                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                            <i class="bi bi-stars"></i> 95% Match
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-1 line-clamp-1">
                                            {{ $rec->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mb-0">
                                            {{ $rec->description ?? 'Proyek kolaboratif antar mahasiswa untuk portofolio siap kerja.' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                        <span>Owner:</span>
                                        <span class="font-medium text-slate-800 dark:text-slate-200 truncate max-w-[120px]">
                                            {{ $rec->owner?->name ?? 'Mahasiswa' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('projects.show', $rec) }}"
                                       class="block text-center w-full py-2 px-3 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition-all duration-150 active:scale-[0.98] no-underline">
                                        Lihat & Ajukan Gabung
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-6 text-slate-400 text-sm">
                                Tidak ada rekomendasi proyek baru saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT AREA: Deadlines & Live Stream (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Upcoming Deadlines & Calendar Sync -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-0">Tenggat Mendatang</h3>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Google Cal Synced
                        </span>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($upcomingDeadlines as $deadline)
                            @php
                                $diffDays = (int) now()->diffInDays(\Carbon\Carbon::parse($deadline->end_date), false);
                                $urgencyClass = $diffDays <= 2 ? 'border-rose-400 bg-rose-50/50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-300' : 'border-amber-400 bg-amber-50/50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-300';
                            @endphp
                            <div class="p-3 rounded-xl border-l-4 {{ $urgencyClass }} bg-slate-50 dark:bg-slate-700/30 transition-colors">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wide">
                                        {{ \Carbon\Carbon::parse($deadline->end_date)->translatedFormat('d M, H:i') }}
                                    </span>
                                    <span class="text-[10px] font-bold">
                                        {{ $diffDays <= 0 ? 'Hari Ini' : ($diffDays == 1 ? 'Besok' : $diffDays . ' Hari Lagi') }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 mb-0 truncate">
                                    {{ $deadline->title }}
                                </h4>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                                <i class="bi bi-calendar-check text-2xl block mb-1 text-slate-300 dark:text-slate-600"></i>
                                Tidak ada tenggat mendesak minggu ini.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('projects.index') }}"
                       class="block text-center w-full py-2.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/60 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors no-underline">
                        Lihat Seluruh Kalender Tugas
                    </a>
                </div>

                <!-- Notifications & Real-Time Activities Stream -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm font-bold">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-0">Aktivitas Terkini</h3>
                        </div>
                        <a href="{{ route('chat') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline no-underline">
                            Buka Chat
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->data['link'] ?? '#' }}"
                               class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors no-underline group">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    <i class="bi bi-chat-left-text"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-slate-800 dark:text-slate-200 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 font-medium line-clamp-2">
                                        {{ $notif->data['message'] ?? 'Pesan baru di workspace.' }}
                                    </p>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block tabular-nums">
                                        {{ $notif->created_at ? \Carbon\Carbon::parse($notif->created_at)->diffForHumans() : 'Baru saja' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-slate-400 dark:text-slate-500 text-xs">
                                <i class="bi bi-chat-square-dots text-2xl block mb-1 text-slate-300 dark:text-slate-600"></i>
                                Belum ada pesan atau notifikasi baru.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
