@section('title', $project->title . ' - Workspace')
<x-app-layout>
    <div class="d-flex vh-100 overflow-hidden">
        <!-- Left Sidebar - Project Navigation -->
        <div class="bg-dark border-end border-white border-opacity-10" style="width: 240px; flex-shrink: 0;">
            <div class="p-4 border-bottom border-white border-opacity-10">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-folder-fill text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold text-white">{{ $project->title }}</h6>
                        <small class="text-secondary">{{ $project->category }}</small>
                    </div>
                </div>
                @if($project->github_repo_url)
                    <a href="{{ $project->github_repo_url }}" target="_blank"
                        class="btn btn-sm btn-outline-secondary w-100 rounded-pill">
                        <i class="bi bi-github me-1"></i>Repository
                    </a>
                @endif
            </div>

            <!-- Navigation -->
            <div class="p-3">
                <small class="text-secondary text-uppercase fw-bold px-3"
                    style="font-size: 10px; letter-spacing: 0.05em;">Navigation</small>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="#" class="nav-link active rounded-3 text-white">
                            <i class="bi bi-house-fill me-2"></i>Home
                        </a>
                    </li>
                </ul>

                <small class="text-secondary text-uppercase fw-bold px-3 mt-4 d-block"
                    style="font-size: 10px; letter-spacing: 0.05em;">My Projects</small>
                <ul class="nav flex-column mt-2">
                    @foreach(auth()->user()->projects()->take(3)->get() as $userProject)
                        <li class="nav-item">
                            <a href="{{ route('projects.workspace', $userProject) }}"
                                class="nav-link rounded-3 {{ $userProject->id === $project->id ? 'active text-white' : 'text-secondary' }}">
                                <i class="bi bi-folder me-2"></i>{{ Str::limit($userProject->title, 20) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <small class="text-secondary text-uppercase fw-bold px-3 mt-4 d-block"
                    style="font-size: 10px; letter-spacing: 0.05em;">Tools</small>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="#" class="nav-link rounded-3 text-secondary position-relative">
                            <i class="bi bi-chat-dots-fill me-2"></i>Messages
                            @if($project->conversation && $project->conversation->messages()->where('read', false)->count() > 0)
                                <span class="badge bg-primary rounded-pill position-absolute end-0 me-2">
                                    {{ $project->conversation->messages()->where('read', false)->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link rounded-3 text-secondary">
                            <i class="bi bi-calendar3 me-2"></i>Calendar
                        </a>
                    </li>
                </ul>
            </div>

            <!-- User Profile at Bottom -->
            <div class="mt-auto p-3 border-top border-white border-opacity-10"
                style="position: absolute; bottom: 0; width: 240px;">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                        class="rounded-circle" width="32" height="32">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-success small" style="font-size: 10px;">
                            <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Online
                        </div>
                    </div>
                    <button class="btn btn-sm btn-link text-secondary p-0">
                        <i class="bi bi-gear-fill"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-grow-1 d-flex flex-column overflow-hidden">
            <!-- Top Header -->
            <div class="bg-dark border-bottom border-white border-opacity-10 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="mb-0 fw-bold text-white">{{ $project->title }}</h5>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                            <i class="bi bi-circle-fill me-1" style="font-size: 6px;"></i>Active
                        </span>
                        @if($project->github_repo_url)
                            <a href="{{ $project->github_repo_url }}" target="_blank"
                                class="text-decoration-none text-secondary small">
                                <i class="bi bi-github me-1"></i>{{ $project->github_repo_name ?? 'Repository' }}
                            </a>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-gear me-1"></i>Settings
                        </button>
                        <button class="btn btn-sm btn-primary rounded-pill">
                            <i class="bi bi-plus-lg me-1"></i>Invite
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs border-0 mt-3" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active border-0 text-white" data-bs-toggle="tab"
                            data-bs-target="#chat-tab">
                            <i class="bi bi-chat-dots-fill me-2"></i>Group Chat
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link border-0 text-secondary" data-bs-toggle="tab"
                            data-bs-target="#tasks-tab">
                            <i class="bi bi-check-circle me-2"></i>Task Board
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link border-0 text-secondary" data-bs-toggle="tab"
                            data-bs-target="#files-tab">
                            <i class="bi bi-folder me-2"></i>Files
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link border-0 text-secondary" data-bs-toggle="tab"
                            data-bs-target="#github-tab">
                            <i class="bi bi-github me-2"></i>GitHub Feed
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="flex-grow-1 overflow-auto">
                <div class="tab-content h-100">
                    <!-- Chat Tab -->
                    <div class="tab-pane fade show active h-100" id="chat-tab">
                        <div class="d-flex h-100">
                            <!-- Chat Messages -->
                            <div class="flex-grow-1 d-flex flex-column">
                                <!-- Messages Area -->
                                <div class="flex-grow-1 overflow-auto p-4" id="messages-container">
                                    @if($project->conversation)
                                        @foreach($project->conversation->messages()->with('user')->latest()->take(50)->get()->reverse() as $message)
                                            <div class="mb-4">
                                                <div class="d-flex align-items-start gap-3">
                                                    <img src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}"
                                                        class="rounded-circle" width="40" height="40">
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="fw-bold text-white">{{ $message->user->name }}</span>
                                                            <span
                                                                class="text-secondary small">{{ $message->created_at->format('h:i A') }}</span>
                                                            @if($message->user_id === auth()->id())
                                                                <span class="text-primary small">You</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-white">{{ $message->message }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-secondary py-5">
                                            <i class="bi bi-chat-dots fs-1 opacity-50"></i>
                                            <p class="mt-3">No messages yet. Start the conversation!</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Message Input -->
                                <div class="p-3 border-top border-white border-opacity-10">
                                    <form action="{{ route('messages.store') }}" method="POST"
                                        class="d-flex gap-2 align-items-center">
                                        @csrf
                                        <input type="hidden" name="conversation_id"
                                            value="{{ $project->conversation->id ?? '' }}">
                                        <button type="button" class="btn btn-link text-secondary p-2">
                                            <i class="bi bi-plus-circle fs-5"></i>
                                        </button>
                                        <input type="text" name="message"
                                            class="form-control bg-dark border-0 rounded-pill text-white"
                                            placeholder="Type a message to #general..." required
                                            style="background-color: rgba(255,255,255,0.05) !important;">
                                        <button type="button" class="btn btn-link text-secondary p-2">
                                            <i class="bi bi-emoji-smile fs-5"></i>
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-circle"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </form>
                                    <small class="text-secondary mt-2 d-block" style="font-size: 11px;">
                                        <i class="bi bi-circle-fill text-success" style="font-size: 6px;"></i> Connected
                                        • Shift + Enter for new line
                                    </small>
                                </div>
                            </div>

                            <!-- Right Sidebar - Team & Tasks -->
                            <div class="border-start border-white border-opacity-10"
                                style="width: 300px; flex-shrink: 0;">
                                <!-- Team Members -->
                                <div class="p-4 border-bottom border-white border-opacity-10">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold text-white">TEAM MEMBERS</h6>
                                        <span
                                            class="badge bg-secondary rounded-pill">{{ $project->members->count() }}</span>
                                    </div>
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($project->members as $member)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="position-relative">
                                                    <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) }}"
                                                        class="rounded-circle" width="36" height="36">
                                                    <span
                                                        class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                                        style="width: 10px; height: 10px;"></span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="small fw-bold text-white">{{ $member->name }}</div>
                                                    <div class="text-secondary" style="font-size: 11px;">
                                                        {{ $member->pivot->role ?? 'Member' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(auth()->id() === $project->owner_id)
                                            <button class="btn btn-sm btn-outline-secondary w-100 rounded-pill mt-2">
                                                <i class="bi bi-plus-lg me-1"></i>Add Member
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quick Tasks -->
                                <div class="p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 fw-bold text-white">QUICK TASKS</h6>
                                        <a href="#" class="text-primary small text-decoration-none">View All</a>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task1">
                                            <label class="form-check-label text-white small" for="task1">
                                                Setup MongoDB Atlas
                                            </label>
                                            <div class="text-secondary" style="font-size: 10px;">Completed by Mike</div>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task2">
                                            <label class="form-check-label text-white small" for="task2">
                                                Finalize Dashboard Mockups
                                            </label>
                                            <span class="badge bg-warning text-dark" style="font-size: 9px;">High
                                                Priority</span>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="task3">
                                            <label class="form-check-label text-white small" for="task3">
                                                Write API Documentation
                                            </label>
                                            <div class="text-secondary" style="font-size: 10px;">Due Tomorrow</div>
                                        </div>
                                        <button class="btn btn-sm btn-link text-primary p-0 text-start mt-2">
                                            <i class="bi bi-plus-lg me-1"></i>Create New Task
                                        </button>
                                    </div>
                                </div>

                                <!-- Repo Status -->
                                @if($project->github_repo_url)
                                    <div class="p-4 border-top border-white border-opacity-10">
                                        <div class="bg-dark rounded-3 p-3 border border-white border-opacity-10">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="bi bi-github text-white"></i>
                                                <span class="small fw-bold text-white">Repo Connected</span>
                                            </div>
                                            <div class="text-success small">
                                                <i class="bi bi-circle-fill" style="font-size: 6px;"></i> Last sync 2 mins
                                                ago
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Other Tabs (Placeholder) -->
                    <div class="tab-pane fade p-4" id="tasks-tab">
                        <h5 class="text-white">Task Board</h5>
                        <p class="text-secondary">Coming soon...</p>
                    </div>
                    <div class="tab-pane fade p-4" id="files-tab">
                        <h5 class="text-white">Files</h5>
                        <p class="text-secondary">Coming soon...</p>
                    </div>
                    <div class="tab-pane fade p-4" id="github-tab">
                        <h5 class="text-white">GitHub Feed</h5>
                        <p class="text-secondary">Coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            border-bottom: 2px solid transparent;
        }

        .nav-tabs .nav-link.active {
            border-bottom-color: var(--bs-primary);
            background: transparent;
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
        }
    </style>
</x-app-layout>