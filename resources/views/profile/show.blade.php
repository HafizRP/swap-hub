@section('title', $user->name)
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
            <h2 class="h3 fw-black text-white mb-0">
                {{ __('Student Portfolio') }}
            </h2>
            @if(auth()->id() === $user->id)
                <a href="{{ route('profile.edit') }}"
                    class="btn btn-light rounded-pill px-4 py-2 fw-black small shadow-sm transition">
                    Edit Portfolio
                </a>
            @else
                <form action="{{ route('chat.direct', $user) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                        Start Collaboration
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="container py-5">
        <div class="row g-5">

            <!-- Profile Header Card -->
            <div class="col-12">
                <div
                    class="card border-0 shadow-lg overflow-hidden rounded-5 bg-dark bg-opacity-25 border border-white border-opacity-10">
                    <div class="position-relative"
                        style="height: 160px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);">
                    </div>
                    <div class="px-4 px-md-5 pb-5 position-relative">
                        <div class="d-flex flex-column flex-md-row align-items-end gap-4" style="margin-top: -80px;">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=160&background=111827&color=fff' }}"
                                class="rounded-5 border border-8 border-dark shadow-2xl position-relative z-1"
                                width="160" height="160">
                            <div class="flex-grow-1 pb-2 text-center text-md-start">
                                <h1 class="display-5 fw-black text-white mb-1">{{ $user->name }}</h1>
                                <p class="small text-secondary fw-bold text-uppercase tracking-widest mb-0 opacity-75">
                                    {{ $user->university }} • {{ $user->major }} • Class of
                                    {{ $user->graduation_year ?? 'Not Set' }}
                                </p>
                            </div>
                            <div class="pb-2 text-center">
                                <span
                                    class="d-block small fw-black text-secondary text-uppercase tracking-tighter mb-2 opacity-50"
                                    style="font-size: 8px;">Reputation Level</span>
                                <div class="badge bg-primary rounded-pill px-4 py-3 fs-6 fw-black shadow-lg">
                                    @if($user->reputation_points >= 500) Elite Contributor
                                    @elseif($user->reputation_points >= 250) Expert Sync
                                    @elseif($user->reputation_points >= 100) Active Talent
                                    @else Rising Star @endif
                                </div>
                            </div>
                        </div>

                        <div class="row g-5 mt-4">
                            <!-- Bio -->
                            <div class="col-lg-8 border-end border-white border-opacity-10">
                                <h6
                                    class="small fw-black text-secondary text-uppercase tracking-widest mb-3 opacity-50">
                                    About Me</h6>
                                <p class="fs-4 text-white lh-base fw-medium italic opacity-90">
                                    "{{ $user->bio ?? 'No bio provided yet. This student is busy building amazing things!' }}"
                                </p>
                            </div>

                            <!-- Stats Grid -->
                            <div class="col-lg-4 d-flex align-items-center">
                                <div class="row g-3 w-100">
                                    <div class="col-6 text-center">
                                        <div class="p-4 rounded-4 border border-white border-opacity-10 shadow-sm"
                                            style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                            <span
                                                class="d-block small fw-black text-secondary text-uppercase mb-1 opacity-50"
                                                style="font-size: 8px;">Projects</span>
                                            <span
                                                class="h2 fw-black text-white mb-0">{{ $user->projects->count() }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div class="p-4 rounded-4 border border-primary border-opacity-10 shadow-sm"
                                            style="background-color: rgba(99, 102, 241, 0.1) !important;">
                                            <span
                                                class="d-block small fw-black text-primary text-uppercase mb-1 opacity-50"
                                                style="font-size: 8px;">Score</span>
                                            <span class="h2 fw-black text-primary mb-0">{{ $user->reputation_points }}
                                                <span class="small" style="font-size: 0.5em;">CP</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills Column -->
            <div class="col-lg-4">
                <div class="d-flex flex-column gap-4">
                    <div
                        class="card border-0 shadow-sm rounded-5 bg-dark bg-opacity-25 border border-white border-opacity-10 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                            <h5 class="fw-black text-white mb-0 uppercase tracking-widest small">Verified Skills</h5>
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('profile.edit') }}"
                                    class="text-primary small fw-bold text-decoration-none">+ Add</a>
                            @endif
                        </div>
                        <div class="d-flex flex-column gap-3">
                            @forelse($user->skills as $skill)
                                <div class="p-3 rounded-4 d-flex align-items-center justify-content-between border border-white border-opacity-5 transition hover-scale-5"
                                    style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <div>
                                        <h6 class="fw-bold text-white mb-1 small">{{ $skill->name }}</h6>
                                        <span class="small text-primary fw-black text-uppercase opacity-75"
                                            style="font-size: 7px;">{{ $skill->pivot->proficiency_level }}</span>
                                    </div>
                                    <div class="d-flex gap-1">
                                        @php $levels = ['beginner' => 1, 'intermediate' => 2, 'advanced' => 3, 'expert' => 4]; @endphp
                                        @for($i = 1; $i <= 4; $i++)
                                            <div class="rounded-circle {{ $i <= $levels[$skill->pivot->proficiency_level] ? 'bg-primary shadow-primary' : '' }}"
                                                style="width: 6px; height: 6px; background-color: {{ $i <= $levels[$skill->pivot->proficiency_level] ? '' : 'rgba(255, 255, 255, 0.1)' }} !important;">
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 opacity-50 italic">
                                    <p class="small text-secondary mb-0">No skills listed yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- GitHub Pulse -->
                    <div
                        class="card border-0 shadow-xl rounded-5 bg-black p-4 text-white overflow-hidden border border-white border-opacity-10">
                        <div class="d-flex align-items-center gap-3 mb-4 px-2">
                            <div class="rounded-3"
                                style="background-color: rgba(255, 255, 255, 0.1) !important; padding: 0.5rem;">
                                <svg style="width: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                </svg>
                            </div>
                            <h5 class="fw-black mb-0 uppercase tracking-widest small">Git Pulse</h5>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-12">
                                <div class="p-3 rounded-4 border border-white border-opacity-10 d-flex justify-content-between align-items-center"
                                    style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <span class="small fw-black text-secondary text-uppercase opacity-50"
                                        style="font-size: 7px;">Monthly Commits</span>
                                    <span class="h4 fw-black text-emerald mb-0">42</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-end gap-1 px-1 mt-auto" style="height: 60px;">
                            @foreach([4, 7, 2, 8, 5, 9, 1, 3, 6, 8, 2, 9, 6, 4, 8] as $h)
                                <div class="flex-grow-1 rounded-top-1"
                                    style="height: {{ $h * 10 }}%; min-width: 4px; background-color: rgba(16, 185, 129, 0.25) !important;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects & Achievements Column -->
            <div class="col-lg-8">
                <div
                    class="card border-0 shadow-sm rounded-5 bg-dark bg-opacity-25 border border-white border-opacity-10 overflow-hidden">
                    <div
                        class="card-header bg-transparent border-bottom border-white border-opacity-5 px-4 px-md-5 py-4">
                        <h5 class="fw-black text-white mb-0 text-uppercase tracking-widest small">Project Contributions
                        </h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            @forelse($user->projects as $project)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 rounded-4 p-4 border border-white border-opacity-5 transition hover-bg-light shadow-sm d-flex flex-column group"
                                        style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div class="rounded-4 border border-white border-opacity-10 group-hover-bg-primary transition"
                                                style="background-color: rgba(11, 17, 32, 0.5) !important; padding: 1rem;">
                                                <svg style="width: 24px;" class="group-hover-text-white" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="small text-primary fw-black text-uppercase opacity-75"
                                                style="font-size: 8px;">{{ $project->pivot->role }}</span>
                                        </div>
                                        <h5 class="fw-black text-white mb-2 text-truncate">{{ $project->title }}</h5>
                                        <p class="small text-secondary mb-4 opacity-75 line-clamp-3 lh-base overflow-hidden"
                                            style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                            {{ $project->description }}
                                        </p>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            @if($project->pivot->is_validated)
                                                <div class="d-flex align-items-center gap-2 text-emerald fw-black"
                                                    style="font-size: 8px; text-transform: uppercase;">
                                                    <svg style="width: 12px;" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Validated
                                                </div>
                                            @endif
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="text-secondary small fw-bold text-decoration-none hover-white opacity-50 ms-auto">Details
                                                →</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 py-5 text-center opacity-50 italic">
                                    <p class="text-secondary mb-0">No projects completed yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .rounded-5 {
            border-radius: 3rem !important;
        }

        .text-emerald {
            color: #10b981 !important;
        }

        .bg-emerald {
            background-color: #10b981 !important;
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .shadow-primary {
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.4);
        }

        .hover-scale-5:hover {
            transform: translateY(-5px);
            transition: all 0.3s;
        }

        .hover-bg-light:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .hover-white:hover {
            color: white !important;
            opacity: 1 !important;
        }

        .group-hover-bg-primary:hover,
        .group:hover .group-hover-bg-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }

        .group-hover-text-white:hover,
        .group:hover .group-hover-text-white {
            color: white !important;
        }
    </style>
</x-app-layout>