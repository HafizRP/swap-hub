<x-app-layout>
    @section('title', 'Admin Dashboard')

    <div class="row g-4 mb-4">
        <!-- Stats Cards -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-1 small">Total Users</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_users']) }}</h3>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> +{{ $stats['new_users_today'] }}
                                today</small>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10">
                            <i class="bi bi-people-fill fs-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-1 small">Total Projects</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_projects']) }}</h3>
                            <small class="text-success"><i class="bi bi-arrow-up"></i>
                                +{{ $stats['new_projects_today'] }} today</small>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10">
                            <i class="bi bi-folder-fill fs-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-1 small">Active Projects</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['active_projects']) }}</h3>
                            <small
                                class="text-secondary">{{ round(($stats['active_projects'] / max($stats['total_projects'], 1)) * 100) }}%
                                of total</small>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10">
                            <i class="bi bi-lightning-fill fs-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary mb-1 small">Total Messages</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_messages']) }}</h3>
                            <small class="text-secondary">Engagement</small>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10">
                            <i class="bi bi-chat-dots-fill fs-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- User Growth Chart -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up me-2 text-primary"></i>User Growth (Last 7 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="userGrowthChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-speedometer me-2 text-primary"></i>Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div
                        class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-white border-opacity-10">
                        <span class="text-secondary"><i class="bi bi-shield-fill me-2"></i>Admins</span>
                        <span class="badge bg-danger">{{ $stats['total_admins'] }}</span>
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-white border-opacity-10">
                        <span class="text-secondary"><i class="bi bi-check-circle-fill me-2"></i>Completed
                            Projects</span>
                        <span class="badge bg-success">{{ $stats['completed_projects'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary"><i class="bi bi-person-plus-fill me-2"></i>New Users Today</span>
                        <span class="badge bg-primary">{{ $stats['new_users_today'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Recent Users -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Recent Users</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-bottom border-white border-opacity-10">
                                    <th class="text-secondary fw-bold small text-uppercase">User</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Email</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Role</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                                    class="rounded-circle me-2" width="32" height="32">
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-folder me-2 text-primary"></i>Recent Projects</h5>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-bottom border-white border-opacity-10">
                                    <th class="text-secondary fw-bold small text-uppercase">Project</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Owner</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Status</th>
                                    <th class="text-secondary fw-bold small text-uppercase">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProjects as $project)
                                    <tr>
                                        <td>{{ Str::limit($project->title, 30) }}</td>
                                        <td>{{ $project->owner->name }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($project->status === 'active') bg-success
                                                @elseif($project->status === 'completed') bg-primary
                                                @elseif($project->status === 'planning') bg-warning
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $project->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">No projects found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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