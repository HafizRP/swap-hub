@section('title', $project->title)
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}"
                                class="text-decoration-none text-secondary hover-primary small fw-bold">Projects</a>
                        </li>
                        <li class="breadcrumb-item active small fw-bold" aria-current="page">Detail</li>
                    </ol>
                </nav>
                <h2 class="h3 fw-black text-white mb-0">
                    {{ $project->title }}
                </h2>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if(auth()->id() === $project->owner_id)
                    <a href="{{ route('projects.edit', $project) }}"
                        class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-black border-white border-opacity-10">Edit
                        Project</a>
                    @if($project->conversation)
                        <a href="{{ route('chat.show', $project->conversation) }}"
                            class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Enter Squad Chat</a>
                    @endif
                @elseif($project->members->contains(auth()->id()))
                    @if($project->conversation)
                        <a href="{{ route('chat.show', $project->conversation) }}"
                            class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Enter Squad Chat</a>
                    @else
                        <div
                            class="badge bg-emerald bg-opacity-10 text-emerald px-4 py-2 rounded-pill small fw-black border border-emerald border-opacity-25 d-flex align-items-center gap-2">
                            <svg style="width: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Already in Squad
                        </div>
                    @endif
                @else
                    <form action="{{ route('projects.members.add', $project) }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="member">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Apply
                            to Join Squad</button>
                    </form>
                @endif
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

                    <!-- GitHub Activity Timeline -->
                    <div class="card border-0 shadow-sm">
                        <div
                            class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-black mb-0">Activity Feed</h5>
                            <span
                                class="badge bg-primary bg-opacity-10 text-primary small fw-black text-uppercase tracking-widest px-2 py-1">Live
                                Updates</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline position-relative ps-4">
                                @forelse($project->githubActivities as $activity)
                                    <div class="timeline-item pb-4 position-relative">
                                        <div class="timeline-dot position-absolute start-0 translate-middle-x bg-primary rounded-circle border border-4 border-dark"
                                            style="width: 14px; height: 14px; left: -21px !important; top: 4px;"></div>
                                        <div class="ps-2">
                                            <p class="small text-white mb-1"><span
                                                    class="fw-black">{{ $activity->user->name }}</span> committed to <span
                                                    class="badge fw-mono"
                                                    style="background-color: rgba(108, 117, 125, 0.25) !important;">{{ substr($activity->commit_sha, 0, 7) }}</span>
                                            </p>
                                            <p
                                                class="small text-secondary font-italic mb-2 ps-2 border-start border-primary border-opacity-25">
                                                "{{ $activity->commit_message }}"</p>
                                            <span class="text-secondary opacity-50"
                                                style="font-size: 10px;">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-secondary small mb-0">Waiting for contribution activity...</p>
                                    </div>
                                @endforelse
                                <div class="timeline-line position-absolute start-0 top-0 bottom-0 bg-primary bg-opacity-10"
                                    style="width: 2px; left: -21px !important; z-index: -1;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">

                    <!-- Squad Members -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                            <h5 class="fw-black mb-0">Squad Members</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column gap-4">
                                @foreach($project->members as $member)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                                class="rounded-3" width="40" height="40">
                                            <div>
                                                <h6 class="small fw-bold text-white mb-0">{{ $member->name }}</h6>
                                                <span class="small text-secondary text-uppercase fw-black opacity-50"
                                                    style="font-size: 9px; letter-spacing: 0.05em;">{{ $member->pivot->role }}</span>
                                            </div>
                                        </div>
                                        @if($member->pivot->is_validated)
                                            <i class="bi bi-patch-check-fill text-primary" title="Validated Contributor"></i>
                                            <svg style="width: 16px;" class="text-primary" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if(auth()->id() === $project->owner_id)
                                <button
                                    class="btn btn-outline-secondary w-100 mt-5 rounded-4 py-3 border-dashed small fw-black opacity-50 hover-opacity-100">
                                    + Invite Talents
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Project Stats -->
                    <div class="card border-0 shadow-lg text-white p-4"
                        style="background: linear-gradient(135deg, #111827, #1f2937);">
                        <h5 class="fw-black mb-4">Collaboration Stats</h5>
                        <div class="d-flex flex-column gap-3">
                            <div class="rounded-4 p-3 d-flex justify-content-between align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                <span class="small fw-black text-secondary text-uppercase">Commits</span>
                                <span class="h4 fw-black mb-0">{{ $project->githubActivities->count() }}</span>
                            </div>
                            <div class="rounded-4 p-3 d-flex justify-content-between align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                <span class="small fw-black text-secondary text-uppercase">Health</span>
                                <span class="h4 fw-black mb-0 text-success">98%</span>
                            </div>
                            <div class="rounded-4 p-3 d-flex justify-content-between align-items-center"
                                style="background-color: rgba(255, 255, 255, 0.05) !important;">
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