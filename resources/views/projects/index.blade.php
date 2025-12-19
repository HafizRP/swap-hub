@section('title', 'Projects')
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h2 class="h4 fw-bold text-white mb-0">
                {{ __('Project Marketplace') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-pill px-4 fw-black">
                + Create New Project
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <!-- Filters -->
        <div class="card border-0 shadow-sm p-4 mb-5">
            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3">
                    <span class="small fw-black text-uppercase text-secondary"
                        style="letter-spacing: 0.1em; font-size: 10px;">Filter By:</span>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('projects.index', ['category' => 'All', 'search' => request('search')]) }}"
                            class="btn {{ request('category', 'All') === 'All' ? 'btn-primary' : 'btn-outline-secondary border-0' }} btn-sm rounded-pill px-4 py-2 fw-black text-uppercase d-inline-flex align-items-center justify-content-center"
                            style="font-size: 9px; min-width: 100px; {{ request('category', 'All') !== 'All' ? 'background: rgba(255,255,255,0.05);' : '' }}">All
                            Projects</a>

                        <a href="{{ route('projects.index', ['category' => 'Development', 'search' => request('search')]) }}"
                            class="btn {{ request('category') === 'Development' ? 'btn-primary' : 'btn-outline-secondary border-0' }} btn-sm rounded-pill px-4 py-2 fw-black text-uppercase d-inline-flex align-items-center justify-content-center"
                            style="font-size: 9px; min-width: 100px; {{ request('category') !== 'Development' ? 'background: rgba(255,255,255,0.05);' : '' }}">Development</a>

                        <a href="{{ route('projects.index', ['category' => 'Design', 'search' => request('search')]) }}"
                            class="btn {{ request('category') === 'Design' ? 'btn-primary' : 'btn-outline-secondary border-0' }} btn-sm rounded-pill px-4 py-2 fw-black text-uppercase d-inline-flex align-items-center justify-content-center"
                            style="font-size: 9px; min-width: 100px; {{ request('category') !== 'Design' ? 'background: rgba(255,255,255,0.05);' : '' }}">Design</a>

                        <a href="{{ route('projects.index', ['category' => 'Marketing', 'search' => request('search')]) }}"
                            class="btn {{ request('category') === 'Marketing' ? 'btn-primary' : 'btn-outline-secondary border-0' }} btn-sm rounded-pill px-4 py-2 fw-black text-uppercase d-inline-flex align-items-center justify-content-center"
                            style="font-size: 9px; min-width: 100px; {{ request('category') !== 'Marketing' ? 'background: rgba(255,255,255,0.05);' : '' }}">Marketing</a>
                    </div>
                </div>
                <div class="position-relative">
                    <form action="{{ route('projects.index') }}" method="GET" class="d-flex gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search projects..."
                            class="form-control form-control-sm bg-dark border-0 rounded-pill px-4 py-2 text-white placeholder-secondary"
                            style="width: 260px; height: 40px; background-color: rgba(255,255,255,0.05) !important;">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-black text-uppercase"
                            style="font-size: 10px;">Search</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="row g-4">
            @forelse($projects as $project)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden feature-card-hover">
                        <div class="p-1" style="background: linear-gradient(to right, #6366f1, #a855f7, #ec4899);"></div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="d-flex flex-wrap gap-2">
                                    @php
                                        $statusColors = [
                                            'planning' => ['bg' => 'rgba(13, 202, 240, 0.1)', 'text' => '#0dcaf0', 'border' => 'rgba(13, 202, 240, 0.2)'],
                                            'active' => ['bg' => 'rgba(25, 135, 84, 0.1)', 'text' => '#198754', 'border' => 'rgba(25, 135, 84, 0.2)'],
                                            'completed' => ['bg' => 'rgba(13, 110, 253, 0.1)', 'text' => '#0d6efd', 'border' => 'rgba(13, 110, 253, 0.2)'],
                                            'archived' => ['bg' => 'rgba(108, 117, 125, 0.1)', 'text' => '#6c757d', 'border' => 'rgba(108, 117, 125, 0.2)'],
                                        ];
                                        $colors = $statusColors[$project->status] ?? $statusColors['planning'];
                                    @endphp
                                    <span
                                        class="badge rounded-pill px-3 py-2 small fw-black text-uppercase d-inline-flex align-items-center"
                                        style="font-size: 8px; background-color: {{ $colors['bg'] }} !important; color: {{ $colors['text'] }} !important; border: 1px solid {{ $colors['border'] }} !important;">
                                        <div class="rounded-circle me-1"
                                            style="width: 4px; height: 4px; background-color: currentColor;"></div>
                                        {{ $project->status }}
                                    </span>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-black text-uppercase d-inline-flex align-items-center"
                                        style="font-size: 8px; border: 1px solid rgba(99, 102, 241, 0.2);">
                                        {{ $project->category }}
                                    </span>
                                </div>
                                <div class="d-flex -space-x-2">
                                    @foreach($project->members->take(3) as $member)
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=4f46e5&color=fff' }}"
                                            class="rounded-circle border border-2 border-dark" width="28" height="28"
                                            title="{{ $member->name }}">
                                    @endforeach
                                </div>
                            </div>

                            <h5 class="card-title fw-black text-white mb-3">{{ $project->title }}</h5>
                            <p class="card-text small text-secondary mb-4 lh-lg overflow-hidden"
                                style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                {{ $project->description }}
                            </p>

                            <div class="d-flex align-items-center gap-2 mb-4">
                                <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=34d399&color=fff' }}"
                                    class="rounded-circle" width="20" height="20">
                                <span class="small text-secondary fw-bold">By <span
                                        class="text-light">{{ $project->owner->name }}</span></span>
                            </div>

                            <a href="{{ route('projects.show', $project) }}"
                                class="btn btn-outline-light w-100 rounded-pill py-2 fw-black small text-uppercase mt-2">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="bg-white bg-opacity-5 rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                        style="width: 80px; height: 80px;">
                        <svg class="text-secondary" style="width: 40px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h4 class="fw-bold text-white mb-2">No projects found</h4>
                    <p class="text-secondary mb-4">Be the first to start a collaboration on Swap Hub!</p>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-black">Start
                        Today</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $projects->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .feature-card-hover {
            transition: all 0.3s;
        }

        .feature-card-hover:hover {
            transform: translateY(-8px);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .-space-x-2>* {
            margin-left: -0.5rem;
        }

        .-space-x-2>*:first-child {
            margin-left: 0;
        }
    </style>
</x-app-layout>