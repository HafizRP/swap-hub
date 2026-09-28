@section('title', $project->title . ' - Workspace')
<x-app-layout>
    <div class="h-[calc(100vh-74px)] flex overflow-hidden -m-4 sm:-m-6 bg-slate-50 dark:bg-slate-900" x-data="{ activeTab: 'chat', showMobileSidebar: false }">
        
        <!-- Left Sidebar: Project Nav -->
        <div class="w-64 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex-col shrink-0 hidden md:flex">
            <!-- Project Header -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-sm shrink-0">
                        <i class="bi bi-folder-fill text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-sm font-extrabold text-slate-900 dark:text-slate-100 truncate mb-0">{{ $project->title }}</h2>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold">{{ $project->category }}</span>
                    </div>
                </div>
                @if($project->github_repo_url)
                    <a href="{{ $project->github_repo_url }}" target="_blank"
                        class="w-full py-1.5 px-3 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-bold inline-flex items-center justify-center gap-1.5 no-underline transition-colors">
                        <i class="bi bi-github"></i>
                        <span>Repositori</span>
                    </a>
                @endif
            </div>

            <!-- Nav Links -->
            <div class="p-3 space-y-4 flex-grow overflow-y-auto custom-scrollbar">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 block mb-1.5">Navigasi</span>
                    <div class="space-y-1">
                        <a href="{{ route('projects.show', $project) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors no-underline">
                            <i class="bi bi-info-circle text-sm"></i>
                            <span>Detail Proyek</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors no-underline">
                            <i class="bi bi-grid text-sm"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-3 block mb-1.5">Proyek Saya</span>
                    <div class="space-y-1">
                        @foreach(auth()->user()->projects()->take(4)->get() as $userProject)
                            <a href="{{ route('projects.workspace', $userProject) }}"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold no-underline transition-colors {{ $userProject->id === $project->id ? 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                <i class="bi bi-folder text-xs"></i>
                                <span class="truncate">{{ $userProject->title }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- User Footer -->
            <div class="p-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}"
                        class="w-8 h-8 rounded-xl object-cover shrink-0" alt="{{ auth()->user()->name }}">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate mb-0">{{ auth()->user()->name }}</p>
                        <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                        </span>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 transition-colors">
                    <i class="bi bi-gear"></i>
                </a>
            </div>
        </div>

        <!-- Main Workspace Area -->
        <div class="flex-grow flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div class="px-5 py-3.5 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
                <div class="flex items-center gap-3">
                    <h1 class="text-base font-black text-slate-900 dark:text-slate-100 mb-0 truncate">{{ $project->title }}</h1>
                    <span class="rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 px-2 py-0.5 text-[10px] font-bold uppercase flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                    @if($project->github_repo_url)
                        <a href="{{ $project->github_repo_url }}" target="_blank"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-xs no-underline inline-flex items-center gap-1">
                            <i class="bi bi-github"></i>
                            <span class="hidden sm:inline">{{ $project->github_repo_name ?? 'Repo' }}</span>
                        </a>
                    @endif
                </div>

                <!-- Tabs -->
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-slate-200/80 dark:border-slate-700 self-start sm:self-auto">
                    <button @click="activeTab = 'chat'" 
                        :class="activeTab === 'chat' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 border-0 cursor-pointer">
                        <i class="bi bi-chat-dots"></i>
                        <span>Diskusi</span>
                    </button>
                    <button @click="activeTab = 'feed'" 
                        :class="activeTab === 'feed' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'"
                        class="px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1.5 border-0 cursor-pointer">
                        <i class="bi bi-git"></i>
                        <span>Aktivitas</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="flex-grow flex overflow-hidden">
                <!-- Chat View -->
                <div x-show="activeTab === 'chat'" class="flex-grow flex flex-col overflow-hidden">
                    <div class="flex-grow flex h-full">
                        <!-- Message Thread -->
                        <div class="flex-grow flex flex-col h-full bg-slate-50/50 dark:bg-slate-900/50">
                            <!-- Messages Area -->
                            <div class="flex-grow overflow-y-auto p-4 sm:p-6 space-y-4 custom-scrollbar" id="messages-container">
                                @if($project->conversation)
                                    @forelse($project->conversation->messages()->with('user')->latest()->take(50)->get()->reverse() as $message)
                                        @php $isSelf = $message->user_id === auth()->id(); @endphp
                                        <div class="flex items-start gap-3 {{ $isSelf ? 'flex-row-reverse' : '' }}">
                                            <img src="{{ $message->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=6366f1&color=fff' }}"
                                                class="w-8 h-8 rounded-xl object-cover shrink-0" alt="{{ $message->user->name }}">
                                            <div class="max-w-[75%] sm:max-w-md {{ $isSelf ? 'items-end' : 'items-start' }} flex flex-col">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $message->user->name }}</span>
                                                    <span class="text-[10px] text-slate-400">{{ $message->created_at->format('h:i A') }}</span>
                                                </div>
                                                <div class="p-3 rounded-2xl text-xs leading-relaxed {{ $isSelf ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 shadow-sm rounded-tl-none' }}">
                                                    {{ $message->message }}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="h-full flex flex-col items-center justify-center text-center p-8">
                                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 text-xl">
                                                <i class="bi bi-chat-dots"></i>
                                            </div>
                                            <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 mb-1">Belum Ada Pesan</h3>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0">Mulai diskusi pertama dengan squad proyek Anda!</p>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="h-full flex flex-col items-center justify-center text-center p-8">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 text-xl">
                                            <i class="bi bi-chat-dots"></i>
                                        </div>
                                        <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200 mb-1">Chat Belum Dikonfigurasi</h3>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-0">Room percakapan sedang disiapkan untuk proyek ini.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Input Box -->
                            <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                                <form action="{{ route('messages.store') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="hidden" name="conversation_id" value="{{ $project->conversation->id ?? '' }}">
                                    <input type="text" name="message" required
                                        class="flex-grow bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all"
                                        placeholder="Ketik pesan untuk squad proyek...">
                                    <button type="submit" 
                                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all duration-150 active:scale-[0.98] flex items-center gap-1.5 border-0 cursor-pointer shrink-0">
                                        <i class="bi bi-send-fill text-xs"></i>
                                        <span class="hidden sm:inline">Kirim</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Right Sidebar: Squad Members -->
                        <div class="w-72 border-l border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shrink-0 hidden lg:flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-extrabold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Anggota Tim</span>
                                <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">{{ $project->members->count() }}</span>
                            </div>
                            <div class="space-y-3 flex-grow overflow-y-auto custom-scrollbar">
                                @foreach($project->members as $member)
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                            class="w-8 h-8 rounded-xl object-cover shrink-0" alt="{{ $member->name }}">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate mb-0">{{ $member->name }}</p>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase">{{ $member->pivot->role ?? 'Member' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feed View -->
                <div x-show="activeTab === 'feed'" class="flex-grow p-6 overflow-y-auto custom-scrollbar" style="display: none;">
                    <div class="max-w-2xl mx-auto space-y-4">
                        <h2 class="text-base font-extrabold text-slate-900 dark:text-slate-100 mb-4">Log Aktivitas Tim</h2>
                        @forelse($project->githubActivities as $activity)
                            <div class="p-4 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <i class="bi bi-git"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-xs text-slate-800 dark:text-slate-100">{{ $activity->user->name ?? 'Kontributor' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 mb-0">{{ $activity->commit_message }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-200 dark:border-slate-700 p-8">
                                <i class="bi bi-git text-3xl text-slate-400 mb-2 block"></i>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mb-0 font-medium">Belum ada aktivitas yang tercatat pada repositori ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>