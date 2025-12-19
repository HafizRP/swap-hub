@section('title', 'Dashboard')
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <h2 class="h4 fw-bold text-white mb-0">
                {{ __('Dashboard') }}
            </h2>
            <div class="d-flex align-items-center gap-3">
                <div class="badge rounded-pill bg-primary p-3 d-flex align-items-center gap-2 shadow-sm">
                    <svg style="width: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span class="fw-bold">{{ $user->reputation_points }} CP</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container py-4">
        <!-- Quick Stats -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 text-white"
                    style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                    <h6 class="text-uppercase small fw-black opacity-75">Active Projects</h6>
                    <div class="display-5 fw-black my-2">{{ $activeProjects->count() }}</div>
                    <div class="small opacity-75">Ongoing collaborations</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 text-white"
                    style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                    <h6 class="text-uppercase small fw-black opacity-75">Pending Swaps</h6>
                    <div class="display-5 fw-black my-2">{{ $incomingSwaps->count() }}</div>
                    <div class="small opacity-75">Requiring your expertise</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 text-white"
                    style="background: linear-gradient(135deg, #f59e0b, #ef4444);">
                    <h6 class="text-uppercase small fw-black opacity-75">Reputation Level</h6>
                    <div class="display-5 fw-black my-2">
                        @if($user->reputation_points >= 500) Elite
                        @elseif($user->reputation_points >= 250) Expert
                        @elseif($user->reputation_points >= 100) Contributor
                        @else Beginner @endif
                    </div>
                    <div class="small opacity-75">Based on contributions</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Active Projects List -->
            <div class="col-lg-6">
                <div class="card h-100 overflow-hidden">
                    <div
                        class="card-header bg-transparent border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center p-4">
                        <h5 class="fw-black mb-0">Active Projects</h5>
                        <a href="{{ route('projects.index') }}"
                            class="btn btn-link link-primary p-0 text-decoration-none fw-bold small">View All</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($activeProjects as $project)
                            <div
                                class="list-group-item bg-transparent border-bottom border-white border-opacity-10 p-4 transition-bg hover-dim">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="pe-3">
                                        <h6 class="fw-bold text-white mb-1">{{ $project->title }}</h6>
                                        <p class="small text-secondary mb-0 text-truncate" style="max-width: 250px;">
                                            {{ $project->description }}</p>
                                    </div>
                                    <span
                                        class="badge text-primary bg-primary bg-opacity-10 py-2 px-3 rounded-pill fw-black small text-uppercase">{{ $project->status }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex -space-x-2">
                                        @foreach($project->members->take(3) as $member)
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=4f46e5&color=fff' }}"
                                                class="rounded-circle border border-2 border-dark" width="32" height="32"
                                                title="{{ $member->name }}">
                                        @endforeach
                                        @if($project->members->count() > 3)
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center border border-2 border-dark"
                                                style="width: 32px; height: 32px; font-size: 10px;">
                                                +{{ $project->members->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold small">Manage</a>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center">
                                <div class="bg-white bg-opacity-5 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 64px; height: 64px;">
                                    <svg class="text-secondary" style="width: 32px;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-secondary mb-3">No active projects yet.</p>
                                <a href="{{ route('projects.create') }}"
                                    class="btn btn-primary btn-sm rounded-pill px-4">Start a Project</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Skill Swap Requests -->
            <div class="col-lg-6">
                <div class="card h-100 overflow-hidden">
                    <div
                        class="card-header bg-transparent border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center p-4">
                        <h5 class="fw-black mb-0">Swap Requests</h5>
                        <a href="{{ route('skill-swaps.index') }}"
                            class="btn btn-link link-success p-0 text-decoration-none fw-bold small">Marketplace</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($incomingSwaps as $swap)
                            <div class="list-group-item bg-transparent border-bottom border-white border-opacity-10 p-4">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <img src="{{ $swap->requester->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($swap->requester->name) . '&background=34d399&color=fff' }}"
                                        class="rounded-4 border border-white border-opacity-10" width="48" height="48">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-white mb-1">{{ $swap->requester->name }}</h6>
                                        <p class="small text-secondary mb-0">Needs: {{ $swap->requestedSkill->name }} |
                                            Offers: {{ $swap->offeredSkill->name }}</p>
                                    </div>
                                    <div class="text-end">
                                        <span class="d-block fw-black text-success small">+{{ $swap->points_offered }}
                                            CP</span>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <form action="{{ route('skill-swaps.accept', $swap) }}" method="POST">
                                            @csrf
                                            <button
                                                class="btn btn-success w-100 rounded-pill py-2 small fw-black">Accept</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('skill-swaps.show', $swap) }}"
                                            class="btn btn-secondary w-100 rounded-pill py-2 small fw-black border-0"
                                            style="background: rgba(255,255,255,0.05);">Details</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center">
                                <div class="bg-white bg-opacity-5 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 64px; height: 64px;">
                                    <svg class="text-secondary" style="width: 32px;" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-secondary mb-1">No pending swap requests.</p>
                                <p class="small text-secondary opacity-50">New requests matching your skills will appear
                                    here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .transition-bg {
            transition: background-color 0.2s;
        }

        .hover-dim:hover {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        .-space-x-2>* {
            margin-left: -0.5rem;
        }

        .-space-x-2>*:first-child {
            margin-left: 0;
        }
    </style>
</x-app-layout>