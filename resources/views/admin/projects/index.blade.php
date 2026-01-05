<x-app-layout>
    @section('title', 'Project Management')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-1 fw-bold">
                        <i class="bi bi-folder-fill me-2 text-primary"></i>All Projects
                    </h5>
                    <small class="text-secondary">Manage and monitor all projects</small>
                </div>
                <div class="col-auto">
                    <form method="GET" class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-transparent">
                                <i class="bi bi-search text-secondary"></i>
                            </span>
                            <input type="text" name="search" class="form-control" placeholder="Search projects..."
                                value="{{ request('search') }}">
                        </div>
                        <select name="status" class="form-select" style="width: 150px;" onchange="this.form.submit()">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Planning
                            </option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">Project</th>
                            <th class="py-3">Owner</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Members</th>
                            <th class="py-3">Created</th>
                            <th class="pe-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="ps-4">
                                    <div style="max-width: 300px;">
                                        <div class="fw-bold mb-1">{{ Str::limit($project->title, 50) }}</div>
                                        <small
                                            class="text-secondary d-block">{{ Str::limit($project->description, 80) }}</small>
                                        @if($project->github_repo_url)
                                            <a href="{{ $project->github_repo_url }}" target="_blank"
                                                class="text-primary small text-decoration-none mt-1 d-inline-block">
                                                <i class="bi bi-github me-1"></i>Repository
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) }}"
                                            class="rounded-circle me-2" width="36" height="36">
                                        <div>
                                            <div class="small fw-semibold">{{ $project->owner->name }}</div>
                                            <small class="text-secondary"><i
                                                    class="bi bi-building me-1"></i>{{ $project->owner->university ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="bi 
                                                        @if($project->category === 'Development') bi-code-slash
                                                        @elseif($project->category === 'Design') bi-palette-fill
                                                        @else bi-megaphone-fill
                                                        @endif me-1"></i>
                                        {{ $project->category }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                                    @if($project->status === 'active') bg-success
                                                    @elseif($project->status === 'completed') bg-primary
                                                    @elseif($project->status === 'planning') bg-warning
                                                    @else bg-secondary
                                                    @endif px-3 py-2">
                                        <i class="bi 
                                                        @if($project->status === 'active') bi-lightning-fill
                                                        @elseif($project->status === 'completed') bi-check-circle-fill
                                                        @elseif($project->status === 'planning') bi-clock-fill
                                                        @else bi-archive-fill
                                                        @endif me-1"></i>
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-secondary me-2"></i>
                                        <span class="fw-bold">{{ $project->members->count() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i
                                            class="bi bi-calendar3 me-1 text-secondary"></i>{{ $project->created_at->format('M d, Y') }}
                                    </div>
                                    <small class="text-secondary">{{ $project->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                            class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-outline-info"
                                            title="Edit Project">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @if($project->status !== 'archived')
                                            <form method="POST" action="{{ route('admin.projects.archive', $project) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" title="Archive Project">
                                                    <i class="bi bi-archive-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $project->title }}?')"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete Project">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-secondary">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                        <p class="mb-0">No projects found</p>
                                        @if(request('search'))
                                            <small>Try adjusting your search criteria</small>
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
            <div class="card-footer bg-transparent border-top border-white border-opacity-10 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-secondary small">
                        Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }}
                        projects
                    </div>
                    <nav aria-label="Project pagination">
                        {{ $projects->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <style>
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            color: #6366f1;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            padding: 0.5rem 0.75rem;
        }

        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #6366f1;
        }

        .pagination .page-item.active .page-link {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
        }
    </style>
</x-app-layout>