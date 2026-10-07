@section('title', 'Dashboard')
<x-app-layout>
    <div class="py-2 space-y-4">

        <!-- Compact Hero Greeting -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
            <div class="flex items-center gap-3">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d9488&color=fff' }}"
                     alt="{{ $user->name }}"
                     class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl object-cover ring-2 ring-teal-500/20 shrink-0">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-stone-100 mb-0 truncate">
                        Selamat Datang, {{ explode(' ', $user->name)[0] }} 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mb-0 truncate">
                        @if($activeProjects->count() > 0)
                            <span class="text-teal-600 dark:text-teal-400 font-semibold">{{ $activeProjects->count() }} proyek aktif</span> • {{ $user->university ?? 'Mahasiswa' }}
                        @else
                            {{ $user->university ?? 'Mahasiswa' }} • Siap memulai proyek baru?
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('projects.create') }}"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium bg-teal-600 hover:bg-teal-500 text-white transition-all no-underline shadow-xs">
                    <i class="bi bi-plus-lg"></i>
                    <span>Buat Proyek</span>
                </a>
                <a href="{{ route('projects.index') }}"
                   class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 text-stone-900 dark:text-stone-100 transition-all no-underline">
                    <i class="bi bi-compass"></i>
                    <span>Eksplor</span>
                </a>
            </div>
        </div>

        <!-- ================= 2. BENTO METRICS KPI ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <!-- Card 1: Reputasi -->
            <div class="bg-white dark:bg-[#141414] rounded-xl p-4 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wider">Poin Reputasi</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-stone-900 dark:text-stone-100 tabular-nums tracking-tight mb-0">
                        {{ number_format($user->reputation_points) }}
                    </h3>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-full">
                        pts
                    </span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-[11px] text-stone-500 dark:text-stone-400 font-medium">
                        <span>Target Badge Level</span>
                        <span>{{ ($user->reputation_points % 100) }} / 100</span>
                    </div>
                    <div class="w-full bg-stone-100 dark:bg-[#2e2c29]/50 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-400 h-1.5 rounded-full" style="width: {{ min(100, max(15, ($user->reputation_points % 100))) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Proyek Selesai -->
            <div class="bg-white dark:bg-[#141414] rounded-xl p-4 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wider">Proyek Tuntas</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="bi bi-check2-all"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-stone-900 dark:text-stone-100 tabular-nums tracking-tight mb-0">
                        {{ $completedProjectsCount }}
                    </h3>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                        Portofolio siap
                    </span>
                </div>
                <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-patch-check-fill text-emerald-500"></i>
                    <span>Tervalidasi peer-review dosen & tim</span>
                </p>
            </div>

            <!-- Card 3: Kolaborasi Aktif -->
            <div class="bg-white dark:bg-[#141414] rounded-xl p-4 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wider">Sprint Berjalan</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg">
                        <i class="bi bi-kanban"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-stone-900 dark:text-stone-100 tabular-nums tracking-tight mb-0">
                        {{ $activeProjects->count() }}
                    </h3>
                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400">
                        Sedang aktif
                    </span>
                </div>
                <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-calendar-event text-teal-500"></i>
                    <span>Tersinkron Google Calendar</span>
                </p>
            </div>

            <!-- Card 4: Permintaan Kolaborasi -->
            <div class="bg-white dark:bg-[#141414] rounded-xl p-4 border border-stone-200 dark:border-stone-800 hover:border-stone-300 dark:hover:border-stone-700 transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-stone-400 dark:text-stone-500 uppercase tracking-wider">Undangan Masuk</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg">
                        <i class="bi bi-envelope-open-fill"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mb-2">
                    <h3 class="text-3xl font-black text-stone-900 dark:text-stone-100 tabular-nums tracking-tight mb-0">
                        {{ $collaborationInvitesCount }}
                    </h3>
                    <span class="text-xs font-semibold text-stone-500 dark:text-stone-400">
                        Permintaan
                    </span>
                </div>
                <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-bell text-sky-500"></i>
                    <span>Cek berkala untuk rekrutmen baru</span>
                </p>
            </div>

        </div>

        <!-- ================= 3. MAIN WORKSPACE + SIDEBAR ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT AREA: Projects & Recommendations (8 cols) -->
            <div class="lg:col-span-8 space-y-8">
                
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-bold text-stone-900 dark:text-stone-100 mb-0">
                            Proyek Aktif
                        </h2>
                        <a href="{{ route('projects.index') }}"
                           class="text-xs font-medium text-teal-600 dark:text-teal-400 hover:text-teal-700 no-underline">
                            Lihat Semua →
                        </a>
                    </div>

                    <!-- Active Project Cards Grid -->
                    <div class="space-y-3">
                        @forelse($activeProjects as $project)
                            @php
                                $totalTasks = $project->tasks?->count() ?? 10;
                                $doneTasks = $project->tasks?->where('status', 'done')->count() ?? 6;
                                $progressPercent = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 60;
                            @endphp
                            <div class="bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 p-4 hover:border-stone-300 dark:hover:border-stone-700 transition-all">
                                <div class="flex items-center justify-between gap-4 mb-3">
                                    <div class="flex items-start gap-3.5 min-w-0">
                                        <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 flex items-center justify-center font-bold text-base shrink-0">
                                            {{ strtoupper(substr($project->title, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                                <h3 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">
                                                    {{ $project->title }}
                                                </h3>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-stone-500 dark:text-stone-400 mb-0 line-clamp-1">
                                                {{ $project->description ?? 'Tidak ada deskripsi singkat.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Quick Sprint Date Badge -->
                                    @if($project->end_date)
                                        <span class="text-xs font-medium text-stone-500 dark:text-stone-400 shrink-0">
                                            <i class="bi bi-clock text-[10px]"></i>
                                            {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('d M') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Progress Bar & Squad Meta -->
                                <div class="space-y-2 pt-3 border-t border-stone-100 dark:border-stone-800">
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-stone-500 dark:text-stone-400">Progress</span>
                                        <span class="font-semibold text-stone-900 dark:text-stone-100">{{ $progressPercent }}%</span>
                                    </div>
                                    <div class="w-full bg-stone-100 dark:bg-stone-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-teal-500 h-1.5 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <div class="flex items-center gap-2">
                                            <div class="flex -space-x-1.5">
                                                @foreach($project->members->take(3) as $m)
                                                    <img src="{{ $m->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($m->name) . '&background=random' }}"
                                                         title="{{ $m->name }}"
                                                         class="w-6 h-6 rounded-full border-2 border-white dark:border-[#141414] object-cover">
                                                @endforeach
                                            </div>
                                            <span class="text-[10px] text-stone-500 dark:text-stone-400">
                                                {{ $project->members->count() }} anggota
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            @if($project->conversation)
                                                <a href="{{ route('chat', $project->conversation) }}"
                                                   class="px-2.5 py-1 rounded-lg text-xs font-medium bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 hover:bg-teal-100 transition-colors no-underline">
                                                    Chat
                                                </a>
                                            @endif
                                            <a href="{{ route('projects.show', $project) }}"
                                               class="px-2.5 py-1 rounded-lg text-xs font-medium bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300 hover:bg-stone-200 dark:hover:bg-stone-700 transition-colors no-underline">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-stone-50 dark:bg-stone-900/50 rounded-xl border border-dashed border-stone-300 dark:border-stone-700 p-8 text-center">
                                <div class="w-12 h-12 rounded-lg bg-stone-100 dark:bg-stone-800 text-stone-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <h4 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-1">Belum Ada Proyek Aktif</h4>
                                <p class="text-xs text-stone-500 dark:text-stone-400 mb-3">Mulai proyek baru atau bergabung dengan tim lain.</p>
                                <a href="{{ route('projects.create') }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-teal-600 hover:bg-teal-500 text-white transition-colors no-underline">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Buat Proyek</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Section: Rekomendasi Kolaborasi Tim -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900 dark:text-stone-100 tracking-tight mb-0">
                                Rekomendasi Proyek Terbuka
                            </h2>
                            <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">Proyek dari kampus lain yang membutuhkan kontributor baru.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
                        @forelse($recommendedProjects as $rec)
                            <div class="bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 p-4 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-stone-100 dark:bg-[#2e2c29]/50 text-stone-600 dark:text-stone-300 border border-stone-200 dark:border-stone-800">
                                            {{ $rec->category ?? 'Tech' }}
                                        </span>
                                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                            <i class="bi bi-stars"></i> 95% Match
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-stone-900 dark:text-stone-100 mb-1 line-clamp-1">
                                            {{ $rec->title }}
                                        </h4>
                                        <p class="text-xs text-stone-500 dark:text-stone-400 line-clamp-2 mb-0">
                                            {{ $rec->description ?? 'Proyek kolaboratif antar mahasiswa untuk portofolio siap kerja.' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-3 border-t border-stone-100 dark:border-stone-800/50">
                                    <div class="flex items-center justify-between text-xs text-stone-500 dark:text-stone-400">
                                        <span>Owner:</span>
                                        <span class="font-medium text-stone-800 dark:text-stone-200 truncate max-w-[120px]">
                                            {{ $rec->owner?->name ?? 'Mahasiswa' }}
                                        </span>
                                    </div>
                                    <a href="{{ route('projects.show', $rec) }}"
                                       class="block text-center w-full py-2 px-3 rounded-xl text-xs font-bold bg-teal-600 hover:bg-teal-700 text-white transition-all duration-150 active:scale-[0.98] no-underline">
                                        Lihat & Ajukan Gabung
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-6 text-stone-400 text-sm">
                                Tidak ada rekomendasi proyek baru saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- RIGHT AREA: Deadlines & Live Stream (4 cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Upcoming Deadlines & Calendar Sync -->
                <div class="bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 p-4 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 dark:border-stone-800/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                            <h3 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Tenggat Mendatang</h3>
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
                            <div class="p-3 rounded-xl border-l-4 {{ $urgencyClass }} bg-stone-50 dark:bg-[#2e2c29]/30 transition-colors">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wide">
                                        {{ \Carbon\Carbon::parse($deadline->end_date)->translatedFormat('d M, H:i') }}
                                    </span>
                                    <span class="text-[10px] font-bold">
                                        {{ $diffDays <= 0 ? 'Hari Ini' : ($diffDays == 1 ? 'Besok' : $diffDays . ' Hari Lagi') }}
                                    </span>
                                </div>
                                <h4 class="text-xs font-bold text-stone-900 dark:text-stone-100 mb-0 truncate">
                                    {{ $deadline->title }}
                                </h4>
                            </div>
                        @empty
                            <div class="text-center py-6 text-stone-400 dark:text-stone-500 text-xs">
                                <i class="bi bi-calendar-check text-2xl block mb-1 text-stone-300 dark:text-stone-600"></i>
                                Tidak ada tenggat mendesak minggu ini.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('projects.index') }}"
                       class="block text-center w-full py-2.5 rounded-xl text-xs font-bold bg-stone-100 hover:bg-stone-200 dark:bg-[#2e2c29]/60 dark:hover:bg-[#2e2c29] text-stone-700 dark:text-stone-200 transition-colors no-underline">
                        Lihat Seluruh Kalender Tugas
                    </a>
                </div>

                <!-- Notifications & Real-Time Activities Stream -->
                <div class="bg-white dark:bg-[#141414] rounded-xl border border-stone-200 dark:border-stone-800 p-4 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 dark:border-stone-800/50">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center text-sm font-bold">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <h3 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Aktivitas Terkini</h3>
                        </div>
                        <a href="{{ route('chat') }}" class="text-[11px] font-bold text-teal-600 dark:text-teal-400 hover:underline no-underline">
                            Buka Chat
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->data['link'] ?? '#' }}"
                               class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-stone-50 dark:hover:bg-[#2e2c29]/40 transition-colors no-underline group">
                                <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                    <i class="bi bi-chat-left-text"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-stone-800 dark:text-stone-200 mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 font-medium line-clamp-2">
                                        {{ $notif->data['message'] ?? 'Pesan baru di workspace.' }}
                                    </p>
                                    <span class="text-[10px] text-stone-400 dark:text-stone-500 block tabular-nums">
                                        {{ $notif->created_at ? \Carbon\Carbon::parse($notif->created_at)->diffForHumans() : 'Baru saja' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-stone-400 dark:text-stone-500 text-xs">
                                <i class="bi bi-chat-square-dots text-2xl block mb-1 text-stone-300 dark:text-stone-600"></i>
                                Belum ada pesan atau notifikasi baru.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
