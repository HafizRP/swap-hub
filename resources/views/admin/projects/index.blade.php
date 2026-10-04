<x-app-layout>
    @section('title', 'Project Management')
    <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200 dark:border-stone-800 overflow-hidden">
        <div class="p-4 border-b border-stone-100 dark:border-stone-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-stone-900 dark:text-stone-100 text-base mb-0 flex items-center gap-2">
                        <i class="bi bi-folder-fill text-teal-600 dark:text-teal-400"></i>All Projects
                    </h5>
                    <small class="text-stone-400 dark:text-stone-500 text-xs">Manage and monitor all projects</small>
                </div>
                <div>
                    <form method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[250px]">
                            <span class="pl-3 pr-2 text-stone-400">
                                <i class="bi bi-search text-xs"></i>
                            </span>
                            <input type="text" name="search" 
                                   class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs"
                                   placeholder="Search projects..." value="{{ request('search') }}">
                        </div>
                        <select name="status" class="bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 focus:outline-none" onchange="this.form.submit()">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white rounded-lg p-2 flex items-center justify-center transition-colors border-0 cursor-pointer">
                            <i class="bi bi-search text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-stone-50 dark:bg-stone-900/60 border-b border-stone-200 dark:border-stone-800">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Project</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Owner</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Category</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Members</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Created</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @forelse($projects as $project)
                            <tr class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40 transition-colors" wire:key="admin-proj-{{ $project->id }}">
                                <td class="px-6 py-4">
                                    <div class="max-w-[300px]">
                                        <div class="font-bold text-xs text-stone-900 dark:text-stone-100 mb-0.5 truncate">{{ $project->title }}</div>
                                        <small class="text-stone-400 dark:text-stone-500 text-xs block truncate">{{ $project->description }}</small>
                                        @if($project->github_repo_url)
                                            <a href="{{ $project->github_repo_url }}" target="_blank"
                                                class="text-teal-600 dark:text-teal-400 text-xs no-underline hover:underline mt-1.5 inline-flex items-center gap-1">
                                                <i class="bi bi-github"></i>Repository
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=0d9488&color=fff' }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-xs text-stone-900 dark:text-stone-100 truncate">{{ $project->owner->name }}</div>
                                            <small class="text-stone-400 dark:text-stone-500 text-[10px] truncate block mt-0.5">
                                                <i class="bi bi-building mr-1"></i>{{ $project->owner->university ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-teal-500/10 text-teal-700 dark:text-teal-400 rounded-lg px-2.5 py-1 text-xs font-bold inline-flex items-center gap-1">
                                        <i class="bi 
                                            @if($project->category === 'Development') bi-code-slash
                                            @elseif($project->category === 'Design') bi-palette-fill
                                            @else bi-megaphone-fill
                                            @endif"></i>
                                        {{ $project->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = $project->status === 'active' 
                                            ? 'bg-emerald-500/10 text-emerald-600' 
                                            : ($project->status === 'completed' ? 'bg-teal-500/10 text-teal-600' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600' : 'bg-stone-100 dark:bg-stone-800 text-stone-500'));
                                        $statusIcon = $project->status === 'active' 
                                            ? 'bi-lightning-fill' 
                                            : ($project->status === 'completed' ? 'bi-check-circle-fill' : ($project->status === 'planning' ? 'bi-clock-fill' : 'bi-archive-fill'));
                                    @endphp
                                    <span class="rounded-lg px-2.5 py-1 text-xs font-bold uppercase inline-flex items-center gap-1 {{ $statusClass }}">
                                        <i class="bi {{ $statusIcon }}"></i>
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-stone-700 dark:text-stone-300">
                                        <i class="bi bi-people-fill text-stone-400"></i>
                                        <span>{{ $project->members->count() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-stone-800 dark:text-stone-200">
                                        <i class="bi bi-calendar3 mr-1 text-stone-400"></i>{{ $project->created_at->format('M d, Y') }}
                                    </div>
                                    <small class="text-stone-400 dark:text-stone-500 text-[10px] block mt-0.5">{{ $project->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex rounded-lg overflow-hidden border border-stone-200 dark:border-stone-700 shadow-sm bg-white dark:bg-[#141414]">
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                           class="p-2 text-stone-500 hover:text-teal-600 border-r border-stone-200 dark:border-stone-700 inline-block" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" 
                                           class="p-2 text-stone-500 hover:text-sky-500 border-r border-stone-200 dark:border-stone-700 inline-block" title="Edit Project">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @if($project->status !== 'archived')
                                            <form method="POST" action="{{ route('admin.projects.archive', $project) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" class="p-2 text-stone-500 hover:text-amber-500 border-r border-stone-200 dark:border-stone-700 bg-transparent border-0 cursor-pointer" title="Archive Project">
                                                    <i class="bi bi-archive-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $project->title }}?')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-stone-500 hover:text-red-500 bg-transparent border-0 cursor-pointer" title="Delete Project">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10">
                                    <div class="text-stone-400 dark:text-stone-500">
                                        <i class="bi bi-inbox text-4xl block mb-2 opacity-50"></i>
                                        <p class="text-xs font-bold uppercase tracking-wider mb-1">No projects found</p>
                                        @if(request('search'))
                                            <small class="text-[10px] text-stone-400">Try adjusting your search criteria</small>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($projects->hasPages())
            <div class="p-4 border-t border-stone-100 dark:border-stone-800 bg-stone-50 dark:bg-[#141414]">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-stone-500 dark:text-stone-400 text-xs">
                        Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} projects
                    </div>
                    <nav aria-label="Project pagination">
                        {{ $projects->links('pagination::tailwind') }}
                    </nav>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
