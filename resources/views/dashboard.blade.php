@section('title', 'Dashboard')
<x-app-layout>
    <div class="space-y-8">

        <!-- Header & Quick Action -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    Halo, {{ explode(' ', $user->name)[0] }}! 👋
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Berikut ringkasan aktivitas proyek, reputasi, dan tugas kolaborasi Anda hari ini.
                </p>
            </div>
            <div>
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-md shadow-brand-500/20 transition-all">
                    <i class="bi bi-plus-lg text-sm"></i>
                    <span>Buat Proyek Baru</span>
                </a>
            </div>
        </div>

        <!-- 4-Column Bento Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Stat 1: Total Reputation -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Reputasi</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($user->reputation_points) }} <span class="text-xs font-semibold text-slate-400">XP</span></h3>
                    <div class="flex items-center gap-1.5 mt-1.5 text-xs text-amber-600 dark:text-amber-400 font-semibold">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Elite Contributor</span>
                    </div>
                </div>
            </div>

            <!-- Stat 2: Completed Projects -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Proyek Selesai</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $completedProjectsCount }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Portfolio Verified</p>
                </div>
            </div>

            <!-- Stat 3: Collaboration Invites -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Undangan Masuk</span>
                    <div class="w-10 h-10 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg">
                        <i class="bi bi-envelope-open-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $collaborationInvitesCount }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Permintaan Kolaborasi</p>
                </div>
            </div>

            <!-- Stat 4: Active Squads -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Proyek Aktif</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg">
                        <i class="bi bi-kanban-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ $activeProjects->count() }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sedang Berjalan</p>
                </div>
            </div>
        </div>

        <!-- Main Grid: Active Projects & Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

            <!-- Left Main Column (8 cols) -->
            <div class="lg:col-span-8 space-y-8">

                <!-- Active Projects Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Proyek Aktif Saya</h2>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                                {{ $activeProjects->count() }}
                            </span>
                        </div>
                        <a href="{{ route('projects.index') }}" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">
                            Lihat Semua Proyek <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($activeProjects as $project)
                            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-11 h-11 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center font-black text-base shrink-0">
                                            {{ substr($project->title, 0, 2) }}
                                        </div>
                                        <div class="space-y-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-base font-bold text-slate-900 dark:text-white truncate">
                                                    {{ $project->title }}
                                                </h3>
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                                {{ $project->description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 sm:self-center shrink-0">
                                        @if($project->conversation)
                                            <a href="{{ route('chat', $project->conversation) }}"
                                               class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold transition-colors"
                                               title="Buka Chat Tim">
                                                <i class="bi bi-chat-dots-fill"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold transition-colors">
                                            Detail
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-400">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-slate-600 dark:text-slate-300">{{ $project->category ?? 'General' }}</span>
                                        <span>•</span>
                                        <span>Peran: <strong class="text-slate-700 dark:text-slate-200">{{ ucfirst($project->pivot?->role ?? 'Member') }}</strong></span>
                                    </div>
                                    @if($project->end_date)
                                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                            <i class="bi bi-calendar-event"></i>
                                            <span>Deadline: {{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 rounded-2xl bg-white dark:bg-slate-900 border border-dashed border-slate-200 dark:border-slate-800 text-center space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto text-xl">
                                    <i class="bi bi-folder-x"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum ada proyek aktif</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                                    Temukan tim untuk diajak kolaborasi atau buat proyek idamanmu sendiri sekarang.
                                </p>
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all">
                                    <i class="bi bi-search"></i>
                                    <span>Eksplorasi Proyek</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Rekomendasi Proyek untuk Anda</h2>
                        <a href="{{ route('projects.index') }}" class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline">
                            Eksplorasi Katalog
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($recommendedProjects as $recProject)
                            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover-card flex flex-col justify-between">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                            {{ $recProject->category ?? 'General' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $recProject->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate" title="{{ $recProject->title }}">
                                        {{ $recProject->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                        {{ $recProject->description }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                                    <span class="text-xs font-medium text-slate-400">
                                        {{ $recProject->members->count() }} Anggota
                                    </span>
                                    <a href="{{ route('projects.show', $recProject) }}"
                                       class="px-3.5 py-1.5 rounded-xl bg-brand-50 hover:bg-brand-100 dark:bg-brand-950/40 dark:hover:bg-brand-900/50 text-brand-700 dark:text-brand-300 text-xs font-bold transition-colors">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-center text-slate-400 text-xs">
                                Tidak ada rekomendasi proyek baru saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Notifications & Deadlines (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Notifications Card -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-bell-fill text-brand-500"></i>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pemberitahuan</h3>
                        </div>
                        <span class="text-[11px] font-bold text-brand-600 dark:text-brand-400 cursor-pointer">Tandai Dibaca</span>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->data['link'] ?? '#' }}"
                               class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                                <img src="{{ $notif->data['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(substr($notif->data['message'] ?? 'S', 0, 1)) . '&background=6366f1&color=fff' }}"
                                     class="w-8 h-8 rounded-lg object-cover shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs text-slate-800 dark:text-slate-200 font-medium leading-tight truncate">
                                        {{ $notif->data['message'] ?? 'Notifikasi baru' }}
                                    </p>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">
                                        {{ $notif->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="py-4 text-center text-slate-400 text-xs">
                                Tidak ada notifikasi baru.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Deadlines Card -->
                <div class="p-6 rounded-2xl bg-gradient-to-br from-brand-600 to-indigo-800 text-white shadow-xl shadow-brand-500/10 space-y-4 relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar3 text-brand-200"></i>
                            <h3 class="text-sm font-bold">Tenggat Waktu Dekat</h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white">
                            Google Sync
                        </span>
                    </div>

                    <div class="space-y-2.5 relative z-10">
                        @forelse($upcomingDeadlines as $deadline)
                            <div class="p-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 space-y-1">
                                <div class="flex items-center gap-1.5 text-[10px] text-amber-300 font-semibold">
                                    <i class="bi bi-clock-fill"></i>
                                    <span>{{ \Carbon\Carbon::parse($deadline->end_date)->format('d M, H:i') }}</span>
                                </div>
                                <h4 class="text-xs font-bold text-white truncate">{{ $deadline->title }}</h4>
                            </div>
                        @empty
                            <div class="p-3 rounded-xl bg-white/10 text-xs text-brand-100 text-center">
                                Tidak ada tenggat waktu dalam waktu dekat.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
