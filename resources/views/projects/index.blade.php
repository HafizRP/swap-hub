@section('title', 'Browse Projects')
<x-app-layout>
    <div class="container py-5">
        <div class="row g-5">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="d-flex flex-column gap-4 sticky-top" style="top: 100px; z-index: 10;">
                    <div>
                        <h5 class="fw-bold mb-3">Filter By</h5>
                        
                        <form action="{{ route('projects.index') }}" method="GET">
                            <!-- Search -->
                            <div class="position-relative mb-4">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="form-control rounded-pill ps-5 py-2 border-0 shadow-sm" 
                                    placeholder="Search keywords..."
                                    style="background-color: var(--bs-body-bg); color: var(--bs-body-color);">
                            </div>

                            <!-- Filters Accordion -->
                            <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden" id="accordionFilters" style="background-color: var(--bs-body-bg);">
                                
                                <!-- Project Type (Category) -->
                                <div class="accordion-item bg-transparent">
                                    <h2 class="accordion-header" id="headingType">
                                        <button class="accordion-button fw-bold {{ request('category') ? '' : 'collapsed' }} bg-transparent text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseType">
                                            Project Type
                                        </button>
                                    </h2>
                                    <div id="collapseType" class="accordion-collapse collapse {{ request('category') ? 'show' : '' }}" data-bs-parent="#accordionFilters">
                                        <div class="accordion-body pt-0">
                                            @foreach(['Development', 'Design', 'Marketing', 'Research'] as $cat)
                                                <div class="form-check my-2">
                                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat }}" id="cat-{{ $cat }}" 
                                                        {{ request('category') == $cat ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <label class="form-check-label text-secondary small" for="cat-{{ $cat }}">
                                                        {{ $cat }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            <div class="form-check my-2">
                                                <input class="form-check-input" type="radio" name="category" value="" id="cat-all" 
                                                    {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                                <label class="form-check-label text-secondary small" for="cat-all">
                                                    All Types
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="accordion-item bg-transparent">
                                    <h2 class="accordion-header" id="headingStatus">
                                        <button class="accordion-button fw-bold items-center bg-transparent text-body" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStatus" aria-expanded="true">
                                            Status
                                        </button>
                                    </h2>
                                    <div id="collapseStatus" class="accordion-collapse collapse show" data-bs-parent="#accordionFilters">
                                        <div class="accordion-body pt-0">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-check-label text-secondary small" for="statusOpen">
                                                    Open for application
                                                </label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="statusOpen" checked disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-end align-items-md-center mb-4 gap-3">
                    <div>
                        <h2 class="fw-bold mb-1">Explore Student Projects</h2>
                        <p class="text-secondary mb-0">Find the perfect team for your next big idea.</p>
                    </div>
                    <span class="text-secondary small fw-medium">Showing {{ $projects->count() }} projects</span>
                </div>

                <div class="row g-4">
                    @forelse($projects as $project)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 zoom-hover" style="background-color: var(--bs-body-bg);">
                                <div class="card-body p-4 d-flex flex-column">
                                    <!-- Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold fs-6 mb-0 text-truncate pe-2" style="max-width: 70%;" title="{{ $project->title }}">
                                            {{ $project->title }}
                                        </h5>
                                        @php
                                            $statusBadge = match ($project->status) {
                                                'active' => ['text' => 'text-success', 'bg' => 'bg-success-subtle', 'label' => 'Open'],
                                                'planning' => ['text' => 'text-primary', 'bg' => 'bg-primary-subtle', 'label' => 'Planning'],
                                                default => ['text' => 'text-secondary', 'bg' => 'bg-secondary-subtle', 'label' => ucfirst($project->status)],
                                            };
                                        @endphp
                                        <span class="badge {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} rounded-2 fw-medium px-2 py-1" style="font-size: 10px;">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>

                                    <!-- Description -->
                                    <p class="small text-secondary mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; min-height: 4.5em;">
                                        {{ Str::limit($project->description, 100) }}
                                    </p>

                                    <!-- Tags -->
                                    <div class="d-flex flex-wrap gap-2 mb-4">
                                        <span class="badge bg-secondary-subtle text-secondary rounded-1 fw-normal px-2 py-1" style="font-size: 10px;">
                                            {{ $project->category ?? 'General' }}
                                        </span>
                                        <!-- Fake tags for UI Demo if needed, or real additional data -->
                                        @if($project->start_date)
                                            <span class="badge bg-secondary-subtle text-secondary rounded-1 fw-normal px-2 py-1" style="font-size: 10px;">
                                                {{ $project->start_date->format('M Y') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Footer -->
                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-light-subtle">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=random' }}" 
                                                 class="rounded-circle" width="24" height="24" alt="Author">
                                            <span class="small fw-bold text-truncate" style="max-width: 80px;">{{ $project->owner->name }}</span>
                                        </div>
                                        <a href="{{ route('projects.show', $project) }}" class="text-primary fw-bold small text-decoration-none">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 py-5 text-center">
                            <div class="mb-3">
                                <svg class="text-secondary opacity-50" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h5 class="fw-bold text-secondary">No projects found</h5>
                            <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary mt-3 rounded-pill px-4">Create Project</a>
                        </div>
                    @endforelse
                </div>

                <!-- Load More / Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $projects->withQueryString()->links('pagination::bootstrap-5') }}
                    <!-- Note: For "Load More" style, we'd need Livewire or a custom paginator view. Standard pagination is safer for data integrity. -->
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Theme Adaptation -->
    <style>
        .accordion-button:not(.collapsed) {
            background-color: transparent;
            color: var(--bs-body-color);
            box-shadow: none;
        }
        .accordion-button:focus {
            box-shadow: none;
        }
        .bg-secondary-subtle {
            background-color: #f3f4f6 !important; /* Tailwind gray-100 */
        }
        [data-bs-theme="dark"] .bg-secondary-subtle {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .zoom-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .zoom-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
    </style>
</x-app-layout>