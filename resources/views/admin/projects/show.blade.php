@section('title', $project->title)
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}"
                                class="text-decoration-none text-secondary hover-primary small fw-bold">Admin
                                Projects</a>
                        </li>
                        <li class="breadcrumb-item active small fw-bold" aria-current="page">Detail</li>
                    </ol>
                </nav>
                <h2 class="h3 fw-black text-white mb-0">
                    {{ $project->title }}
                </h2>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.edit', $project) }}"
                    class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-black border-white border-opacity-10">
                    <i class="bi bi-pencil-fill me-1"></i>Edit Project
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="btn btn-secondary rounded-pill px-4 py-2 small fw-black">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">
                    <!-- Description Card -->
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                            <h5 class="fw-black mb-0">Project Overview</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-secondary lh-lg mb-4">
                                {{ $project->description }}
                            </div>

                            @if($project->github_repo_url)
                                <div
                                    class="bg-dark bg-opacity-25 rounded-4 p-4 d-flex align-items-center justify-content-between border border-white border-opacity-10 mt-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center"
                                            style="width: 44px; height: 44px;">
                                            <svg style="width: 24px;" fill="#000" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-white mb-0">
                                                {{ $project->github_repo_name ?? 'Repository linked' }}
                                            </h6>
                                            <p class="small text-secondary mb-0">Live GitHub integration active</p>
                                        </div>
                                    </div>
                                    <a href="{{ $project->github_repo_url }}" target="_blank"
                                        class="btn btn-sm btn-link link-primary p-0 text-decoration-none fw-black small">Open
                                        Repo ↗</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Project Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                            <h5 class="fw-black mb-0">Project Information</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label
                                        class="small text-secondary text-uppercase fw-black tracking-widest">Category</label>
                                    <p class="mb-0">
                                        <span class="badge bg-info px-3 py-2">{{ $project->category }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="small text-secondary text-uppercase fw-black tracking-widest">Status</label>
                                    <p class="mb-0">
                                        <span class="badge 
                                            @if($project->status === 'active') bg-success
                                            @elseif($project->status === 'completed') bg-primary
                                            @elseif($project->status === 'planning') bg-warning
                                            @else bg-secondary
                                            @endif px-3 py-2">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="small text-secondary text-uppercase fw-black tracking-widest">Created</label>
                                    <p class="mb-0 text-white">{{ $project->created_at->format('M d, Y H:i') }}</p>
                                    <small class="text-secondary">{{ $project->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label
                                        class="small text-secondary text-uppercase fw-black tracking-widest">Members</label>
                                    <p class="mb-0 text-white fw-bold">{{ $project->members->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">
                    <!-- Project Owner -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                            <h5 class="fw-black mb-0">Project Owner</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}"
                                    class="rounded-3" width="48" height="48">
                                <div>
                                    <h6 class="fw-bold text-white mb-0">{{ $project->owner->name }}</h6>
                                    <p class="small text-secondary mb-0">{{ $project->owner->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Squad Members -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                            <h5 class="fw-black mb-0">Squad Members</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column gap-4">
                                @forelse($project->members as $member)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                                class="rounded-3" width="40" height="40">
                                            <div>
                                                <h6 class="small fw-bold text-white mb-0">{{ $member->name }}</h6>
                                                <span class="small text-secondary text-uppercase fw-black opacity-50"
                                                    style="font-size: 9px; letter-spacing: 0.05em;">{{ $member->pivot->role ?? 'member' }}</span>
                                            </div>
                                        </div>
                                        @if($member->pivot->is_validated)
                                            <svg style="width: 16px;" class="text-primary" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-secondary small text-center mb-0">No members yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Project Stats -->
                    <div class="card border-0 shadow-sm p-4">
                        <h5 class="fw-black mb-4">Project Stats</h5>
                        <div class="d-flex flex-column gap-3">
                            <div
                                class="rounded-4 p-3 d-flex justify-content-between align-items-center bg-secondary bg-opacity-10">
                                <span class="small fw-black text-secondary text-uppercase">Members</span>
                                <span class="h4 fw-black mb-0">{{ $project->members->count() }}</span>
                            </div>
                            <div
                                class="rounded-4 p-3 d-flex justify-content-between align-items-center bg-secondary bg-opacity-10">
                                <span class="small fw-black text-secondary text-uppercase">Active</span>
                                <span
                                    class="h4 fw-black mb-0">{{ (int) ($project->created_at->diffInDays() + 1) }}d</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>