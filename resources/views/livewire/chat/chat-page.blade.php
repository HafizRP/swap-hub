@section('title', $this->getTitle())
 
<div>
    <div id="chat-page-component" class="w-full h-full flex flex-col" style="height: calc(100vh - 74px) !important;" x-data="{ showAddMemberModal: false }">
        <div class="grid grid-cols-12 h-full gap-0">
 
            <!-- LEFT COLUMN: Conversation List -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 border-r border-slate-200 dark:border-slate-800 flex flex-col h-full bg-white dark:bg-slate-900 {{ $conversation ? 'hidden md:flex' : 'flex' }}">
                <div class="p-3.5 px-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-0">Percakapan & Workspace</h3>
                </div>
                @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
            </div>
 
            <!-- MIDDLE COLUMN: Main Chat / Interaction Area -->
            <div class="col-span-12 md:col-span-8 lg:col-span-6 flex flex-col h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 relative {{ $conversation ? 'flex' : 'hidden md:flex' }}">
                @if($conversation)
                    <!-- Header Area -->
                    <div class="px-5 pt-3.5 {{ $conversation->type === 'project' ? 'pb-0' : 'pb-3.5' }} border-b border-slate-200 dark:border-slate-800 shrink-0 bg-slate-50/70 dark:bg-slate-900">
                        <div class="flex justify-between items-center gap-3 mb-2">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Mobile Back -->
                                <a href="/chat" wire:navigate class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 md:hidden mr-1 p-0">
                                    <i class="bi bi-arrow-left text-lg"></i>
                                </a>
 
                                @php
                                    $title = $this->getTitle();
                                    $other = ($conversation->type === 'direct') ? $conversation->participants->where('id', '!=', auth()->id())->first() : null;
                                    $avatar = $conversation->type === 'project'
                                        ? 'https://ui-avatars.com/api/?name=' . urlencode($title) . '&background=6366f1&color=fff'
                                        : ($other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($title) . '&background=6366f1&color=fff');
                                @endphp
 
                                @if($conversation->type === 'direct')
                                    <img src="{{ $avatar }}" class="w-10 h-10 rounded-2xl object-cover border border-slate-200 dark:border-slate-700 shadow-xs" alt="{{ $title }}">
                                @else
                                    <div class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xs shrink-0">
                                        <i class="bi bi-folder-fill text-base"></i>
                                    </div>
                                @endif
 
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h2 class="font-extrabold text-slate-900 dark:text-slate-100 mb-0 truncate text-sm sm:text-base">{{ $title }}</h2>
                                        @if($conversation->type === 'project')
                                            <span class="rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 px-2 py-0.5 text-[9px] font-bold uppercase flex items-center gap-1">
                                                <span class="rounded-full bg-emerald-500 w-1.5 h-1.5 inline-block"></span>Aktif
                                            </span>
                                        @endif
                                    </div>
                                    @if($conversation->type === 'project' && $conversation->project && $conversation->project->github_repo_url)
                                        <a href="{{ $conversation->project->github_repo_url }}" target="_blank"
                                            class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs no-underline flex items-center gap-1 transition-colors">
                                            <i class="bi bi-github"></i>
                                            <span>{{ $conversation->project->github_repo_name ?? 'Repositori' }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
 
                            <div class="flex items-center gap-2">
                                @if($conversation->type === 'project')
                                    <button @click="showAddMemberModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs py-1.5 px-3.5 border-0 transition-all duration-150 active:scale-[0.98] cursor-pointer shadow-xs flex items-center gap-1">
                                        <i class="bi bi-person-plus"></i>
                                        <span class="hidden sm:inline">Undang</span>
                                    </button>
                                @endif
                            </div>
                        </div>
 
                        @if($conversation->type === 'project')
                            <!-- Tabs -->
                            <div class="flex gap-2 border-b border-slate-200 dark:border-slate-800 mt-3" style="margin-bottom: -1px;">
                                <button class="px-3 py-2 font-bold text-xs border-b-2 transition-all duration-150 cursor-pointer {{ $activeTab === 'chat' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}"
                                    wire:click.prevent="setTab('chat')">
                                    <i class="bi bi-chat-dots-fill mr-1.5"></i>Squad Chat
                                </button>
                                <button class="px-3 py-2 font-bold text-xs border-b-2 transition-all duration-150 cursor-pointer {{ $activeTab === 'tasks' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}"
                                    wire:click.prevent="setTab('tasks')">
                                    <i class="bi bi-kanban mr-1.5"></i>Task Board
                                </button>
                                <button class="px-3 py-2 font-bold text-xs border-b-2 transition-all duration-150 cursor-pointer {{ $activeTab === 'files' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}"
                                    wire:click.prevent="setTab('files')">
                                    <i class="bi bi-folder mr-1.5"></i>Berkas
                                </button>
                                @if($conversation->project && $conversation->project->github_repo_url)
                                    <button class="px-3 py-2 font-bold text-xs border-b-2 transition-all duration-150 cursor-pointer {{ $activeTab === 'github' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}"
                                        wire:click.prevent="setTab('github')">
                                        <i class="bi bi-github mr-1.5"></i>GitHub Feed
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
 
                    <!-- TAB CONTENT AREA -->
 
                    @if($activeTab === 'chat')
                        <!-- Chat Messages Area -->
                        <div class="flex-grow overflow-y-auto p-4 sm:p-5 custom-scrollbar bg-slate-50/50 dark:bg-slate-900" id="messagesContainer"
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
                                            <span class="text-[11px] bg-slate-200/70 dark:bg-slate-800 px-3 py-1 rounded-full text-slate-600 dark:text-slate-400 inline-block border border-slate-300/60 dark:border-slate-700">
                                                {!! Str::markdown($msg['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                            </span>
                                        </div>
                                        @php $prevUserId = null; @endphp
                                    @else
                                        <div class="flex items-end gap-2.5 {{ $isOwn ? 'flex-row-reverse' : '' }} {{ $isSameUser ? 'mt-0.5' : 'mt-3' }}">
                                            <!-- Avatar -->
                                            @if(!$isOwn)
                                                @if(!$isSameUser)
                                                    <img src="{{ $msg['user_avatar'] }}" class="w-8 h-8 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0" 
                                                        title="{{ $msg['user_name'] }}" alt="{{ $msg['user_name'] }}">
                                                @else
                                                    <div class="w-8 shrink-0"></div>
                                                @endif
                                            @endif
 
                                            <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }}" style="max-width: 75%;">
                                                
                                                <!-- Name -->
                                                @if(!$isOwn && !$isSameUser)
                                                    <span class="text-slate-400 dark:text-slate-500 font-bold ml-1 mb-1 text-[10px]">{{ $msg['user_name'] }}</span>
                                                @endif
 
                                                <!-- Message Bubble -->
                                                @if(!empty(trim($msg['content'])))
                                                    <div class="shadow-xs rounded-2xl p-3 text-xs leading-relaxed {{ $isOwn ? 'bg-indigo-600 text-white rounded-br-xs' : 'bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 text-slate-800 dark:text-slate-100 rounded-bl-xs' }}">
                                                        {!! Str::markdown($msg['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                                    </div>
                                                @endif
 
                                                <!-- Attachments -->
                                                @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                    <div class="flex flex-wrap gap-2 mt-1.5 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                                        @foreach($msg['attachments'] as $att)
                                                            @if(Str::startsWith($att['file_type'], 'image/'))
                                                                <div role="button" onclick="openGallery(@js($msg['attachments']), '{{ $att['id'] }}')" 
                                                                     class="overflow-hidden rounded-xl shadow-xs border border-slate-200 dark:border-slate-700 transition hover-lift shrink-0 cursor-pointer">
                                                                    <img src="{{ $att['file_path'] }}"
                                                                         class="block max-h-48 max-w-full object-cover" alt="Attachment">
                                                                </div>
                                                            @else
                                                                <a href="{{ $att['file_path'] }}" target="_blank"
                                                                    class="flex items-center gap-2 p-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 no-underline text-slate-800 dark:text-slate-200 shadow-xs hover-lift transition">
                                                                    <div class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg p-1.5">
                                                                        <i class="bi bi-file-earmark-text text-base"></i>
                                                                    </div>
                                                                    <span class="text-xs font-semibold">{{ $att['file_name'] }}</span>
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
 
                                                <!-- Meta: Time -->
                                                 <div class="mt-1 flex items-center gap-2 text-[10px] text-slate-400 dark:text-slate-500 px-1"> 
                                                     <span x-data="{ date: new Date('{{ $msg['created_at'] }}') }" 
                                                           x-text="date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false })"></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center text-center py-16">
                                        <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-3 text-2xl">
                                            <i class="bi bi-chat-heart"></i>
                                        </div>
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-0.5">Belum Ada Pesan</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-0">Mulai diskusi pertama dengan squad proyek Anda!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
 
                        <!-- Input Area -->
                        <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                            <form wire:submit.prevent="sendMessage">
                                @if(count($attachments) > 0)
                                    <div class="flex flex-wrap gap-2 mb-2 p-2 bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                        @foreach($attachments as $index => $att)
                                            <div class="relative bg-white dark:bg-slate-700 rounded-lg px-2.5 py-1 text-xs text-slate-700 dark:text-slate-200 flex items-center gap-2 border border-slate-200 dark:border-slate-600">
                                                <span class="truncate max-w-[150px]">{{ $att->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="removeAttachment({{ $index }})"
                                                    class="text-red-500 hover:text-red-700 font-bold bg-transparent border-0 p-0 cursor-pointer">
                                                    ✕
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
 
                                <div class="flex items-center bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-600 overflow-hidden transition-all">
                                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 pl-3.5 pr-2 bg-transparent border-0 outline-none cursor-pointer"
                                        onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-paperclip text-lg"></i>
                                    </button>
                                    <input type="file" wire:model="attachments" id="fileInput" class="hidden" multiple>
 
                                    <input type="text" wire:model.live.debounce.250ms="newMessage"
                                        class="flex-1 w-full bg-transparent border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none px-2 h-11 text-xs"
                                        placeholder="Ketik pesan untuk {{ $conversation->type === 'project' ? '#general' : $title }}..."
                                        {{ $loading ? 'disabled' : '' }}>
 
                                    <button class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 text-white rounded-xl flex items-center justify-center transition-all active:scale-95 shrink-0 m-1 w-9 h-9 border-0 cursor-pointer"
                                        type="submit"
                                        {{ (empty($newMessage) && count($attachments) === 0) || $loading ? 'disabled' : '' }}>
                                        <i class="bi bi-send-fill text-white text-xs"></i>
                                    </button>
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
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 bg-white dark:bg-slate-900">
                        <div class="w-16 h-16 rounded-3xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 text-3xl">
                            <i class="bi bi-chat-quote-fill"></i>
                        </div>
                        <h3 class="font-extrabold mb-1 text-base text-slate-900 dark:text-slate-100">Pilih Percakapan</h3>
                        <p class="text-slate-400 dark:text-slate-500 text-xs">Pilih room proyek atau pesan langsung dari daftar di sebelah kiri.</p>
                    </div>
                @endif
            </div>
 
            <!-- RIGHT COLUMN: Context Info -->
            <div class="col-span-12 lg:col-span-3 border-l border-slate-200 dark:border-slate-800 hidden lg:flex flex-col h-full bg-white dark:bg-slate-900">
                @if($conversation && $conversation->type === 'project')
                    <!-- Team Members Info -->
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Anggota Tim</span>
                            <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-bold rounded-full px-2 py-0.5">{{ $conversation->participants->count() }}</span>
                        </div>
                        <div class="flex flex-col gap-2.5 overflow-y-auto custom-scrollbar" style="max-height: 40vh;">
                            @foreach($conversation->participants as $user)
                                <div class="flex items-center gap-2.5">
                                    <div class="relative shrink-0">
                                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                            class="w-8 h-8 rounded-xl object-cover border border-slate-200 dark:border-slate-700" alt="{{ $user->name }}">
                                        <span class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 border border-white dark:border-slate-900 rounded-full w-2.5 h-2.5"></span>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="font-bold text-xs text-slate-900 dark:text-slate-100 truncate">{{ $user->name }}</div>
                                        <div class="text-slate-400 text-[10px] truncate">
                                            @if($user->id === $conversation->project->owner_id)
                                                <span class="text-indigo-600 dark:text-indigo-400 font-bold">Owner</span> •
                                            @endif
                                            {{ $user->major ?? 'Member' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button @click="showAddMemberModal = true" class="border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 w-full rounded-xl mt-2 py-2 text-xs font-bold transition-colors cursor-pointer bg-transparent">
                                <i class="bi bi-person-plus mr-1"></i>Undang Anggota
                            </button>
                        </div>
                    </div>
 
                    <!-- Quick Tasks -->
                    <div class="p-4 flex-grow">
                        <div class="flex justify-between items-center mb-3">
                            <span class="font-extrabold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Tugas Cepat</span>
                            <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold no-underline hover:underline"
                                wire:click.prevent="setTab('tasks')">Buka Board</a>
                        </div>
 
                        <div class="flex flex-col gap-2">
                            @if(isset($quickTasks))
                                @forelse($quickTasks as $task)
                                    <div class="flex items-center gap-2">
                                        <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 shrink-0" type="checkbox"
                                            wire:click.prevent="setTab('tasks')">
                                        <div class="flex items-center flex-wrap gap-1 min-w-0 cursor-pointer"
                                            wire:click.prevent="setTab('tasks')">
                                            <span class="text-xs text-slate-700 dark:text-slate-300 truncate max-w-[140px]">
                                                {{ $task->title }}
                                            </span>
                                            @if($task->priority === 'high')
                                                <span class="bg-red-50 dark:bg-red-950/40 text-red-600 border border-red-200/60 rounded px-1 text-[8px] font-bold">HIGH</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                        <i class="bi bi-check2-circle text-slate-400 text-base mb-1 block"></i>
                                        <p class="text-slate-400 dark:text-slate-500 text-[10px] mb-0">Semua tugas beres.</p>
                                    </div>
                                @endforelse
                            @endif
 
                            <button class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-bold text-left mt-2 bg-transparent border-0 p-0 cursor-pointer"
                                wire:click.prevent="setTab('tasks')">
                                <i class="bi bi-plus-lg mr-1"></i>Buat Tugas Baru
                            </button>
                        </div>
                    </div>
 
                @elseif($conversation && $conversation->type === 'direct')
                    @php
                        $other = $conversation->participants->where('id', '!=', auth()->id())->first();
                    @endphp
                    @if($other)
                        <div class="p-6 text-center">
                            <img src="{{ $other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name) . '&background=6366f1&color=fff' }}"
                                class="w-16 h-16 rounded-2xl mb-3 shadow-sm mx-auto object-cover border border-slate-200 dark:border-slate-700" alt="{{ $other->name }}">
                            <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm mb-0.5">{{ $other->name }}</h4>
                            <p class="text-slate-400 dark:text-slate-500 text-xs mb-4">{{ $other->major ?? 'Mahasiswa' }}</p>
 
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('profile.show', $other->id) }}"
                                    class="block text-center w-full bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 font-bold py-2 px-4 rounded-xl text-xs transition-colors no-underline border border-indigo-200/60 dark:border-indigo-800/60">
                                    Lihat Profil
                                </a>
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