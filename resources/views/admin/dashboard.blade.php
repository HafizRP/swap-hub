<x-app-layout>
    @section('title', 'Admin Dashboard')
 
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Stats Cards -->
        <!-- Card 1 -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-start">
            <div>
                <p class="text-slate-455 text-slate-400 mb-1 text-xs font-bold uppercase tracking-wider">Total Users</p>
                <h3 class="text-2xl font-black text-slate-850 dark:text-slate-100 mb-1">{{ number_format($stats['total_users']) }}</h3>
                <small class="text-emerald-500 font-bold text-xs flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +{{ $stats['new_users_today'] }} today
                </small>
            </div>
            <div class="rounded-xl p-3 bg-indigo-500/10 text-indigo-650 dark:text-indigo-400">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 2 -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-start">
            <div>
                <p class="text-slate-450 text-slate-400 mb-1 text-xs font-bold uppercase tracking-wider">Total Projects</p>
                <h3 class="text-2xl font-black text-slate-850 dark:text-slate-100 mb-1">{{ number_format($stats['total_projects']) }}</h3>
                <small class="text-emerald-500 font-bold text-xs flex items-center gap-1">
                    <i class="bi bi-arrow-up"></i> +{{ $stats['new_projects_today'] }} today
                </small>
            </div>
            <div class="rounded-xl p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-450">
                <i class="bi bi-folder-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 3 -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-start">
            <div>
                <p class="text-slate-450 text-slate-400 mb-1 text-xs font-bold uppercase tracking-wider">Active Projects</p>
                <h3 class="text-2xl font-black text-slate-850 dark:text-slate-100 mb-1">{{ number_format($stats['active_projects']) }}</h3>
                <small class="text-slate-400 dark:text-slate-500 text-xs">
                    {{ round(($stats['active_projects'] / max($stats['total_projects'], 1)) * 100) }}% of total
                </small>
            </div>
            <div class="rounded-xl p-3 bg-amber-500/10 text-amber-500">
                <i class="bi bi-lightning-fill text-xl"></i>
            </div>
        </div>
 
        <!-- Card 4 -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-start">
            <div>
                <p class="text-slate-450 text-slate-400 mb-1 text-xs font-bold uppercase tracking-wider">Total Messages</p>
                <h3 class="text-2xl font-black text-slate-850 dark:text-slate-100 mb-1">{{ number_format($stats['total_messages']) }}</h3>
                <small class="text-slate-400 dark:text-slate-500 text-xs">Engagement</small>
            </div>
            <div class="rounded-xl p-3 bg-sky-500/10 text-sky-600 dark:text-sky-400">
                <i class="bi bi-chat-dots-fill text-xl"></i>
            </div>
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        <!-- User Growth Chart -->
        <div class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="bi bi-graph-up text-indigo-650 dark:text-indigo-400 text-lg"></i>
                <h5 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-0">User Growth (Last 7 Days)</h5>
            </div>
            <div>
                <canvas id="userGrowthChart" height="80"></canvas>
            </div>
        </div>
 
        <!-- Quick Stats -->
        <div class="lg:col-span-4 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center gap-2 mb-6">
                <i class="bi bi-speedometer text-indigo-650 dark:text-indigo-400 text-lg"></i>
                <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base mb-0">Quick Stats</h5>
            </div>
            
            <div class="flex flex-col gap-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 text-sm flex items-center gap-2">
                        <i class="bi bi-shield-fill text-red-500"></i> Admins
                    </span>
                    <span class="bg-red-500/10 text-red-600 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['total_admins'] }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50">
                    <span class="text-slate-500 dark:text-slate-400 text-sm flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-500"></i> Completed Projects
                    </span>
                    <span class="bg-emerald-500/10 text-emerald-600 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['completed_projects'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 dark:text-slate-400 text-sm flex items-center gap-2">
                        <i class="bi bi-person-plus-fill text-indigo-600"></i> New Users Today
                    </span>
                    <span class="bg-indigo-500/10 text-indigo-650 rounded-full px-2.5 py-0.5 text-xs font-bold">{{ $stats['new_users_today'] }}</span>
                </div>
            </div>
        </div>
    </div>
 
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
                <h5 class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0 flex items-center gap-2">
                    <i class="bi bi-people text-indigo-650"></i> Recent Users
                </h5>
                <a href="{{ route('admin.users.index') }}" class="border border-indigo-650 text-indigo-655 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 font-bold py-1 px-3 rounded-full text-xs transition-colors no-underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-700/30">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">User</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Email</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Role</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750/30 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="rounded-circle" width="32" height="32">
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-655 dark:text-slate-300">{{ $user->email }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $user->role && $user->role->slug === 'admin' ? 'bg-red-500/10 text-red-500' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">
                                        {{ $user->role->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-400 dark:text-slate-500">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 dark:text-slate-500 py-6 text-sm">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
 
        <!-- Recent Projects -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
                <h5 class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0 flex items-center gap-2">
                    <i class="bi bi-folder text-indigo-650"></i> Recent Projects
                </h5>
                <a href="{{ route('admin.projects.index') }}" class="border border-indigo-650 text-indigo-655 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 font-bold py-1 px-3 rounded-full text-xs transition-colors no-underline">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-700/30">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Project</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Owner</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($recentProjects as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750/30 transition-colors">
                                <td class="px-6 py-3.5 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ Str::limit($project->title, 30) }}</td>
                                <td class="px-6 py-3.5 text-xs text-slate-655 dark:text-slate-300">{{ $project->owner->name }}</td>
                                <td class="px-6 py-3.5">
                                    @php
                                        $statusClass = $project->status === 'active' 
                                            ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                            : ($project->status === 'completed' ? 'bg-indigo-500/10 text-indigo-655 dark:text-indigo-400' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-slate-500/10 text-slate-500 dark:text-slate-400'));
                                    @endphp
                                    <span class="rounded px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $statusClass }}">
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-400 dark:text-slate-500">{{ $project->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 dark:text-slate-500 py-6 text-sm">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
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