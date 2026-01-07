@section('title', $project->title)
<x-app-layout>
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-4">
            <div class="flex-grow-1">
                <div class="mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary rounded-pill px-3 py-1 small fw-bold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Back to Projects
                    </a>
                </div>

                <h2 class="h3 fw-black text-body mb-0">
                    {{ $project->title }}
                </h2>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @if(auth()->id() == $project->owner_id)
                    <a href="{{ route('projects.edit', $project) }}"
                        class="btn btn-outline-secondary rounded-pill px-4 py-2 small fw-bold">Edit
                        Project</a>
                    @if($project->conversation)
                        <a href="{{ route('chat', $project->conversation) }}"
                            class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Enter Squad Chat</a>
                    @endif
                @else
                    @php
                        $member = $project->members->firstWhere('id', auth()->id());
                        $status = $member ? $member->pivot->status : null;
                    @endphp
                    @if($status === 'active')
                        @if($project->conversation)
                            <a href="{{ route('chat', $project->conversation) }}"
                                class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Enter Squad Chat</a>
                        @else
                            <div class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill small fw-black border border-success border-opacity-25 d-flex align-items-center gap-2">
                                <svg style="width: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Already in Squad
                            </div>
                        @endif
                    @elseif($status === 'pending')
                         <div class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 rounded-pill small fw-black border border-warning border-opacity-25 d-flex align-items-center gap-2">
                            <i class="bi bi-hourglass-split"></i>
                            Request Pending
                        </div>
                    @else
                        {{-- Not a member or rejected --}}
                        <form action="{{ route('projects.members.add', $project) }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="member">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 small fw-black shadow-lg">Apply
                                to Join Squad</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>


    <div class="container py-5">
        {{-- Action Card for Non-Members --}}
        @if(auth()->id() !== $project->owner_id)
            @php
                $member = $project->members->firstWhere('id', auth()->id());
                $status = $member ? $member->pivot->status : null;
            @endphp

            @if($status === null || $status === 'rejected')
                <div class="alert alert-primary border-0 shadow-sm rounded-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 p-4">
                    <div class="mb-3 mb-md-0">
                        <h5 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-2"></i>Interested in this project?</h5>
                        <p class="mb-0">Join the squad and start collaborating with the team!</p>
                    </div>
                    <form action="{{ route('projects.members.add', $project) }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="member">
                        <button type="submit" class="btn btn-light btn-lg shadow-sm px-4">
                            <i class="bi bi-person-plus-fill me-2"></i>Apply to Join Squad
                        </button>
                    </form>
                </div>
            @elseif($status === 'pending')
                <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 mb-4 p-4">
                    <i class="bi bi-hourglass-split fs-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Request Pending</h5>
                        <p class="mb-0">Your application to join this project is awaiting approval from the owner.</p>
                    </div>
                </div>
            @elseif($status === 'active' && $project->conversation)
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 p-4">
                    <div class="d-flex align-items-center gap-3 mb-3 mb-md-0">
                        <i class="bi bi-check-circle-fill fs-3"></i>
                        <div>
                            <h5 class="fw-bold mb-1">You're part of this squad!</h5>
                            <p class="mb-0">Join the conversation and collaborate with your team.</p>
                        </div>
                    </div>
                    <a href="{{ route('chat', $project->conversation) }}" class="btn btn-success btn-lg shadow-sm px-4">
                        <i class="bi bi-chat-dots-fill me-2"></i>Enter Squad Chat
                    </a>
                </div>
            @endif
        @endif

        <div class="row g-4">

            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-4">

                    <!-- Description Card -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-transparent border-bottom border-secondary-subtle p-4">
                            <h5 class="fw-black mb-0">Project Overview</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="project-description text-secondary" style="font-size: 1rem; line-height: 1.8;">
                                {!! Str::markdown($project->description) !!}
                            </div>

                            <style>
                                .project-description p {
                                    margin-bottom: 1.5rem;
                                }
                                .project-description p:last-child {
                                    margin-bottom: 0;
                                }
                                .project-description ul, .project-description ol {
                                    margin-bottom: 1.5rem;
                                    padding-left: 1.5rem;
                                }
                                .project-description li {
                                    margin-bottom: 0.5rem;
                                }
                                .project-description h1, .project-description h2, .project-description h3, .project-description h4 {
                                    color: var(--bs-body-color);
                                    font-weight: 700;
                                    margin-top: 1.5rem;
                                    margin-bottom: 1rem;
                                }
                                .project-description strong,
                                .project-description b {
                                    font-weight: 700;
                                    color: var(--bs-body-color);
                                }
                            </style>

                            @if($project->github_repo_url)
                                <div class="bg-dark bg-opacity-25 rounded-4 p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between border border-white border-opacity-10 mt-4 gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 48px; height: 48px;">
                                            <svg style="width: 24px;" fill="#000" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-body mb-0">
                                                {{ $project->github_repo_name ?? 'Repository linked' }}
                                            </h6>
                                            <p class="small text-secondary mb-0">Live GitHub integration active</p>
                                        </div>
                                    </div>
                                    <a href="{{ $project->github_repo_url }}" target="_blank"
                                        class="btn btn-sm btn-link link-primary p-0 text-decoration-none fw-black">
                                        Open Repo ↗
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- GitHub Activity Timeline -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div
                            class="card-header bg-transparent border-bottom border-secondary-subtle p-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-black mb-0">Activity Feed</h5>
                            <span
                                class="badge bg-primary-subtle text-primary-emphasis small fw-bold text-uppercase px-2 py-1">Live
                                Updates</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline position-relative ps-4">
                                @forelse($project->githubActivities as $activity)
                                    <div class="timeline-item pb-4 position-relative">
                                        <div class="timeline-dot position-absolute start-0 translate-middle-x bg-primary rounded-circle border border-4 border-dark"
                                            style="width: 14px; height: 14px; left: -21px !important; top: 4px;"></div>
                                        <div class="ps-2">
                                            <p class="small text-body mb-1"><span
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
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-bottom border-secondary-subtle p-4">
                            <h5 class="fw-black mb-0">Squad Members</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-column gap-3">
                                @foreach($project->members as $member)
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                                class="rounded-3" width="44" height="44" style="object-fit: cover;">
                                            <div>
                                                <h6 class="fw-bold text-body mb-0" style="font-size: 0.9375rem;">{{ $member->name }}</h6>
                                                <span class="small text-secondary text-uppercase fw-bold opacity-75"
                                                    style="font-size: 0.75rem; letter-spacing: 0.05em;">{{ $member->pivot->role }}</span>
                                            </div>
                                        </div>
                                        @if($member->pivot->is_validated)
                                            <svg style="width: 20px;" class="text-primary flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20" title="Validated Contributor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if(auth()->id() == $project->owner_id)
                                <button data-bs-toggle="modal" data-bs-target="#inviteMemberModal"
                                    class="btn btn-outline-secondary w-100 mt-4 rounded-3 py-3 border-dashed fw-bold opacity-75 hover-opacity-100">
                                    <i class="bi bi-plus-lg me-2"></i>Invite Talents
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Project Stats -->
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-black mb-4">Collaboration Stats</h5>
                        <div class="d-flex flex-column gap-3">
                            <!-- Commits -->
                            <div class="p-3 rounded-4 bg-body-tertiary d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-git"></i>
                                    </div>
                                    <span class="fw-bold small text-secondary text-uppercase">Commits</span>
                                </div>
                                <span class="h5 fw-black mb-0">{{ $project->githubActivities->count() }}</span>
                            </div>
                            
                            <!-- Health -->
                            <div class="p-3 rounded-4 bg-body-tertiary d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                       <i class="bi bi-activity"></i>
                                    </div>
                                    <span class="fw-bold small text-secondary text-uppercase">Health</span>
                                </div>
                                <span class="h5 fw-black mb-0 text-success">Good</span>
                            </div>

                            <!-- Active -->
                            <div class="p-3 rounded-4 bg-body-tertiary d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span class="fw-bold small text-secondary text-uppercase">Active</span>
                                </div>
                                <span class="h5 fw-black mb-0">{{ (int) ($project->created_at->diffInDays() + 1) }}d</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Modal -->
    <div class="modal fade" id="inviteMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg" style="background-color: var(--bs-body-bg);">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-black">Invite Talent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-4">
                    <form action="{{ route('projects.members.add', $project) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label text-secondary small fw-bold">User Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="Enter email address" required style="background-color: var(--bs-tertiary-bg); border-color: transparent;">
                        </div>
                        <div class="mb-4">
                            <label for="role" class="form-label text-secondary small fw-bold">Role</label>
                            <select name="role" class="form-select rounded-3" style="background-color: var(--bs-tertiary-bg); border-color: transparent;">
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                                <option value="collaborator">Collaborator</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold">Add Member</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>