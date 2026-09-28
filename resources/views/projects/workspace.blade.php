@section('title', $project->title . ' - Workspace')
<x-app-layout>
    <div class="space-y-6" x-data="{ activeTab: 'tasks' }">

        <!-- Top Project Workspace Header -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-brand-600">
                            <i class="bi bi-arrow-left"></i> Kembali ke Detail
                        </a>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                            {{ $project->category ?? 'General' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            Active Workspace
                        </span>
                    </div>

                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $project->title }}
                    </h1>
                </div>

                <div class="flex items-center gap-2.5">
                    @if($project->github_repo_url)
                        <a href="{{ $project->github_repo_url }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white dark:bg-slate-800 hover:bg-slate-800 text-xs font-bold transition-all shadow-sm">
                            <i class="bi bi-github"></i>
                            <span>Repository</span>
                        </a>
                    @endif

                    @if($project->conversation)
                        <a href="{{ route('chat', $project->conversation) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-md shadow-brand-500/20">
                            <i class="bi bi-chat-dots-fill"></i>
                            <span>Chat Tim</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Workspace Tabs -->
            <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-slate-800 overflow-x-auto custom-scrollbar">
                <button @click="activeTab = 'tasks'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                        :class="activeTab === 'tasks' ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                    <i class="bi bi-kanban"></i>
                    <span>Kanban Board</span>
                </button>

                <button @click="activeTab = 'github'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                        :class="activeTab === 'github' ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                    <i class="bi bi-github"></i>
                    <span>GitHub Feed</span>
                </button>

                <button @click="activeTab = 'files'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0"
                        :class="activeTab === 'files' ? 'bg-brand-600 text-white shadow-sm shadow-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                    <i class="bi bi-folder2-open"></i>
                    <span>Berkas & File</span>
                </button>
            </div>
        </div>

        <!-- Tab Content Container -->
        <div>
            <!-- Tasks Tab -->
            <div x-show="activeTab === 'tasks'" x-cloak>
                @livewire('project.task-board', ['project' => $project])
            </div>

            <!-- GitHub Feed Tab -->
            <div x-show="activeTab === 'github'" x-cloak class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                @livewire('project.github-feed', ['project' => $project])
            </div>

            <!-- Files Tab -->
            <div x-show="activeTab === 'files'" x-cloak class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
                @livewire('project.file-browser', ['project' => $project])
            </div>
        </div>

    </div>
</x-app-layout>
