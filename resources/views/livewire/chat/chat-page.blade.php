@section('title', $this->getTitle())

<div>
    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(155, 155, 155, 0.5);
            border-radius: 3px;
        }

        /* Chat Bubbles */
        .chat-bubble {
            border-radius: 18px;
            padding: 10px 16px;
            position: relative;
            max-width: 100%;
            width: fit-content;
            word-wrap: break-word;
        }

        .chat-bubble-own {
            background-color: var(--bs-primary);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-bubble-other {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-body-color);
            border-bottom-left-radius: 4px;
        }

        /* Variables */
        :root {
            --chat-header-height: 120px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--bs-secondary);
            padding: 1rem 0;
            margin-right: 1.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-tabs .nav-link:hover {
            color: var(--bs-primary);
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            background: transparent;
            border-bottom-color: var(--bs-primary);
        }
    </style>

    <div id="chat-page-component" class="container-fluid py-0 h-100" style="height: calc(100vh - 74px) !important;">
        <div class="row h-100 g-0">

            <!-- LEFT COLUMN: Conversation List -->
            <div class="col-12 col-md-4 col-lg-3 border-end border-secondary-subtle d-flex flex-column h-100 bg-body {{ $conversation ? 'd-none d-md-flex' : 'd-flex' }}">
                <div
                    class="p-3 border-bottom border-secondary-subtle d-flex justify-content-between align-items-center bg-body">
                    <h6 class="fw-bold mb-0 text-uppercase tracking-wide small opacity-75 text-body">Workspaces</h6>
                    <button class="btn btn-sm btn-icon text-secondary"><i class="bi bi-plus-lg"></i></button>
                </div>
                @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
            </div>

            <!-- MIDDLE COLUMN: Main Chat / Interaction Area -->
            <div class="col-12 col-md-8 col-lg-6 d-flex flex-column h-100 bg-body border-end border-secondary-subtle position-relative {{ $conversation ? 'd-flex' : 'd-none d-md-flex' }}">
                @if($conversation)
                    <!-- Header Area -->
                    <div
                        class="px-4 pt-3 {{ $conversation->type === 'project' ? 'pb-0' : 'pb-3' }} border-bottom border-secondary-subtle flex-shrink-0 bg-body-tertiary">

                        <!-- Top Row: Title & Actions -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Mobile Back -->
                                <a href="/chat" wire:navigate class="btn btn-icon btn-text-secondary d-md-none me-1 p-0">
                                    <i class="bi bi-arrow-left fs-5"></i>
                                </a>

                                @php
                                    $title = $this->getTitle();
                                    $other = ($conversation->type === 'direct') ? $conversation->participants->where('id', '!=', auth()->id())->first() : null;
                                    $avatar = $conversation->type === 'project'
                                        ? 'https://ui-avatars.com/api/?name=' . urlencode($title) . '&background=4f46e5&color=fff'
                                        : ($other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($title));
                                @endphp

                                @if($conversation->type === 'direct')
                                    <img src="{{ $avatar }}" class="rounded-circle shadow-sm" width="40" height="40">
                                @else
                                    <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-folder-fill fs-5"></i>
                                    </div>
                                @endif

                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <h5 class="fw-bold mb-0 text-body">{{ $title }}</h5>
                                        @if($conversation->type === 'project')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"
                                                style="font-size: 10px;">
                                                <i class="bi bi-circle-fill me-1"
                                                    style="font-size: 6px; vertical-align: middle;"></i>Active
                                            </span>
                                        @endif
                                    </div>
                                    @if($conversation->type === 'project' && $conversation->project && $conversation->project->github_repo_url)
                                        <a href="{{ $conversation->project->github_repo_url }}" target="_blank"
                                            class="text-secondary small text-decoration-none hover-primary">
                                            <i
                                                class="bi bi-github me-1"></i>{{ $conversation->project->github_repo_name ?? 'Repository' }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-pill fw-bold small">
                                    <i class="bi bi-gear-fill me-1"></i>Settings
                                </button>
                                @if($conversation->type === 'project')
                                    <button class="btn btn-sm btn-primary rounded-pill fw-bold small" data-bs-toggle="modal"
                                        data-bs-target="#addMemberModal">
                                        <i class="bi bi-plus-lg me-1"></i>Invite
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($conversation->type === 'project')
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" style="margin-bottom: -1px;">
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === 'chat' ? 'active' : '' }}" href="#"
                                        wire:click.prevent="setTab('chat')">
                                        <i class="bi bi-chat-dots-fill me-2"></i>Project Chat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === 'tasks' ? 'active' : '' }}" href="#"
                                        wire:click.prevent="setTab('tasks')">
                                        <i class="bi bi-kanban me-2"></i>Task Board
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === 'files' ? 'active' : '' }}" href="#"
                                        wire:click.prevent="setTab('files')">
                                        <i class="bi bi-folder me-2"></i>Files
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ $activeTab === 'github' ? 'active' : '' }}" href="#"
                                        wire:click.prevent="setTab('github')">
                                        <i class="bi bi-github me-2"></i>GitHub Feed
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>

                    <!-- TAB CONTENT AREA -->

                    @if($activeTab === 'chat')
                        <!-- Chat Messages Area -->
                        <div class="flex-grow-1 overflow-auto p-4 custom-scrollbar bg-body" id="messagesContainer"
                            x-init="$el.scrollTop = $el.scrollHeight"
                            @scroll-to-bottom.window="document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight">
                            
                            <div class="d-flex flex-column gap-3">
                                @forelse($messages as $msg)
                                    @php
                                        $isOwn = $msg['user_id'] == auth()->id();
                                        $isSystem = !$msg['user_id'];
                                    @endphp

                                    @if($isSystem)
                                        <div class="text-center my-3">
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill px-3 py-1 fw-normal shadow-sm"
                                                style="font-size: 11px;">
                                                {!! nl2br(e($msg['content'])) !!}
                                            </span>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-end gap-2 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                                            <!-- Avatar -->
                                            <img src="{{ $msg['user_avatar'] }}" class="rounded-circle shadow-sm flex-shrink-0" width="32" height="32" 
                                                 data-bs-toggle="tooltip" title="{{ $msg['user_name'] }}" style="margin-bottom: 2px;">

                                            <div class="d-flex flex-column {{ $isOwn ? 'align-items-end' : 'align-items-start' }}" style="max-width: 75%;">
                                                
                                                <!-- Message Bubble -->
                                                @if(!empty(trim($msg['content'])))
                                                <div class="chat-bubble shadow-sm {{ $isOwn ? 'chat-bubble-own' : 'chat-bubble-other' }}">
                                                    {!! nl2br(e($msg['content'])) !!}
                                                </div>
                                                @endif

                                                <!-- Attachments -->
                                                @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                    <div class="d-flex flex-wrap gap-2 mt-1 {{ $isOwn ? 'justify-content-end' : 'justify-content-start' }}">
                                                        @foreach($msg['attachments'] as $att)
                                                            @if(Str::startsWith($att['file_type'], 'image/'))
                                                                <div role="button" onclick="openGallery(@js($msg['attachments']), '{{ $att['id'] }}')" 
                                                                     class="overflow-hidden rounded-3 shadow-sm border border-secondary-subtle transition hover-lift">
                                                                    <img src="{{ $att['file_path'] }}"
                                                                        class="d-block"
                                                                        style="max-height: 200px; max-width: 100%; object-fit: cover;">
                                                                </div>
                                                            @else
                                                                <a href="{{ $att['file_path'] }}" target="_blank"
                                                                    class="d-flex align-items-center gap-2 p-2 bg-body-tertiary rounded border border-secondary-subtle text-decoration-none text-body shadow-sm hover-lift transition">
                                                                    <div class="bg-body rounded p-1">
                                                                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                                                                    </div>
                                                                    <span class="small fw-medium">{{ $att['file_name'] }}</span>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Meta: Name and Time -->
                                                <div class="mt-1 d-flex align-items-center gap-2 small {{ $isOwn ? 'justify-content-end' : 'justify-content-start flex-row-reverse' }} px-1"> 
                                                     <span class="text-secondary opacity-75" style="font-size: 10px;">{{ $msg['created_at_human'] }}</span>
                                                     @if(!$isOwn) 
                                                        <span class="fw-bold text-secondary opacity-75" style="font-size: 10px;">{{ $msg['user_name'] }}</span> 
                                                     @else
                                                        <!-- Optional checklist for read status could go here -->
                                                        <i class="bi bi-check2-all text-primary opacity-75" style="font-size: 12px;"></i>
                                                     @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div
                                        class="h-100 d-flex flex-column align-items-center justify-content-center text-center opacity-50 py-5">
                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle mb-3">
                                            <i class="bi bi-chat-heart fs-1 text-secondary"></i>
                                        </div>
                                        <p class="small text-secondary fw-bold">No messages yet.</p>
                                        <p class="small text-secondary" style="font-size: 0.8rem;">Start the conversation with your team!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Input Area -->
                        <div class="p-3 border-top border-secondary-subtle bg-body-tertiary">
                            <form wire:submit.prevent="sendMessage">
                                @if(count($attachments) > 0)
                                    <div
                                        class="d-flex flex-wrap gap-2 mb-2 p-2 bg-secondary bg-opacity-10 rounded border border-secondary-subtle bg-body">
                                        @foreach($attachments as $index => $att)
                                            <div class="position-relative">
                                                <span class="badge bg-secondary">{{ $att->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                                    class="btn-close btn-close-white position-absolute top-0 end-0 translate-middle p-1 bg-danger rounded-circle"
                                                    style="width: 16px; height: 16px;"></button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="input-group bg-body rounded-pill shadow-sm border border-secondary-subtle overflow-hidden">
                                    <button type="button" class="btn btn-link text-secondary ps-3 border-0 text-decoration-none"
                                        onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-paperclip fs-5"></i>
                                    </button>
                                    <input type="file" wire:model="attachments" id="fileInput" class="d-none" multiple>

                                    <input type="text" wire:model.live.debounce.250ms="newMessage"
                                        class="form-control border-0 bg-transparent text-body shadow-none px-2"
                                        placeholder="Type a message to {{ $conversation->type === 'project' ? '#general' : $title }}..."
                                        {{ $loading ? 'disabled' : '' }} 
                                        style="height: 48px;">

                                    <button class="btn btn-link text-secondary border-0 text-decoration-none" type="button">
                                        <i class="bi bi-emoji-smile fs-5"></i>
                                    </button>
                                    <button class="btn btn-primary border-0 m-1 rounded-circle d-flex align-items-center justify-content-center transition-transform active-scale-95"
                                        style="width: 40px; height: 40px;" type="submit"
                                        {{ (empty($newMessage) && count($attachments) === 0) || $loading ? 'disabled' : '' }}>
                                        <i class="bi bi-send-fill text-white ms-1" style="font-size: 0.9rem;"></i>
                                    </button>
                                </div>
                                <div class="text-end mt-2 me-2">
                                    <small class="text-secondary opacity-75" style="font-size: 10px;">
                                        Press <span class="fw-bold">Enter</span> to send
                                    </small>
                                </div>
                            </form>
                        </div>

                    @elseif($activeTab === 'tasks' && $conversation->type === 'project' && $conversation->project)
                        @livewire('project.task-board', ['project' => $conversation->project], key('tasks-' . $conversation->id))

                    @elseif($activeTab === 'files' && $conversation->type === 'project' && $conversation->project)
                        @livewire('project.file-browser', ['project' => $conversation->project], key('files-' . $conversation->id))

                    @elseif($activeTab === 'github' && $conversation->type === 'project' && $conversation->project)
                        @livewire('project.github-feed', ['project' => $conversation->project], key('github-' . $conversation->id))
                    @endif

                @else
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4 bg-body">
                        <div class="bg-secondary bg-opacity-10 p-4 rounded-circle mb-3">
                            <i class="bi bi-chat-quote-fill fs-1 text-secondary"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-body">Select a Conversation</h4>
                        <p class="text-secondary small">Choose a project workspace or direct message from the sidebar.</p>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: Context Info -->
            <div class="col-lg-3 border-start border-secondary-subtle d-none d-lg-flex flex-column h-100 bg-body">
                @if($conversation && $conversation->type === 'project')
                    <!-- Team Members Info -->
                    <div class="p-4 border-bottom border-secondary-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-uppercase small tracking-wide opacity-75 text-body">Team Members</h6>
                            <span class="badge bg-secondary rounded-pill">{{ $conversation->participants->count() }}</span>
                        </div>
                        <div class="d-flex flex-column gap-3 overflow-auto custom-scrollbar" style="max-height: 40vh;">
                            @foreach($conversation->participants as $user)
                                <div class="d-flex align-items-start gap-2">
                                    <div class="position-relative">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="rounded-circle" width="36" height="36">
                                        <span
                                            class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                            style="width: 10px; height: 10px;"></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small text-body">{{ $user->name }}</div>
                                        <div class="text-secondary small" style="font-size: 10px;">
                                            @if($user->id === $conversation->project->owner_id)
                                                <span class="text-primary fw-bold">Owner</span> •
                                            @endif
                                            {{ $user->major ?? 'Member' }}
                                        </div>
                                    </div>
                                    @if($user->id === auth()->id())
                                        <i class="bi bi-person text-secondary" style="font-size: 12px;"></i>
                                    @endif
                                </div>
                            @endforeach
                            <button class="btn btn-sm btn-outline-secondary w-100 rounded-pill mt-2 small"
                                data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                <i class="bi bi-plus-lg me-1"></i>Add Member
                            </button>
                        </div>
                    </div>

                    <!-- Quick Tasks -->
                    <div class="p-4 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-uppercase small tracking-wide opacity-75 text-body">Quick Tasks</h6>
                            <a href="#" class="text-primary small text-decoration-none fw-bold" style="font-size: 11px;"
                                wire:click.prevent="setTab('tasks')">View All</a>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            @if(isset($quickTasks))
                                @forelse($quickTasks as $task)
                                    <div class="form-check d-flex align-items-center gap-2 ps-0 mb-0">
                                        <input class="form-check-input mt-0 flex-shrink-0" type="checkbox"
                                            wire:click.prevent="setTab('tasks')" style="margin-left: 0;">
                                        <div class="d-flex align-items-center flex-wrap gap-1" role="button"
                                            wire:click.prevent="setTab('tasks')">
                                            <label class="form-check-label text-body small text-truncate"
                                                style="max-width: 140px; cursor: pointer;">
                                                {{ $task->title }}
                                            </label>
                                            @if($task->priority === 'high')
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill px-2 py-0"
                                                    style="font-size: 8px;">HIGH</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 bg-secondary bg-opacity-10 rounded-3">
                                        <i class="bi bi-check2-circle text-secondary fs-4 mb-2 opacity-50"></i>
                                        <p class="text-secondary small mb-0 opacity-75" style="font-size: 11px;">You have no active
                                            tasks.</p>
                                    </div>
                                @endforelse
                            @endif

                            <button
                                class="btn btn-sm btn-link text-primary p-0 text-start mt-2 px-0 text-decoration-none small fw-bold"
                                wire:click.prevent="setTab('tasks')">
                                <i class="bi bi-plus-lg me-1"></i>Create New Task
                            </button>
                        </div>
                    </div>

                @elseif($conversation && $conversation->type === 'direct')
                    @php
                        $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                    @endphp
                    @if($other)
                        <div class="p-5 text-center">
                            <img src="{{ $other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name) }}"
                                class="rounded-circle mb-3 shadow" width="80" height="80">
                            <h5 class="fw-bold text-body">{{ $other->name }}</h5>
                            <p class="text-secondary small">{{ $other->major ?? 'Student' }}</p>

                            <div class="d-grid gap-2 mt-4">
                                <a href="{{ route('profile.show', $other->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill">View Profile</a>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill">Block User</button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    @if($conversation)
        @script
        <script>
            let conversationId = {{ $conversation->id }};
            const channelName = `chat.${conversationId}`;

            // Only setup Echo if it's available
            if (window.Echo) {
                window.Echo.private(channelName)
                    .listen('.message.sent', (e) => {
                        $wire.call('loadMessages');
                    });
            } else {
                console.warn('Echo not initialized - real-time updates disabled');
            }

            Livewire.on('update-url', (data) => {
                const url = data.url;
                window.history.pushState({}, '', url);
            });

            /* Open Image Gallery Functionality could be added here */
            window.openGallery = (attachments, scrollId) => {
                // Placeholder for lightbox logic
                console.log('Open gallery for:', scrollId);
            };
        </script>
        @endscript
    @endif

    @if($conversation && $conversation->type === 'project' && $conversation->project)
        @livewire('project.add-member', ['project' => $conversation->project], key('add-member-' . $conversation->id))
    @endif
</div>