<x-app-layout>
    @section('title', 'Project Management')
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700/50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h5 class="font-bold text-slate-800 dark:text-slate-100 text-lg mb-1 flex items-center gap-2">
                        <i class="bi bi-folder-fill text-indigo-650 dark:text-indigo-400"></i>All Projects
                    </h5>
                    <small class="text-slate-450 dark:text-slate-500">Manage and monitor all projects</small>
                </div>
                <div>
                    <form method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center bg-slate-50 dark:bg-slate-750 border border-slate-200 dark:border-slate-700 rounded-full overflow-hidden w-[250px]">
                            <span class="pl-4 pr-2 text-slate-400">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" 
                                   class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-2 px-1 text-xs"
                                   placeholder="Search projects..." value="{{ request('search') }}">
                        </div>
                        <select name="status" class="bg-slate-50 dark:bg-slate-750 border border-slate-200 dark:border-slate-700 rounded-full px-4 py-2 text-xs text-slate-700 dark:text-slate-250 focus:outline-none" onchange="this.form.submit()">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2.5 flex items-center justify-center transition-colors border-0">
                            <i class="bi bi-search text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse align-middle text-nowrap">
                    <thead class="bg-slate-50 dark:bg-slate-700/30">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Project</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Owner</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Category</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Members</th>
                            <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Created</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="max-w-[300px]">
                                        <div class="font-bold text-sm text-slate-850 dark:text-slate-100 mb-0.5 truncate">{{ $project->title }}</div>
                                        <small class="text-slate-450 dark:text-slate-400 text-xs block truncate">{{ $project->description }}</small>
                                        @if($project->github_repo_url)
                                            <a href="{{ $project->github_repo_url }}" target="_blank"
                                                class="text-indigo-650 dark:text-indigo-405 text-xs no-underline hover:underline mt-1.5 inline-flex items-center gap-1">
                                                <i class="bi bi-github"></i>Repository
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) }}"
                                            class="rounded-circle" width="36" height="36">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-xs text-slate-855 dark:text-slate-100 truncate">{{ $project->owner->name }}</div>
                                            <small class="text-slate-400 dark:text-slate-500 text-[10px] truncate block mt-0.5">
                                                <i class="bi bi-building mr-1"></i>{{ $project->owner->university ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 rounded-lg px-2.5 py-1 text-xs font-bold inline-flex items-center gap-1">
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
                                            : ($project->status === 'completed' ? 'bg-indigo-500/10 text-indigo-655' : ($project->status === 'planning' ? 'bg-amber-500/10 text-amber-600' : 'bg-slate-100 dark:bg-slate-700 text-slate-500'));
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
                                    <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        <i class="bi bi-people-fill text-slate-400"></i>
                                        <span>{{ $project->members->count() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-800 dark:text-slate-200">
                                        <i class="bi bi-calendar3 mr-1 text-slate-400"></i>{{ $project->created_at->format('M d, Y') }}
                                    </div>
                                    <small class="text-slate-400 dark:text-slate-500 text-[10px] block mt-0.5">{{ $project->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                           class="p-2 text-slate-500 hover:text-indigo-600 border-r border-slate-200 dark:border-slate-700 inline-block" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" 
                                           class="p-2 text-slate-500 hover:text-sky-500 border-r border-slate-200 dark:border-slate-700 inline-block" title="Edit Project">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @if($project->status !== 'archived')
                                            <form method="POST" action="{{ route('admin.projects.archive', $project) }}" class="inline-block">
                                                @csrf
                                                <button type="submit" class="p-2 text-slate-500 hover:text-amber-500 border-r border-slate-200 dark:border-slate-700 bg-transparent border-0" title="Archive Project">
                                                    <i class="bi bi-archive-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $project->title }}?')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-500 hover:text-red-500 bg-transparent border-0" title="Delete Project">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10">
                                    <div class="text-slate-400 dark:text-slate-500">
                                        <i class="bi bi-inbox text-4xl block mb-2 opacity-50"></i>
                                        <p class="text-xs font-bold uppercase tracking-wider mb-1">No projects found</p>
                                        @if(request('search'))
                                            <small class="text-[10px] text-slate-400">Try adjusting your search criteria</small>
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
            <div class="p-6 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/40">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-slate-500 dark:text-slate-400 text-xs">
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