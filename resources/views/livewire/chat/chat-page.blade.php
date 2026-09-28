@section('title', $this->getTitle())

<div>
    <div id="chat-page-component" class="w-full h-[calc(100vh-4.5rem)] flex flex-col rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden" x-data="{ showAddMemberModal: false }">
        <div class="grid grid-cols-12 h-full gap-0">

            <!-- LEFT COLUMN: Conversation List -->
            <div class="col-span-12 md:col-span-4 lg:col-span-3 border-r border-slate-200/80 dark:border-slate-800 flex flex-col h-full bg-white dark:bg-slate-900 {{ $conversation ? 'hidden md:flex' : 'flex' }}">
                <div class="p-3.5 px-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <h3 class="font-black text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Pesan & Workspace
                    </h3>
                </div>
                @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
            </div>

            <!-- MIDDLE COLUMN: Main Chat / Interaction Area -->
            <div class="col-span-12 md:col-span-8 lg:col-span-6 flex flex-col h-full bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 relative {{ $conversation ? 'flex' : 'hidden md:flex' }}">
                @if($conversation)
                    <!-- Header Area -->
                    <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 shrink-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
                        <div class="flex justify-between items-center gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Mobile Back Button -->
                                <a href="/chat" wire:navigate class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 md:hidden p-1">
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
                                    <img src="{{ $avatar }}" alt="{{ $title }}" class="w-10 h-10 rounded-xl object-cover shadow-sm ring-2 ring-slate-100 dark:ring-slate-800">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-950/50 text-brand-600 flex items-center justify-center font-black text-sm shrink-0 border border-brand-200/60 dark:border-brand-800/40">
                                        <i class="bi bi-folder-fill"></i>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h2 class="font-black text-slate-900 dark:text-white truncate text-sm">
                                            {{ $title }}
                                        </h2>
                                        @if($conversation->type === 'project')
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                Active
                                            </span>
                                        @endif
                                    </div>
                                    @if($conversation->type === 'project' && $conversation->project && $conversation->project->github_repo_url)
                                        <a href="{{ $conversation->project->github_repo_url }}" target="_blank"
                                           class="text-[11px] font-bold text-slate-400 hover:text-brand-600 flex items-center gap-1 truncate">
                                            <i class="bi bi-github"></i>
                                            <span>{{ $conversation->project->github_repo_name ?? 'GitHub Repo' }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($conversation->type === 'project')
                                    <button @click="showAddMemberModal = true" type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-sm">
                                        <i class="bi bi-person-plus-fill"></i>
                                        <span class="hidden sm:inline">Undang</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($conversation->type === 'project')
                            <!-- Workspace Navigation Tabs -->
                            <div class="flex gap-2 pt-3 border-t border-slate-100 dark:border-slate-800 mt-3 overflow-x-auto custom-scrollbar">
                                <button class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 {{ $activeTab === 'chat' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                        wire:click.prevent="setTab('chat')">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <span>Chat Tim</span>
                                </button>
                                <button class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 {{ $activeTab === 'tasks' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                        wire:click.prevent="setTab('tasks')">
                                    <i class="bi bi-kanban"></i>
                                    <span>Papan Tugas</span>
                                </button>
                                <button class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 {{ $activeTab === 'files' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                        wire:click.prevent="setTab('files')">
                                    <i class="bi bi-folder2-open"></i>
                                    <span>Berkas</span>
                                </button>
                                @if($conversation->project && $conversation->project->github_repo_url)
                                    <button class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 {{ $activeTab === 'github' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}"
                                            wire:click.prevent="setTab('github')">
                                        <i class="bi bi-github"></i>
                                        <span>GitHub</span>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- TAB CONTENT AREA -->
                    @if($activeTab === 'chat')
                        <!-- Chat Messages Container -->
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-slate-50/50 dark:bg-slate-900/50 space-y-3" id="messagesContainer"
                             x-init="$el.scrollTop = $el.scrollHeight"
                             @scroll-to-bottom.window="document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight">
                            
                            @forelse($messages as $msg)
                                @php
                                    $isOwn = $msg['user_id'] == auth()->id();
                                    $isSystem = !$msg['user_id'];
                                @endphp

                                @if($isSystem)
                                    <div class="text-center my-2">
                                        <span class="text-[11px] bg-slate-200/80 dark:bg-slate-800 px-3 py-1 rounded-full text-slate-500 dark:text-slate-400 inline-block">
                                            {!! Str::markdown($msg['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-end gap-2.5 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                        @if(!$isOwn)
                                            <img src="{{ $msg['user_avatar'] }}" alt="{{ $msg['user_name'] }}"
                                                 class="w-7 h-7 rounded-lg object-cover shadow-sm shrink-0 mb-1"
                                                 title="{{ $msg['user_name'] }}">
                                        @endif

                                        <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }} max-w-[80%]">
                                            @if(!$isOwn)
                                                <span class="text-[10px] font-bold text-slate-400 ml-1 mb-0.5">{{ $msg['user_name'] }}</span>
                                            @endif

                                            @if(!empty(trim($msg['content'])))
                                                <div class="p-3 rounded-2xl text-xs leading-relaxed shadow-sm {{ $isOwn ? 'bg-brand-600 text-white rounded-br-none' : 'bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-900 dark:text-slate-100 rounded-bl-none' }}">
                                                    {!! Str::markdown($msg['content'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                                </div>
                                            @endif

                                            <!-- Attachments -->
                                            @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                <div class="flex flex-wrap gap-2 mt-1.5 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                                                    @foreach($msg['attachments'] as $att)
                                                        @if(Str::startsWith($att['file_type'], 'image/'))
                                                            <div class="overflow-hidden rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 max-w-[200px]">
                                                                <img src="{{ $att['file_path'] }}" alt="{{ $att['file_name'] }}" class="w-full h-auto object-cover">
                                                            </div>
                                                        @else
                                                            <a href="{{ $att['file_path'] }}" target="_blank"
                                                               class="flex items-center gap-2 p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 shadow-sm">
                                                                <i class="bi bi-file-earmark-text text-brand-600"></i>
                                                                <span class="truncate max-w-[120px]">{{ $att['file_name'] }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="text-[9px] text-slate-400 mt-1 px-1"
                                                 x-data="{ date: new Date('{{ $msg['created_at'] }}') }"
                                                 x-text="date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 py-16 space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-brand-600 text-xl">
                                        <i class="bi bi-chat-dots-fill"></i>
                                    </div>
                                    <p class="text-xs font-bold">Belum ada pesan.</p>
                                    <p class="text-[11px]">Mulai obrolan pertama dengan rekan tim Anda!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Chat Input Box -->
                        <div class="p-3.5 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                            <form wire:submit.prevent="sendMessage" class="space-y-2">
                                @if(count($attachments) > 0)
                                    <div class="flex flex-wrap gap-2 p-2 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700">
                                        @foreach($attachments as $index => $att)
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-white dark:bg-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600">
                                                <span class="truncate max-w-[140px]">{{ $att->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="removeAttachment({{ $index }})" class="text-rose-500 hover:text-rose-700">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-2xl px-3 py-1.5 border border-transparent focus-within:border-brand-500 focus-within:ring-2 focus-within:ring-brand-500/20">
                                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1"
                                            onclick="document.getElementById('fileInput').click()">
                                        <i class="bi bi-paperclip text-lg"></i>
                                    </button>
                                    <input type="file" wire:model="attachments" id="fileInput" class="hidden" multiple>

                                    <input type="text" wire:model.live.debounce.250ms="newMessage"
                                           class="flex-1 bg-transparent border-none text-xs text-slate-900 dark:text-white placeholder-slate-400 outline-none focus:ring-0 py-2"
                                           placeholder="Ketik pesan..."
                                           {{ $loading ? 'disabled' : '' }}>

                                    <button type="submit"
                                            class="w-9 h-9 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-40 text-white flex items-center justify-center transition-all shadow-md shadow-brand-500/20"
                                            {{ (empty($newMessage) && count($attachments) === 0) || $loading ? 'disabled' : '' }}>
                                        <i class="bi bi-send-fill text-xs"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    @elseif($activeTab === 'tasks' && $conversation->type === 'project' && $conversation->project)
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                            @livewire('project.task-board', ['project' => $conversation->project], key('tasks-' . $conversation->id))
                        </div>

                    @elseif($activeTab === 'files' && $conversation->type === 'project' && $conversation->project)
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                            @livewire('project.file-browser', ['project' => $conversation->project], key('files-' . $conversation->id))
                        </div>

                    @elseif($activeTab === 'github' && $conversation->type === 'project' && $conversation->project)
                        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                            @livewire('project.github-feed', ['project' => $conversation->project], key('github-' . $conversation->id))
                        </div>
                    @endif

                @else
                    <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-white dark:bg-slate-900 space-y-3">
                        <div class="w-16 h-16 rounded-3xl bg-brand-50 dark:bg-brand-950/50 flex items-center justify-center text-brand-600 text-2xl">
                            <i class="bi bi-chat-square-text-fill"></i>
                        </div>
                        <h3 class="font-black text-slate-900 dark:text-white text-base">Pilih Obrolan</h3>
                        <p class="text-xs text-slate-400 max-w-xs">Pilih ruang kolaborasi proyek atau obrolan langsung dari sidebar untuk memulai percakapan.</p>
                    </div>
                @endif
            </div>

            <!-- RIGHT COLUMN: Context Info & Team -->
            <div class="col-span-12 lg:col-span-3 border-l border-slate-200/80 dark:border-slate-800 hidden lg:flex flex-col h-full bg-white dark:bg-slate-900">
                @if($conversation && $conversation->type === 'project')
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-black text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Anggota Tim ({{ $conversation->participants->count() }})
                            </h3>
                            <button @click="showAddMemberModal = true" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                                + Tambah
                            </button>
                        </div>

                        <div class="space-y-2.5 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($conversation->participants as $user)
                                <div class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff' }}"
                                         alt="{{ $user->name }}"
                                         class="w-8 h-8 rounded-xl object-cover">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate">
                                            @if($user->id === $conversation->project->owner_id)
                                                <span class="text-brand-600 font-bold">Owner</span> •
                                            @endif
                                            {{ $user->major ?? 'Member' }}
                                        </div>
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
                        <div class="p-6 text-center space-y-3">
                            <img src="{{ $other->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($other->name) . '&background=6366f1&color=fff' }}"
                                 alt="{{ $other->name }}"
                                 class="w-20 h-20 rounded-2xl mx-auto object-cover shadow-sm ring-4 ring-slate-100 dark:ring-slate-800">
                            <div>
                                <h3 class="font-black text-slate-900 dark:text-white text-base">{{ $other->name }}</h3>
                                <p class="text-xs text-slate-400">{{ $other->university ?? 'Mahasiswa' }} • {{ $other->major ?? '' }}</p>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('profile.show', $other->id) }}"
                                   class="inline-flex items-center justify-center w-full px-4 py-2 rounded-xl bg-brand-50 hover:bg-brand-100 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 text-xs font-bold transition-colors">
                                    Lihat Profil Lengkap
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
            }
        </script>
        @endscript
    @endif

    @if($conversation && $conversation->type === 'project' && $conversation->project)
        @livewire('project.add-member', ['project' => $conversation->project], key('add-member-' . $conversation->id))
    @endif
</div>
