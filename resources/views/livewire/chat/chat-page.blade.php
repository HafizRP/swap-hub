@section('title', $this->getTitle())
 
<div>
    <div id="chat-page-component" class="w-full h-full flex flex-col" style="height: calc(100vh - 74px) !important;" x-data="{ showAddMemberModal: false }">
        <div class="grid grid-cols-12 h-full gap-0">
 
            <!-- LEFT COLUMN: Conversation List -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 border-r border-slate-200 dark:border-slate-700 flex flex-col h-full bg-white dark:bg-slate-800 {{ $conversation ? 'hidden md:flex' : 'flex' }}">
                <div class="p-4 border-b border-slate-205 border-slate-200 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800">
                    <h6 class="font-bold text-xs uppercase tracking-wider text-slate-450 dark:text-slate-500 mb-0">Workspaces</h6>
                    <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-transparent border-0"><i class="bi bi-plus-lg"></i></button>
                </div>
                @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
            </div>
 
            <!-- MIDDLE COLUMN: Main Chat / Interaction Area -->
            <div class="col-span-12 md:col-span-8 lg:col-span-6 flex flex-col h-full bg-white dark:bg-slate-800 border-r border-slate-202 border-slate-200 dark:border-slate-700 relative {{ $conversation ? 'flex' : 'hidden md:flex' }}">
                @if($conversation)
                    <!-- Header Area -->
                    <div class="px-5 pt-4 {{ $conversation->type === 'project' ? 'pb-0' : 'pb-4' }} border-b border-slate-200 dark:border-slate-700 shrink-0 bg-slate-50 dark:bg-slate-800/60">
                        <div class="flex justify-between items-center gap-3 mb-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Mobile Back -->
                                <a href="/chat" wire:navigate class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 md:hidden mr-1 p-0">
                                    <i class="bi bi-arrow-left text-xl"></i>
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
                                    <div class="bg-indigo-600 rounded-lg flex items-center justify-center text-white shadow-sm shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-folder-fill text-lg"></i>
                                    </div>
                                @endif
 
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h5 class="font-bold text-slate-850 dark:text-slate-100 mb-0 truncate text-base">{{ $title }}</h5>
                                        @if($conversation->type === 'project')
                                            <span class="rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-450 px-2 py-0.5 text-[9px] font-bold uppercase flex items-center gap-1">
                                                <span class="rounded-full bg-emerald-500 w-1.5 h-1.5 inline-block"></span>Active
                                            </span>
                                        @endif
                                    </div>
                                    @if($conversation->type === 'project' && $conversation->project && $conversation->project->github_repo_url)
                                        <a href="{{ $conversation->project->github_repo_url }}" target="_blank"
                                            class="text-slate-450 dark:text-slate-500 text-xs no-underline hover:text-indigo-650 flex items-center gap-1">
                                            <i class="bi bi-github"></i>{{ $conversation->project->github_repo_name ?? 'Repository' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
 
                            <div class="flex gap-2">
                                <button class="border border-slate-205 border-slate-200 dark:border-slate-700 text-slate-750 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 rounded-full font-bold text-xs py-1.5 px-4 transition-colors">
                                    <i class="bi bi-gear-fill mr-1"></i>Settings
                                </button>
                                @if($conversation->type === 'project')
                                    <button @click="showAddMemberModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-bold text-xs py-1.5 px-4 border-0 transition-colors">
                                        <i class="bi bi-plus-lg mr-1"></i>Invite
                                    </button>
                                @endif
                            </div>
                        </div>
 
                        @if($conversation->type === 'project')
                            <!-- Tabs -->
                            <div class="flex gap-4 border-b border-slate-200 dark:border-slate-700 mt-4" style="margin-bottom: -1px;">
                                <button class="px-4 py-2 font-bold text-sm border-b-2 transition-all duration-150 {{ $activeTab === 'chat' ? 'border-indigo-600 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-indigo-650' }}"
                                    wire:click.prevent="setTab('chat')">
                                    <i class="bi bi-chat-dots-fill mr-2"></i>Project Chat
                                </button>
                                <button class="px-4 py-2 font-bold text-sm border-b-2 transition-all duration-150 {{ $activeTab === 'tasks' ? 'border-indigo-600 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-indigo-650' }}"
                                    wire:click.prevent="setTab('tasks')">
                                    <i class="bi bi-kanban mr-2"></i>Task Board
                                </button>
                                <button class="px-4 py-2 font-bold text-sm border-b-2 transition-all duration-150 {{ $activeTab === 'files' ? 'border-indigo-600 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-indigo-650' }}"
                                    wire:click.prevent="setTab('files')">
                                    <i class="bi bi-folder mr-2"></i>Files
                                </button>
                                @if($conversation->project && $conversation->project->github_repo_url)
                                    <button class="px-4 py-2 font-bold text-sm border-b-2 transition-all duration-150 {{ $activeTab === 'github' ? 'border-indigo-600 text-indigo-650 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-indigo-650' }}"
                                        wire:click.prevent="setTab('github')">
                                        <i class="bi bi-github mr-2"></i>GitHub Feed
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
 
                    <!-- TAB CONTENT AREA -->
 
                    @if($activeTab === 'chat')
                        <!-- Chat Messages Area -->
                        <div class="flex-grow overflow-auto p-4 custom-scrollbar bg-slate-50 dark:bg-slate-900" id="messagesContainer"
                            x-init="$el.scrollTop = $el.scrollHeight"
                            @scroll-to-bottom.window="document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight">
                            
                            <div class="flex flex-col gap-1">
                                @php $prevUserId = null; @endphp
                                @forelse($messages as $msg)
                                    @php
                                        $isOwn = $msg['user_id'] == auth()->id();
                                        $isSystem = !$msg['user_id'];
                                        $isSameUser = $prevUserId === $msg['user_id'];
                                        $prevUserId = $msg['user_id'];
                                    @endphp
 
                                    @if($isSystem)
                                        <div class="text-center my-3">
                                            <span class="text-xs bg-slate-200 dark:bg-slate-800 px-3.5 py-1 rounded-full text-slate-500 dark:text-slate-400 inline-block border border-slate-300 dark:border-slate-700">
                                                {!! Str::markdown($msg['content']) !!}
                                            </span>
                                        </div>
                                        @php $prevUserId = null; @endphp {{-- Reset grouping after system msg --}}
                                    @else
                                        <div class="flex items-end gap-2.5 {{ $isOwn ? 'flex-row-reverse' : '' }} {{ $isSameUser ? 'mt-0.5' : 'mt-3' }}">
                                            <!-- Avatar -->
                                            @if(!$isOwn)
                                                @if(!$isSameUser)
                                                    <img src="{{ $msg['user_avatar'] }}" class="rounded-circle shadow-sm shrink-0" width="32" height="32" 
                                                        title="{{ $msg['user_name'] }}" style="margin-bottom: 2px;">
                                                @else
                                                    <div style="width: 32px;" class="shrink-0"></div>
                                                @endif
                                            @endif
 
                                            <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }}" style="max-width: 75%;">
                                                
                                                <!-- Name -->
                                                @if(!$isOwn && !$isSameUser)
                                                    <span class="text-slate-400 dark:text-slate-500 font-bold ml-1 mb-1" style="font-size: 10px;">{{ $msg['user_name'] }}</span>
                                                @endif
 
                                                <!-- Message Bubble -->
                                                @if(!empty(trim($msg['content'])))
                                                    <div class="shadow-sm rounded-2xl p-3 text-sm {{ $isOwn ? 'bg-indigo-600 text-white rounded-br-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 rounded-bl-sm' }}"
                                                         style="{{ $isSameUser ? ($isOwn ? 'border-top-right-radius: 4px;' : 'border-top-left-radius: 4px;') : '' }}">
                                                        {!! Str::markdown($msg['content']) !!}
                                                    </div>
                                                @endif
 
                                                <!-- Attachments -->
                                                @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                    <div class="flex flex-wrap gap-2 mt-1.5 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                                        @foreach($msg['attachments'] as $att)
                                                            @if(Str::startsWith($att['file_type'], 'image/'))
                                                                <div role="button" onclick="openGallery(@js($msg['attachments']), '{{ $att['id'] }}')" 
                                                                     class="overflow-hidden rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 transition hover-lift shrink-0">
                                                                    <img src="{{ $att['file_path'] }}"
                                                                         class="block"
                                                                         style="max-height: 200px; max-width: 100%; object-fit: cover;">
                                                                </div>
                                                            @else
                                                                <a href="{{ $att['file_path'] }}" target="_blank"
                                                                    class="flex items-center gap-2 p-2 bg-white dark:bg-slate-850 rounded-lg border border-slate-200 dark:border-slate-700 no-underline text-slate-800 dark:text-slate-200 shadow-sm hover-lift transition">
                                                                    <div class="bg-slate-50 dark:bg-slate-800 rounded p-1.5">
                                                                        <i class="bi bi-file-earmark-text text-lg text-indigo-600 dark:text-indigo-400"></i>
                                                                    </div>
                                                                    <span class="text-xs font-semibold">{{ $att['file_name'] }}</span>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
 
                                                <!-- Meta: Time -->
                                                 <div class="mt-1 flex items-center gap-2 text-[10px] {{ $isOwn ? 'justify-end' : 'justify-start flex-row-reverse' }} px-1 opacity-55 text-slate-400 dark:text-slate-500"> 
                                                     <span x-data="{ date: new Date('{{ $msg['created_at'] }}') }" 
                                                           x-text="date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false })"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="h-full flex flex-col items-center justify-content-center text-center opacity-50 py-12">
                                        <div class="bg-slate-200 dark:bg-slate-800 p-4 rounded-full mb-3 text-slate-450 dark:text-slate-500">
                                            <i class="bi bi-chat-heart text-3xl"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider mb-1">No messages yet.</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-600">Start the conversation with your team!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
 
                        <!-- Input Area -->
                        <div class="p-3.5 border-t border-slate-202 border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40">
                            <form wire:submit.prevent="sendMessage">
                                @if(count($attachments) > 0)
                                    <div class="flex flex-wrap gap-2 mb-2 p-2 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                                        @foreach($attachments as $index => $att)
                                            <div class="relative bg-slate-200 dark:bg-slate-700 rounded-full px-3 py-1 text-xs text-slate-700 dark:text-slate-200 flex items-center gap-2">
                                                <span class="truncate max-w-[150px]">{{ $att->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                                    class="text-red-500 hover:text-red-700 font-bold bg-transparent border-0 p-0 cursor-pointer">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
 
                                <div class="flex items-center bg-white dark:bg-slate-800 rounded-full shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 pl-4 pr-2 bg-transparent border-0 outline-none"
                                        onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-paperclip text-lg"></i>
                                    </button>
                                    <input type="file" wire:model="attachments" id="fileInput" class="hidden" multiple>
 
                                    <input type="text" wire:model.live.debounce.250ms="newMessage"
                                        class="flex-1 w-full bg-transparent border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none px-2 h-12 text-sm"
                                        placeholder="Type a message to {{ $conversation->type === 'project' ? '#general' : $title }}..."
                                        {{ $loading ? 'disabled' : '' }}>
 
                                    <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 px-2 bg-transparent border-0 outline-none" type="button">
                                        <i class="bi bi-emoji-smile text-lg"></i>
                                    </button>
                                    <button class="bg-indigo-650 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-full flex items-center justify-center transition-transform active:scale-95 shrink-0 m-1"
                                        style="width: 40px; height: 40px;" type="submit"
                                        {{ (empty($newMessage) && count($attachments) === 0) || $loading ? 'disabled' : '' }}>
                                        <i class="bi bi-send-fill text-white text-sm"></i>
                                    </button>
                                </div>
                                <div class="text-right mt-2 mr-2">
                                    <small class="text-slate-400 dark:text-slate-500 text-[10px] opacity-75">
                                        Press <span class="font-bold">Enter</span> to send
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
                    <div class="h-full flex flex-col items-center justify-content-center text-center p-6 bg-white dark:bg-slate-800">
                        <div class="bg-slate-100 dark:bg-slate-700/50 p-4 rounded-full mb-3 text-slate-450 dark:text-slate-500">
                            <i class="bi bi-chat-quote-fill text-3xl"></i>
                        </div>
                        <h4 class="font-bold mb-2 text-base text-slate-800 dark:text-slate-100">Select a Conversation</h4>
                        <p class="text-slate-500 dark:text-slate-400 text-xs">Choose a project workspace or direct message from the sidebar.</p>
                    </div>
                @endif
            </div>
 
            <!-- RIGHT COLUMN: Context Info -->
            <div class="col-span-12 lg:col-span-3 border-l border-slate-205 border-slate-200 dark:border-slate-700 hidden lg:flex flex-col h-full bg-white dark:bg-slate-800">
                @if($conversation && $conversation->type === 'project')
                    <!-- Team Members Info -->
                    <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex justify-between items-center mb-3">
                            <h6 class="font-bold text-xs uppercase tracking-wider text-slate-450 dark:text-slate-500 mb-0">Team Members</h6>
                            <span class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-350 text-[10px] font-bold rounded-full px-2 py-0.5">{{ $conversation->participants->count() }}</span>
                        </div>
                        <div class="flex flex-col gap-3 overflow-auto custom-scrollbar" style="max-height: 40vh;">
                            @foreach($conversation->participants as $user)
                                <div class="flex items-start gap-2.5">
                                    <div class="relative shrink-0">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                            class="rounded-circle" width="36" height="36">
                                        <span class="absolute bottom-0 right-0 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"
                                            style="width: 10px; height: 10px;"></span>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="font-bold text-xs text-slate-850 dark:text-slate-100 truncate">{{ $user->name }}</div>
                                        <div class="text-slate-400 dark:text-slate-500 text-[10px] truncate mt-0.5">
                                            @if($user->id === $conversation->project->owner_id)
                                                <span class="text-indigo-650 dark:text-indigo-400 font-bold">Owner</span> •
                                            @endif
                                            {{ $user->major ?? 'Member' }}
                                        </div>
                                    </div>
                                    @if($user->id === auth()->id())
                                        <i class="bi bi-person text-slate-400" style="font-size: 12px;"></i>
                                    @endif
                                </div>
                            @endforeach
                            <button @click="showAddMemberModal = true" class="border border-slate-205 border-slate-200 dark:border-slate-700 text-slate-750 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 w-full rounded-full mt-3 py-2 text-xs font-bold transition-colors">
                                <i class="bi bi-plus-lg mr-1"></i>Add Member
                            </button>
                        </div>
                    </div>
 
                    <!-- Quick Tasks -->
                    <div class="p-4 flex-grow">
                        <div class="flex justify-between items-center mb-3">
                            <h6 class="font-bold text-xs uppercase tracking-wider text-slate-450 dark:text-slate-500 mb-0">Quick Tasks</h6>
                            <a href="#" class="text-xs text-indigo-650 dark:text-indigo-400 font-bold no-underline"
                                wire:click.prevent="setTab('tasks')">View All</a>
                        </div>
 
                        <div class="flex flex-col gap-2">
                            @if(isset($quickTasks))
                                @forelse($quickTasks as $task)
                                    <div class="flex items-center gap-2 mb-0">
                                        <input class="rounded border-slate-305 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 mt-0 shrink-0" type="checkbox"
                                            wire:click.prevent="setTab('tasks')">
                                        <div class="flex items-center flex-wrap gap-1 min-w-0" role="button"
                                            wire:click.prevent="setTab('tasks')">
                                            <label class="text-xs text-slate-800 dark:text-slate-200 truncate cursor-pointer"
                                                style="max-width: 140px;">
                                                {{ $task->title }}
                                            </label>
                                            @if($task->priority === 'high')
                                                <span class="bg-red-500/10 text-red-500 border border-red-500/20 rounded px-1 text-[8px] font-bold">HIGH</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 bg-slate-50 dark:bg-slate-700/20 rounded-xl">
                                        <i class="bi bi-check2-circle text-slate-400 text-lg mb-2 opacity-50 block"></i>
                                        <p class="text-slate-400 dark:text-slate-500 text-[10px] mb-0">You have no active tasks.</p>
                                    </div>
                                @endforelse
                            @endif
 
                            <button class="text-indigo-650 hover:text-indigo-700 text-xs font-bold text-left mt-2 bg-transparent border-0 p-0 outline-none"
                                wire:click.prevent="setTab('tasks')">
                                <i class="bi bi-plus-lg mr-1"></i>Create New Task
                            </button>
                        </div>
                    </div>
 
                @elseif($conversation && $conversation->type === 'direct')
                    @php
                        $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                    @endphp
                    @if($other)
                        <div class="p-6 text-center">
                            <img src="{{ $other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name) }}"
                                class="rounded-circle mb-3 shadow mx-auto" width="80" height="80">
                            <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base">{{ $other->name }}</h5>
                            <p class="text-slate-450 dark:text-slate-500 text-xs">{{ $other->major ?? 'Student' }}</p>
 
                            <div class="flex flex-col gap-2 mt-5">
                                <a href="{{ route('profile.show', $other->id) }}"
                                    class="block text-center w-full border border-indigo-650 text-indigo-655 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 font-bold py-2 px-4 rounded-full text-xs transition-colors no-underline">View Profile</a>
                                <button class="w-full border border-slate-205 border-slate-200 dark:border-slate-700 text-slate-750 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 font-bold py-2 px-4 rounded-full text-xs transition-colors bg-transparent">Block User</button>
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
 
            window.openGallery = (attachments, scrollId) => {
                console.log('Open gallery for:', scrollId);
            };
        </script>
        @endscript
    @endif
 
    @if($conversation && $conversation->type === 'project' && $conversation->project)
        @livewire('project.add-member', ['project' => $conversation->project], key('add-member-' . $conversation->id))
    @endif
</div>