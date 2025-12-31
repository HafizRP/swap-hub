@section('title', 'Dashboard')
<x-app-layout>
    <div class="container py-5">
        <div class="row g-4 justify-content-center">

            <!-- LEFT COLUMN: Main Content (8 cols) -->
            <div class="col-lg-8">

                <!-- 1. Header & Welcome -->
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="display-6 fw-bold mb-1">Selamat Datang, {{ explode(' ', $user->name)[0] }} 👋</h2>
                        <p class="text-secondary mb-0">Here is what's happening with your projects today.</p>
                    </div>
                    <div>
                        <a href="{{ route('projects.create') }}"
                            class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Project
                        </a>
                    </div>
                </div>

                <!-- 2. Stats Row -->
                <div class="row g-3 mb-5">
                    <!-- Reputation -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 start-card">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-warning"
                                    style="width: 56px; height: 56px;">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-secondary text-uppercase fw-bold small mb-0"
                                        style="font-size: 10px; letter-spacing: 0.5px;">Total Reputation</p>
                                    <h4 class="fw-black mb-0">{{ number_format($user->reputation_points) }}</h4>
                                    <span class="text-success small fw-bold" style="font-size: 11px;">Elite
                                        Member</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 start-card">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-primary"
                                    style="width: 56px; height: 56px;">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-secondary text-uppercase fw-bold small mb-0"
                                        style="font-size: 10px; letter-spacing: 0.5px;">Projects Done</p>
                                    <h4 class="fw-black mb-0">{{ $completedProjectsCount }}</h4>
                                    <span class="text-secondary small" style="font-size: 11px;">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invites -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 start-card">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center text-info"
                                    style="width: 56px; height: 56px;">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-secondary text-uppercase fw-bold small mb-0"
                                        style="font-size: 10px; letter-spacing: 0.5px;">Invites</p>
                                    <h4 class="fw-black mb-0">{{ $collaborationInvitesCount }}</h4>
                                    <span class="text-secondary small" style="font-size: 11px;">New Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Active Projects -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Active Projects</h5>
                    <a href="{{ route('projects.index') }}" class="text-decoration-none fw-bold small">View All</a>
                </div>

                <div class="row g-3 mb-5">
                    @forelse($activeProjects as $project)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm p-3 hover-scale transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Icon / Letter -->
                                    <div class="rounded-3 bg-{{ ['primary', 'warning', 'success', 'danger', 'info'][rand(0, 4)] }} bg-opacity-10 p-3 d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <span
                                            class="fw-black fs-5 text-{{ ['primary', 'warning', 'success', 'danger', 'info'][rand(0, 4)] }}">{{ substr($project->title, 0, 1) }}</span>
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="fw-bold mb-0">{{ $project->title }}</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-2 py-1"
                                                    style="font-size: 10px;">{{ ucfirst($project->status) }}</span>
                                                @if($project->end_date)
                                                    <span
                                                        class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill small px-2 py-1"
                                                        style="font-size: 10px;">Due:
                                                        {{ \Carbon\Carbon::parse($project->end_date)->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-secondary small" style="font-size: 11px;">Role:
                                                {{ ucfirst($project->pivot?->role ?? 'Member') }}</span>
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="text-decoration-none small fw-bold d-flex align-items-center">
                                                Details <svg width="14" height="14" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" class="ms-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <!-- Fake Progress Bar -->
                                        <div class="progress mt-2" style="height: 4px;">
                                            @php $prog = rand(20, 90); @endphp
                                            <div class="progress-bar rounded-pill" role="progressbar"
                                                style="width: {{ $prog }}%" aria-valuenow="{{ $prog }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 border-dashed p-5 text-center bg-transparent">
                                <p class="text-secondary mb-0">You have no active projects currently.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- 4. Recommendations -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Project Recommendations</h5>
                    <div class="d-flex gap-2">
                        <button
                            class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center"
                            style="width: 28px; height: 28px;"><svg width="14" height="14" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg></button>
                        <button
                            class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center"
                            style="width: 28px; height: 28px;"><svg width="14" height="14" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg></button>
                    </div>
                </div>

                <div class="row g-3">
                    @forelse($recommendedProjects as $project)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-4 h-100 position-relative">
                                <span
                                    class="badge bg-secondary bg-opacity-10 text-secondary position-absolute top-0 end-0 m-3 rounded-pill"
                                    style="font-size: 10px;">{{ $project->category }}</span>
                                <div class="mb-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-inline-flex">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-2 text-truncate">{{ $project->title }}</h6>
                                <p class="text-secondary small mb-4 line-clamp-2"
                                    style="font-size: 11px; min-height: 2.5em;">{{ $project->description }}</p>

                                <div class="mt-auto">
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="badge bg-light text-dark border small fw-normal">React</span>
                                        <span class="badge bg-light text-dark border small fw-normal">API</span>
                                    </div>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-primary w-100 btn-sm rounded-pill fw-bold">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted small">No recommendations at the moment.</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- RIGHT COLUMN: Sidebar (4 cols) -->
            <div class="col-lg-4">

                <!-- Notifications -->
                <div class="card border-0 shadow-sm mb-4">
                    <div
                        class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Notifications</h6>
                        <a href="#" class="small text-primary fw-bold text-decoration-none"
                            style="font-size: 11px;">Mark all read</a>
                    </div>
                    <div class="list-group list-group-flush p-2">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->data['link'] ?? '#' }}"
                                class="list-group-item list-group-item-action border-0 px-3 py-3 d-flex align-items-center gap-3 rounded-3 hover-bg-light text-decoration-none">
                                <div class="flex-shrink-0">
                                    <img src="{{ $notif->data['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(substr($notif->data['message'] ?? 'S', 0, 1)) . '&background=random' }}"
                                        class="rounded-circle" width="36" height="36">
                                </div>
                                <div class="w-100 overflow-hidden">
                                    <p class="mb-1 small lh-sm text-truncate text-break">
                                        {{ $notif->data['message'] ?? 'New message' }}</p>
                                    <small class="text-secondary"
                                        style="font-size: 10px;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center p-4">
                                <p class="text-secondary small mb-0">No new messages</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-3">
                        <a href="#" class="text-secondary small fw-bold text-decoration-none"
                            style="font-size: 11px;">VIEW ALL NOTIFICATIONS</a>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden position-relative">
                    <div class="position-absolute top-0 end-0 opacity-10 p-3">
                        <svg width="100" height="100" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="card-body p-4 position-relative">
                        <h6 class="fw-bold mb-4">Upcoming Deadlines</h6>

                        <div class="vstack gap-3">
                            @forelse($upcomingDeadlines as $deadline)
                                <div class="bg-white bg-opacity-10 rounded-3 p-3 border border-white border-opacity-10">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div class="bg-warning rounded-circle" style="width: 8px; height: 8px;"></div>
                                        <small class="fw-bold"
                                            style="font-size: 11px;">{{ \Carbon\Carbon::parse($deadline->end_date)->format('M d, H:i A') }}</small>
                                    </div>
                                    <h6 class="fw-bold mb-0 text-truncate">{{ $deadline->title }}</h6>
                                </div>
                            @empty
                                <div class="bg-white bg-opacity-10 rounded-3 p-3 border border-white border-opacity-10">
                                    <p class="mb-0 small">No upcoming deadlines.</p>
                                </div>
                            @endforelse
                        </div>

                        <button class="btn btn-white w-100 mt-4 rounded-pill fw-bold text-primary bg-white shadow-sm">
                            Open Calendar
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <style>
        .hover-scale:hover {
            transform: translateY(-2px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        [data-bs-theme="dark"] .card {
            background-color: #1e293b;
        }

        [data-bs-theme="light"] .card {
            background-color: #ffffff;
        }

        [data-bs-theme="light"] .text-white-force {
            color: #fff !important;
        }
    </style>
</x-app-layout>