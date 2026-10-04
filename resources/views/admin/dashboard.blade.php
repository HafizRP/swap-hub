<x-app-layout>
    @section('title', 'Admin Dashboard')
 
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Stats Cards -->
        <!-- Card 1 -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-black text-stone-900 dark:text-stone-100 mb-1">{{ number_format($stats['total_users']) }}</h3>
                <small class="text-emerald-500 font-bold text-xs flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +{{ $stats['new_users_today'] }} today
                </small>
            </div>
            <div class="rounded-xl p-3 bg-teal-500/10 text-teal-600 dark:text-teal-400">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 2 -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Total Projects</p>
                <h3 class="text-2xl font-black text-stone-900 dark:text-stone-100 mb-1">{{ number_format($stats['total_projects']) }}</h3>
                <small class="text-emerald-500 font-bold text-xs flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +{{ $stats['new_projects_today'] }} today
                </small>
            </div>
            <div class="rounded-xl p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-450">
                <i class="bi bi-folder-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 3 -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Active Projects</p>
                <h3 class="text-2xl font-black text-stone-900 dark:text-stone-100 mb-1">{{ number_format($stats['active_projects']) }}</h3>
                <small class="text-stone-400 dark:text-stone-500 text-xs">
                    {{ round(($stats['active_projects'] / max($stats['total_projects'], 1)) * 100) }}% of total
                </small>
            </div>
            <div class="rounded-xl p-3 bg-amber-500/10 text-amber-500">
                <i class="bi bi-lightning-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 4 -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Total Messages</p>
                <h3 class="text-2xl font-black text-stone-900 dark:text-stone-100 mb-1">{{ number_format($stats['total_messages']) }}</h3>
                <small class="text-stone-400 dark:text-stone-500 text-xs">Engagement</small>
            </div>
            <div class="rounded-xl p-3 bg-sky-500/10 text-sky-600 dark:text-sky-400">
                <i class="bi bi-chat-dots-fill text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Domain Specific Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Kredit Beredar</p>
                <h3 class="text-2xl font-black text-amber-500 mb-1">{{ number_format($stats['total_credits_circulating']) }}</h3>
                <small class="text-stone-400 dark:text-stone-500 text-xs">Saldo token mahasiswa</small>
            </div>
            <div class="rounded-xl p-3 bg-amber-500/10 text-amber-500">
                <i class="bi bi-coin text-xl"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Skill Swaps</p>
                <h3 class="text-2xl font-black text-teal-600 dark:text-teal-400 mb-1">{{ number_format($stats['total_swaps']) }}</h3>
                <small class="text-stone-400 dark:text-stone-500 text-xs">Pertukaran 1-on-1</small>
            </div>
            <div class="rounded-xl p-3 bg-teal-500/10 text-teal-600">
                <i class="bi bi-arrow-left-right text-xl"></i>
            </div>
        </div>
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 flex justify-between items-start">
            <div>
                <p class="text-stone-400 dark:text-stone-500 mb-1 text-xs font-bold uppercase tracking-wider">Review Bounties Aktif</p>
                <h3 class="text-2xl font-black text-sky-500 mb-1">{{ number_format($stats['pending_reviews']) }}</h3>
                <small class="text-stone-400 dark:text-stone-500 text-xs">Open / In Review</small>
            </div>
            <div class="rounded-xl p-3 bg-sky-500/10 text-sky-600">
                <i class="bi bi-code-slash text-xl"></i>
            </div>
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6">
        <!-- User Growth Chart -->
        <div class="lg:col-span-8 bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4">
            <div class="flex items-center gap-2 mb-4">
                <i class="bi bi-graph-up text-teal-600 dark:text-teal-400 text-lg"></i>
                <h5 class="font-bold text-stone-900 dark:text-stone-100 text-base mb-0">User Growth (Last 7 Days)</h5>
            </div>
            <div>
                <canvas id="userGrowthChart" height="80"></canvas>
            </div>
        </div>
 
        <!-- Quick Stats -->
        <div class="lg:col-span-4 bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4">
            <div class="flex items-center gap-2 mb-6">
                <i class="bi bi-speedometer text-teal-600 dark:text-teal-400 text-lg"></i>
                <h5 class="font-bold text-stone-900 dark:text-stone-100 text-base mb-0">Quick Stats</h5>
            </div>
            
            <div class="flex flex-col gap-4">
                <div class="flex justify-between items-center pb-3 border-b border-stone-100 dark:border-stone-800/50">
                    <span class="text-stone-500 dark:text-stone-400 text-sm flex items-center gap-2">
                        <i class="bi bi-shield-fill text-red-500"></i> Admins
                    </span>
                    <span class="bg-red-500/10 text-red-600 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['total_admins'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-stone-100 dark:border-stone-800/50">
                    <span class="text-stone-500 dark:text-stone-400 text-sm flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500"></i> Completed Projects
                    </span>
                    <span class="bg-emerald-500/10 text-emerald-600 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['completed_projects'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-stone-500 dark:text-stone-400 text-sm flex items-center gap-2">
                        <i class="bi bi-person-plus-fill text-teal-600"></i> New Users Today
                    </span>
                    <span class="bg-teal-500/10 text-teal-600 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['new_users_today'] }}</span>
                </div>
            </div>
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800/50 flex justify-between items-center">
                <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0 flex items-center gap-2">
                    <i class="bi bi-people text-teal-600"></i> Recent Users
                </h5>
                <a href="{{ route('admin.users.index') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-950/20 font-bold py-1 px-3 rounded-full text-xs transition-colors no-underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-[#2e2c29]/30">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">User</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Email</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Role</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-[#2e2c29]/50">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-[#2e2c29]/20 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="rounded-circle" width="32" height="32">
                                        <span class="text-sm font-semibold text-stone-800 dark:text-stone-100">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-300">{{ $user->email }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-stone-100 dark:bg-[#2e2c29] text-stone-600 dark:text-stone-300' }}">
                                        {{ $user->role->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400 dark:text-stone-500">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-stone-400 dark:text-stone-500 py-6 text-sm">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
 
        <!-- Recent Projects -->
        <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800/50 flex justify-between items-center">
                <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0 flex items-center gap-2">
                    <i class="bi bi-folder text-teal-600"></i> Recent Projects
                </h5>
                <a href="{{ route('admin.projects.index') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-950/20 font-bold py-1 px-3 rounded-full text-xs transition-colors no-underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-[#2e2c29]/30">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Project</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Owner</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-[#2e2c29]/50">
                        @forelse($recentProjects as $project)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-[#2e2c29]/20 transition-colors">
                                <td class="px-6 py-3.5 text-sm font-semibold text-stone-800 dark:text-stone-100">{{ Str::limit($project->title, 30) }}</td>
                                <td class="px-6 py-3.5 text-xs text-stone-600 dark:text-stone-300">{{ $project->owner->name }}</td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $statusClass = $project->status === 'active' 
                                            ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                            : ($project->status === 'completed' ? 'bg-teal-500/10 text-teal-600 dark:text-teal-400' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-stone-500/10 text-stone-500 dark:text-stone-400'));
                                    @endphp
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-stone-400 dark:text-stone-500">{{ $project->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-stone-400 dark:text-stone-500 py-6 text-sm">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Audit Trail -->
    <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4 mb-6">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-stone-100 dark:border-stone-800">
            <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0 flex items-center gap-2">
                <i class="bi bi-shield-check text-emerald-500"></i>Aktivitas Admin Terkini (Audit Trail)
            </h5>
            <a href="{{ route('admin.audit-logs.index') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-50 dark:hover:bg-teal-950/20 font-bold py-1 px-3 rounded-full text-xs transition-colors no-underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse align-middle text-nowrap">
                <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                    <tr>
                        <th class="px-4 py-2.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Waktu</th>
                        <th class="px-4 py-2.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Admin</th>
                        <th class="px-4 py-2.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                        <th class="px-4 py-2.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                    @forelse($recentAuditLogs as $log)
                        <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40">
                            <td class="px-4 py-2.5 text-xs text-stone-500">{{ $log->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-2.5 text-xs font-bold text-stone-900 dark:text-stone-100">{{ $log->admin?->name ?? 'System' }}</td>
                            <td class="px-4 py-2.5 text-xs font-mono font-bold text-stone-700 dark:text-stone-300">{{ $log->action }}</td>
                            <td class="px-4 py-2.5 text-xs text-stone-500">
                                {{ $log->target_type ? class_basename($log->target_type).' #'.$log->target_id : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-xs text-stone-400">Belum ada aktivitas admin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
 
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('userGrowthChart');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($userGrowth, 'date')) !!},
                    datasets: [{
                        label: 'New Users',
                        data: {!! json_encode(array_column($userGrowth, 'count')) !!},
                        borderColor: 'rgb(13, 148, 136)',
                        backgroundColor: 'rgba(13, 148, 136, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>