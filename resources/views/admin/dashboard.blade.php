<x-app-layout>
    @section('title', 'Admin Dashboard')

    <div class="space-y-6">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Admin Command Center
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Ringkasan analitik pertumbuhan pengguna, proyek kolaborasi, dan operasional sistem.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold transition-colors">
                    <i class="bi bi-people-fill mr-1"></i> Kelola Mahasiswa
                </a>
                <a href="{{ route('admin.projects.index') }}" class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-colors">
                    <i class="bi bi-folder-fill mr-1"></i> Moderasi Proyek
                </a>
            </div>
        </div>

        <!-- Real-Time System Health Monitor -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            @livewire('system-health')
        </div>

        <!-- Bento Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1 -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Pengguna</span>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_users']) }}</h3>
                    <span class="text-emerald-500 text-[11px] font-bold flex items-center gap-1">
                        <i class="bi bi-arrow-up-short text-sm"></i> +{{ $stats['new_users_today'] }} hari ini
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-950/50 text-brand-600 flex items-center justify-center text-xl">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Total Proyek</span>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_projects']) }}</h3>
                    <span class="text-emerald-500 text-[11px] font-bold flex items-center gap-1">
                        <i class="bi bi-arrow-up-short text-sm"></i> +{{ $stats['new_projects_today'] }} hari ini
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="bi bi-folder-fill"></i>
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Proyek Aktif</span>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['active_projects']) }}</h3>
                    <span class="text-slate-400 text-[11px] font-semibold">
                        {{ round(($stats['active_projects'] / max($stats['total_projects'], 1)) * 100) }}% aktif
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div class="space-y-1">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Aktivitas Pesan</span>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($stats['total_messages']) }}</h3>
                    <span class="text-brand-600 text-[11px] font-semibold">Interaksi Tim</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center text-xl">
                    <i class="bi bi-chat-square-dots-fill"></i>
                </div>
            </div>
        </div>

        <!-- Chart & Quick Summary Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- User Growth Chart -->
            <div class="lg:col-span-8 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-graph-up text-brand-600"></i>
                        <span>Tren Pertumbuhan Mahasiswa (7 Hari Terakhir)</span>
                    </h3>
                </div>
                <div class="h-64">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>

            <!-- Quick Roles & Stats -->
            <div class="lg:col-span-4 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4 flex flex-col justify-between">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-brand-600"></i>
                    <span>Ikhtisar Akun</span>
                </h3>

                <div class="space-y-3 divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-2">
                            <i class="bi bi-shield-shaded text-rose-500"></i> Administrator
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300">
                            {{ $stats['total_admins'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-2">
                            <i class="bi bi-check-circle-fill text-emerald-500"></i> Proyek Selesai
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                            {{ $stats['completed_projects'] }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-2">
                            <i class="bi bi-person-plus-fill text-brand-600"></i> Registrasi Hari Ini
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                            {{ $stats['new_users_today'] }}
                        </span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors">
                        Buka Manajemen Pengguna
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Tables Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-people text-brand-600"></i>
                        <span>Mahasiswa Terbaru</span>
                    </h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase font-extrabold text-[10px] border-b border-slate-100 dark:border-slate-800">
                                <th class="pb-2">Nama</th>
                                <th class="pb-2">Peran</th>
                                <th class="pb-2 text-right">Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentUsers as $user)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 flex items-center gap-2 font-bold text-slate-900 dark:text-white">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                             alt="{{ $user->name }}" class="w-6 h-6 rounded-lg object-cover">
                                        <span class="truncate max-w-[140px]">{{ $user->name }}</span>
                                    </td>
                                    <td class="py-2.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-rose-500/10 text-rose-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                                            {{ $user->role->name ?? 'Student' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 text-right text-slate-400 text-[11px]">
                                        {{ $user->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-slate-400">Tidak ada data mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bi bi-folder text-brand-600"></i>
                        <span>Proyek Terbaru</span>
                    </h3>
                    <a href="{{ route('admin.projects.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase font-extrabold text-[10px] border-b border-slate-100 dark:border-slate-800">
                                <th class="pb-2">Proyek</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2 text-right">Dibuat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentProjects as $project)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-2.5 font-bold text-slate-900 dark:text-white truncate max-w-[150px]">
                                        {{ $project->title }}
                                    </td>
                                    <td class="py-2.5">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $project->status === 'active' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }}">
                                            {{ $project->status }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 text-right text-slate-400 text-[11px]">
                                        {{ $project->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-slate-400">Tidak ada data proyek.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('userGrowthChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_column($userGrowth, 'date')) !!},
                        datasets: [{
                            label: 'Mahasiswa Baru',
                            data: {!! json_encode(array_column($userGrowth, 'count')) !!},
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.12)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#6366f1',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(156, 163, 175, 0.1)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
