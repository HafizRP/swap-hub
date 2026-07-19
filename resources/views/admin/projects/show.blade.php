@section('title', $project->title)
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-content-between items-start md:items-center gap-4">
            <div class="flex-grow">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                        <li>
                            <a href="{{ route('admin.projects.index') }}" class="text-slate-400 hover:text-indigo-650 no-underline">Admin Projects</a>
                        </li>
                        <li class="text-slate-300">/</li>
                        <li class="text-slate-200" aria-current="page">Detail</li>
                    </ol>
                </nav>
                <h2 class="text-2xl font-black text-white mb-0">
                    {{ $project->title }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.edit', $project) }}"
                    class="border border-white/10 hover:bg-white/10 text-white font-bold py-2 px-4 rounded-full text-xs transition-all no-underline flex items-center gap-1">
                    <i class="bi bi-pencil-fill mr-1"></i>Edit Project
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded-full text-xs transition-colors no-underline flex items-center gap-1">
                    <i class="bi bi-arrow-left mr-1"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>
 
    <div class="container mx-auto py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <!-- Description Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                        <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base mb-0">Project Overview</h5>
                    </div>
                    <div class="p-6">
                        <div class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm mb-4">
                            {{ $project->description }}
                        </div>
 
                        @if($project->github_repo_url)
                            <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl p-4 flex items-center justify-between border border-slate-200 dark:border-slate-700 mt-6">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white dark:bg-slate-800 rounded-full p-2.5 flex items-center justify-center shrink-0 w-11 h-11 border border-slate-100 dark:border-slate-700">
                                        <svg style="width: 22px;" fill="currentColor" viewBox="0 0 24 24" class="text-slate-850 dark:text-slate-105">
                                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-slate-805 dark:text-slate-100 mb-0.5 text-sm">
                                            {{ $project->github_repo_name ?? 'Repository linked' }}
                                        </h6>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-0">Live GitHub integration active</p>
                                    </div>
                                </div>
                                <a href="{{ $project->github_repo_url }}" target="_blank"
                                    class="text-indigo-650 dark:text-indigo-400 text-xs font-bold no-underline hover:underline">Open Repo ↗</a>
                            </div>
                        @endif
                    </div>
                </div>
 
                <!-- Project Info -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                        <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base mb-0">Project Information</h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <label class="block text-slate-450 dark:text-slate-500 font-bold text-xs mb-2 uppercase tracking-wider">Category</label>
                                <span class="bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 rounded-lg px-3 py-1.5 text-xs font-bold inline-block">{{ $project->category }}</span>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-slate-450 dark:text-slate-500 font-bold text-xs mb-2 uppercase tracking-wider">Status</label>
                                @php
                                    $statusClass = $project->status === 'active' 
                                        ? 'bg-emerald-500/10 text-emerald-600' 
                                        : ($project->status === 'completed' ? 'bg-indigo-500/10 text-indigo-655' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600' : 'bg-slate-100 dark:bg-slate-700 text-slate-500'));
                                @endphp
                                <span class="rounded-lg px-3 py-1.5 text-xs font-bold uppercase inline-block {{ $statusClass }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-slate-450 dark:text-slate-500 font-bold text-xs mb-2 uppercase tracking-wider">Created</label>
                                <p class="mb-0 text-slate-800 dark:text-slate-100 font-bold text-sm">{{ $project->created_at->format('M d, Y H:i') }}</p>
                                <small class="text-slate-400 dark:text-slate-500 text-xs">{{ $project->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-slate-455 dark:text-slate-500 font-bold text-xs mb-2 uppercase tracking-wider">Members</label>
                                <p class="mb-0 text-slate-800 dark:text-slate-100 font-bold text-sm">{{ $project->members->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Sidebar -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Project Owner -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                        <h5 class="font-bold text-slate-850 dark:text-slate-100 text-sm mb-0">Project Owner</h5>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=6366f1&color=fff' }}"
                                class="rounded-lg shadow-sm" width="48" height="48">
                            <div class="min-w-0">
                                <h6 class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0.5 truncate">{{ $project->owner->name }}</h6>
                                <p class="text-xs text-slate-450 dark:text-slate-500 truncate mb-0">{{ $project->owner->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
 
                <!-- Squad Members -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                        <h5 class="font-bold text-slate-850 dark:text-slate-100 text-sm mb-0">Squad Members</h5>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-col gap-4">
                            @forelse($project->members as $member)
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                            class="rounded-lg shadow-sm" width="40" height="40">
                                        <div class="min-w-0">
                                            <h6 class="font-bold text-slate-800 dark:text-slate-100 text-xs mb-0.5 truncate">{{ $member->name }}</h6>
                                            <span class="text-[9px] text-slate-450 dark:text-slate-500 font-black uppercase tracking-wider block">{{ $member->pivot->role ?? 'member' }}</span>
                                        </div>
                                    </div>
                                    @if($member->pivot->is_validated)
                                        <svg style="width: 16px;" class="text-indigo-600 dark:text-indigo-400 shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            @empty
                                <p class="text-slate-400 dark:text-slate-500 text-xs text-center mb-0">No members yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
 
                <!-- Project Stats -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-205 border-slate-200 dark:border-slate-700 p-5">
                    <h5 class="font-bold text-slate-850 dark:text-slate-100 text-sm mb-4">Project Stats</h5>
                    <div class="flex flex-col gap-3">
                        <div class="rounded-xl p-3.5 flex justify-between items-center bg-slate-50 dark:bg-slate-750/30 border border-slate-100 dark:border-slate-700/50">
                            <span class="text-xs font-black text-slate-455 dark:text-slate-400 uppercase tracking-wider">Members</span>
                            <span class="text-lg font-black text-slate-800 dark:text-slate-100">{{ $project->members->count() }}</span>
                        </div>
                        <div class="rounded-xl p-3.5 flex justify-between items-center bg-slate-50 dark:bg-slate-750/30 border border-slate-100 dark:border-slate-700/50">
                            <span class="text-xs font-black text-slate-455 dark:text-slate-400 uppercase tracking-wider">Active</span>
                            <span class="text-lg font-black text-slate-800 dark:text-slate-100">{{ (int) ($project->created_at->diffInDays() + 1) }}d</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>