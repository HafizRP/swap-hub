@section('title', $project->title)
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-grow">
                <!-- Breadcrumbs -->
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="flex items-center gap-1.5 text-xs font-semibold text-stone-400">
                        <li>
                            <a href="{{ route('admin.projects.index') }}" class="text-stone-400 hover:text-teal-600 no-underline">Admin Projects</a>
                        </li>
                        <li class="text-stone-300 dark:text-stone-600">/</li>
                        <li class="text-stone-200" aria-current="page">Detail</li>
                    </ol>
                </nav>
                <h2 class="text-xl font-black text-stone-900 dark:text-stone-100 mb-0">
                    {{ $project->title }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.edit', $project) }}"
                    class="border border-stone-200 dark:border-stone-700 hover:bg-stone-50 dark:hover:bg-stone-800 text-stone-700 dark:text-stone-200 font-medium py-1.5 px-3 rounded-lg text-xs transition-all no-underline flex items-center gap-1">
                    <i class="bi bi-pencil-fill mr-1"></i>Edit Project
                </a>
                <a href="{{ route('admin.projects.index') }}"
                    class="bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-300 font-medium py-1.5 px-3 rounded-lg text-xs transition-colors no-underline flex items-center gap-1">
                    <i class="bi bi-arrow-left mr-1"></i>Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Main Content -->
            <div class="lg:col-span-8 flex flex-col gap-4">
                <!-- Description Card -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800">
                        <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0">Project Overview</h5>
                    </div>
                    <div class="p-4">
                        <div class="text-stone-600 dark:text-stone-400 leading-relaxed text-xs mb-4">
                            {{ $project->description }}
                        </div>

                        @if($project->github_repo_url)
                            <div class="bg-stone-50 dark:bg-[#1a1917] rounded-xl p-4 flex items-center justify-between border border-stone-200 dark:border-stone-700 mt-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white dark:bg-[#141414] rounded-full p-2.5 flex items-center justify-center shrink-0 w-10 h-10 border border-stone-200 dark:border-stone-700">
                                        <i class="bi bi-github text-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-bold text-stone-900 dark:text-stone-100 mb-0 text-xs">
                                            {{ $project->github_repo_name ?? 'Repository linked' }}
                                        </h6>
                                        <p class="text-[11px] text-stone-400 dark:text-stone-500 mb-0">Live GitHub integration active</p>
                                    </div>
                                </div>
                                <a href="{{ $project->github_repo_url }}" target="_blank"
                                    class="text-teal-600 dark:text-teal-400 text-xs font-bold no-underline hover:underline">Open Repo ↗</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Project Info -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="px-6 py-4 border-b border-stone-100 dark:border-stone-800">
                        <h5 class="font-bold text-stone-900 dark:text-stone-100 text-sm mb-0">Project Information</h5>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs mb-1.5 uppercase tracking-wider">Category</label>
                                <span class="bg-teal-500/10 text-teal-700 dark:text-teal-400 rounded-lg px-2.5 py-1 text-xs font-bold inline-block">{{ $project->category }}</span>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs mb-1.5 uppercase tracking-wider">Status</label>
                                @php
                                    $statusClass = $project->status === 'active' 
                                        ? 'bg-emerald-500/10 text-emerald-600' 
                                        : ($project->status === 'completed' ? 'bg-teal-500/10 text-teal-600' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600' : 'bg-stone-100 dark:bg-stone-800 text-stone-500'));
                                @endphp
                                <span class="rounded-lg px-2.5 py-1 text-xs font-bold uppercase inline-block {{ $statusClass }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs mb-1 uppercase tracking-wider">Created</label>
                                <p class="mb-0 text-stone-900 dark:text-stone-100 font-bold text-xs">{{ $project->created_at->format('M d, Y H:i') }}</p>
                                <small class="text-stone-400 dark:text-stone-500 text-[10px]">{{ $project->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-stone-400 dark:text-stone-500 font-bold text-xs mb-1 uppercase tracking-wider">Members</label>
                                <p class="mb-0 text-stone-900 dark:text-stone-100 font-bold text-xs">{{ $project->members->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 flex flex-col gap-4">
                <!-- Project Owner -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="px-4 py-3.5 border-b border-stone-100 dark:border-stone-800">
                        <h5 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0">Project Owner</h5>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=0d9488&color=fff' }}"
                                class="rounded-lg w-10 h-10 object-cover" width="40" height="40">
                            <div class="min-w-0">
                                <h6 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0.5 truncate">{{ $project->owner->name }}</h6>
                                <p class="text-[11px] text-stone-400 dark:text-stone-500 truncate mb-0">{{ $project->owner->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Squad Members -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="px-4 py-3.5 border-b border-stone-100 dark:border-stone-800">
                        <h5 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0">Squad Members</h5>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-col gap-3">
                            @forelse($project->members as $member)
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=0d9488&color=fff' }}"
                                            class="rounded-lg w-8 h-8 object-cover" width="32" height="32">
                                        <div class="min-w-0">
                                            <h6 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0 truncate">{{ $member->name }}</h6>
                                            <span class="text-[9px] text-stone-400 dark:text-stone-500 font-bold uppercase tracking-wider block">{{ $member->pivot->role ?? 'member' }}</span>
                                        </div>
                                    </div>
                                    @if($member->pivot->is_validated)
                                        <i class="bi bi-patch-check-fill text-teal-600 dark:text-teal-400 text-sm shrink-0"></i>
                                    @endif
                                </div>
                            @empty
                                <p class="text-stone-400 dark:text-stone-500 text-xs text-center mb-0">No members yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Project Stats -->
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 p-4">
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-3">Project Stats</h5>
                    <div class="flex flex-col gap-2.5">
                        <div class="rounded-xl p-3 flex justify-between items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-100 dark:border-stone-800">
                            <span class="text-xs font-semibold text-stone-500 dark:text-stone-400">Members</span>
                            <span class="text-base font-bold text-stone-900 dark:text-stone-100">{{ $project->members->count() }}</span>
                        </div>
                        <div class="rounded-xl p-3 flex justify-between items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-100 dark:border-stone-800">
                            <span class="text-xs font-semibold text-stone-500 dark:text-stone-400">Active</span>
                            <span class="text-base font-bold text-stone-900 dark:text-stone-100">{{ (int) ($project->created_at->diffInDays() + 1) }}d</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
