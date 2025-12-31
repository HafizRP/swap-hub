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

        /* Dark Mode Defaults */
        [data-bs-theme="dark"] .bg-sidebar {
            background-color: #0f172a !important;
        }
        [data-bs-theme="dark"] .bg-main {
            background-color: #1e293b !important;
        }

        /* Light Mode Defaults */
        [data-bs-theme="light"] .bg-sidebar {
            background-color: #f8fafc !important;
        }
        [data-bs-theme="light"] .bg-main {
            background-color: #ffffff !important;
        }
        
        /* Message Input & Form Controls */
        #chat-page-component .form-control {
            background-color: rgba(255, 255, 255, 0.05); /* Dark mode default */
            color: var(--bs-body-color);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        [data-bs-theme="light"] #chat-page-component .form-control {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }

        /* Fix Text Colors */
        [data-bs-theme="light"] .text-white {
            color: #1e293b !important;
        }
        [data-bs-theme="light"] .text-white-50 {
            color: #64748b !important;
        }
        [data-bs-theme="light"] .border-white {
            border-color: #e2e8f0 !important;
        }
        
        /* Specific Override for Primary Badge/Button Text */
        [data-bs-theme="light"] .bg-primary .text-white, 
        .btn-primary {
            color: #ffffff !important;
        }
    </style>

    <div id="chat-page-component" class="container-fluid py-0 h-100" style="height: calc(100vh - 74px) !important;">
        <div class="row h-100 g-0">

            <!-- LEFT COLUMN: Conversation List / Channels -->
            <div class="col-12 col-md-4 col-lg-3 border-end border-white border-opacity-10 d-flex flex-column h-100 bg-sidebar {{ $conversation ? 'd-none d-md-flex' : 'd-flex' }}" style="background-color: var(--bs-body-bg);">
                <div class="p-3 border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center">
                     <h6 class="fw-bold mb-0 text-uppercase tracking-wide small opacity-75">Workspaces</h6>
                     <button class="btn btn-sm btn-icon text-secondary"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                </div>
                @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
            </div>

            <!-- MIDDLE COLUMN: Main Chat Area -->
            <div class="col-12 col-md-8 col-lg-6 d-flex flex-column h-100 bg-main border-end border-white border-opacity-10 position-relative {{ $conversation ? 'd-flex' : 'd-none d-md-flex' }}" style="background-color: var(--bs-card-bg);">
                @if($conversation)
                    <!-- Workspace Header -->
                    <div class="px-4 pt-3 pb-0 border-bottom border-white border-opacity-10 flex-shrink-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Mobile Back -->
                                <a href="/chat" wire:navigate class="btn btn-icon btn-text-secondary d-md-none me-1 p-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                </a>

                                @php
                                    $title = $this->getTitle();
                                    $other = ($conversation->type === 'direct') ? $conversation->participants->where('id', '!=', auth()->id())->first() : null;
                                    $avatar = $conversation->type === 'project' 
                                        ? 'https://ui-avatars.com/api/?name=' . urlencode($title) . '&background=4f46e5&color=fff'
                                        : ($other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($title));
                                @endphp

                                <img src="{{ $avatar }}" class="rounded-circle shadow-sm" width="40" height="40">

                                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                    {{ $title }}
                                    @if($conversation->type === 'project')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill small fw-bold px-2 py-0" style="font-size: 10px;">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill small fw-bold px-2 py-0" style="font-size: 10px;">DM</span>
                                    @endif
                                </h5>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 rounded-2">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Settings
                                </button>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs border-0 gap-4" style="margin-bottom: -1px;">
                            <li class="nav-item">
                                <a class="nav-link border-0 bg-transparent px-0 pb-3 fw-bold {{ $activeTab === 'chat' ? 'text-primary border-bottom border-2 border-primary active' : 'text-secondary' }}" href="#" wire:click.prevent="setTab('chat')">Chat</a>
                            </li>
                            @if($conversation->type === 'project')
                                <li class="nav-item">
                                    <a class="nav-link border-0 bg-transparent px-0 pb-3 fw-bold {{ $activeTab === 'tasks' ? 'text-primary border-bottom border-2 border-primary active' : 'text-secondary' }}" href="#" wire:click.prevent="setTab('tasks')">Tasks</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link border-0 bg-transparent px-0 pb-3 fw-medium text-secondary" href="#">Files</a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    @if($activeTab === 'chat')

                    <!-- Messages -->
                    <div class="flex-grow-1 overflow-auto p-4 custom-scrollbar" id="messagesContainer"
                        x-init="$el.scrollTop = $el.scrollHeight"
                        @scroll-to-bottom.window="document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight">
                        
                        @forelse($messages as $msg)
                            @php
                                $isOwn = $msg['user_id'] == auth()->id();
                                $isSystem = !$msg['user_id'];
                            @endphp

                            @if($isSystem)
                                <div class="text-center my-4">
                                     <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3 py-1 fw-normal">
                                        {!! nl2br(e($msg['content'])) !!}
                                     </span>
                                </div>
                            @else
                                <div class="d-flex gap-3 mb-4 {{ $isOwn ? '' : '' }}">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $msg['user_avatar'] }}" class="rounded-circle shadow-sm" width="40" height="40">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-baseline gap-2 mb-1">
                                            <span class="fw-bold {{ $isOwn ? 'text-primary' : 'text-body' }}">{{ $msg['user_name'] }}</span>
                                            <span class="small text-secondary opacity-75" style="font-size: 11px;">{{ $msg['created_at_human'] }}</span>
                                        </div>
                                        
                                        <div class="p-3 rounded-3 {{ $isOwn ? 'bg-primary bg-opacity-10 text-body border border-primary border-opacity-10' : 'bg-secondary bg-opacity-5 text-body border border-secondary border-opacity-10' }}" 
                                             style="width: fit-content; max-width: 90%;">
                                            <p class="mb-0 text-break" style="white-space: pre-line;">{!! nl2br(e($msg['content'])) !!}</p>
                                            
                                            @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                <div class="d-flex flex-wrap gap-2 mt-2">
                                                    @foreach($msg['attachments'] as $att)
                                                        @if(Str::startsWith($att['file_type'], 'image/'))
                                                            <div role="button" onclick="openGallery(@js($msg['attachments']), '{{ $att['id'] }}')">
                                                                <img src="{{ $att['file_path'] }}" class="rounded shadow-sm" style="max-height: 150px; max-width: 100%;">
                                                            </div>
                                                        @else
                                                            <a href="{{ $att['file_path'] }}" target="_blank" class="d-flex align-items-center gap-2 p-2 bg-white rounded border text-decoration-none text-body">
                                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                                                <span class="small">{{ $att['file_name'] }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                             <div class="text-center py-5 opacity-50">
                                 <p class="small text-secondary">No messages yet. Start the conversation!</p>
                             </div>
                        @endforelse

                    </div>

                    <!-- Input Area -->
                    <div class="p-3 border-top border-white border-opacity-10 bg-body">
                        <form wire:submit.prevent="sendMessage">
                             @if(count($attachments) > 0)
                                <div class="d-flex flex-wrap gap-2 mb-2 p-2 bg-secondary bg-opacity-10 rounded">
                                    @foreach($attachments as $index => $att)
                                        <div class="position-relative">
                                            <span class="badge bg-secondary">{{ $att->getClientOriginalName() }}</span>
                                            <button type="button" wire:click="removeAttachment({{ $index }})" class="btn-close btn-close-white position-absolute top-0 end-0 translate-middle p-1 bg-danger rounded-circle" style="width: 16px; height: 16px;"></button>
                                        </div>
                                    @endforeach
                                </div>
                             @endif

                             <div class="input-group">
                                <button type="button" class="btn btn-light border" onclick="document.getElementById('fileInput').click()">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                </button>
                                <input type="file" wire:model="attachments" id="fileInput" class="d-none" multiple>
                                
                                <input type="text" wire:model.live.debounce.250ms="newMessage" class="form-control border-start-0" placeholder="Type a message..." {{ $loading ? 'disabled' : '' }}>
                                <button class="btn btn-primary px-4" type="submit" {{ (empty($newMessage) && empty($attachments)) ? 'disabled' : '' }}>
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                </button>
                             </div>
                        </form>
                        </form>
                    </div>
                    @elseif($activeTab === 'tasks' && $conversation->type === 'project' && $conversation->project)
                         @livewire('project.task-board', ['project' => $conversation->project], key('tasks-' . $conversation->id))
                    @endif

                @else
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                        <h4 class="fw-bold mb-2">Select a Workspace</h4>
                        <p class="text-secondary small">Choose a project or person from the sidebar.</p>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: Context / Widgets -->
            <div class="col-lg-3 border-start border-white border-opacity-10 d-none d-lg-flex flex-column h-100 p-4 custom-scrollbar overflow-auto bg-sidebar" style="background-color: var(--bs-body-bg);">
                @if($conversation && $conversation->type === 'project')
                    <!-- Team Members -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-uppercase small tracking-wide opacity-75">Team Members</h6>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <!-- Participants -->
                            @foreach($conversation->participants as $user)
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="rounded-circle" width="36" height="36">
                                        <!-- Simulate Online dot for now or use real presence if available -->
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 small">{{ $user->name }}</h6>
                                        <p class="small text-secondary mb-0" style="font-size: 10px;">{{ $user->major ?? 'Member' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($conversation && $conversation->type === 'direct')
                    @php
                        $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                    @endphp
                    @if($other)
                        <!-- User Profile -->
                        <div class="text-center mb-5">
                            <img src="{{ $other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name) }}" class="rounded-circle mb-3 shadow" width="80" height="80">
                            <h5 class="fw-bold">{{ $other->name }}</h5>
                            <p class="text-secondary small">{{ $other->major ?? 'Student' }}</p>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <a href="{{ route('profile.show', $other->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">View Profile</a>
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
            
            // Listen for Messages in THIS conversation
            window.Echo.private(channelName)
                .listen('.message.sent', (e) => {
                    console.log('New message in chat ' + conversationId, e);
                    $wire.call('loadMessages');
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('scroll-to-bottom'));
                    }, 100);
                });

            Livewire.on('update-url', (data) => {
                const url = data.url;
                window.history.pushState({}, '', url);
            });
        
            // Handle browser back
             window.addEventListener('popstate', (event) => {
                 window.location.reload(); 
             });
        </script>
        @endscript
    @endif
</div>
