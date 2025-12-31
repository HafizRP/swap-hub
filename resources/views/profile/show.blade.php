@section('title', $user->name)
<x-app-layout>
    <div class="container py-5">
        
        <!-- ROW 1: HEADER CARD -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative" style="background-color: var(--bs-card-bg);">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center gap-4 gap-md-0">
                    <!-- Avatar -->
                    <div class="col-md-auto text-center text-md-start">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=random' }}" 
                                 class="rounded-circle border border-4 border-white shadow-sm" 
                                 width="128" height="128" style="object-fit: cover;">
                            <!-- Online/Status Indicator -->
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" 
                                 style="width: 24px; height: 24px; bottom: 8px !important; right: 8px !important;"></div>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="col-md flex-grow-1 text-center text-md-start">
                        <h1 class="fw-bold mb-1">{{ $user->name }}</h1>
                        <p class="text-secondary mb-2 fs-5">{{ $user->university }} <span class="mx-1">•</span> {{ $user->major }}</p>
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3 mt-2">
                             <span class="text-secondary small d-flex align-items-center gap-1">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $user->location ?? 'Indonesia, ID' }}
                             </span>
                             <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold small">
                                 Open to work
                             </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-auto d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Edit Profile
                            </a>
                        @else
                            <form action="{{ route('chat.direct', $user) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2">
                                     <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                     Message
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('profile.resume', $user) }}" class="btn btn-light border rounded-3 px-4 py-2 fw-bold d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Resume
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: SPLIT CONTENT -->
        <div class="row g-4 mb-4">
            
            <!-- ABOUT ME -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: var(--bs-card-bg);">
                    <div class="card-body p-4 p-md-5 d-flex flex-column">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <h5 class="fw-bold mb-0">About Me</h5>
                        </div>
                        <p class="text-secondary lh-lg mb-5">
                            {{ $user->bio ?? "Passionate {$user->major} student looking for collaborative opportunities. Experienced in building web applications and working in agile teams. Always eager to learn new technologies." }}
                        </p>

                        <!-- Stats Row -->
                        <div class="mt-auto">
                            <div class="row g-4 border-top pt-4">
                                <div class="col-6 col-md-3">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 10px; letter-spacing: 1px;">Experience</h6>
                                    <p class="fw-bold mb-0">Student</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 10px; letter-spacing: 1px;">Projects</h6>
                                    <p class="fw-bold mb-0">{{ $user->projects->count() }}+ Completed</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 10px; letter-spacing: 1px;">Reputation</h6>
                                    <p class="fw-bold mb-0">{{ $user->reputation_points }} CP</p>
                                </div>
                                <div class="col-6 col-md-3">
                                    <h6 class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 10px; letter-spacing: 1px;">Language</h6>
                                    <p class="fw-bold mb-0">English, ID</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SKILLS & EXPERTISE -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: var(--bs-card-bg);">
                    <div class="card-body p-4 p-md-5">
                         <div class="d-flex align-items-center gap-2 mb-4">
                            <svg class="text-primary" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <h5 class="fw-bold mb-0">Skills & Expertise</h5>
                        </div>

                        <!-- Skill Tags -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @forelse($user->skills as $skill)
                                <span class="badge bg-secondary-subtle text-secondary rounded-3 px-3 py-2 fw-medium border border-light-subtle">
                                    {{ $skill->name }}
                                </span>
                            @empty
                                <span class="text-secondary small">No specific skills listed.</span>
                            @endforelse
                        </div>

                        <!-- Tools Proficiency (Progress Bars) -->
                        <h6 class="text-secondary small fw-bold mb-3 mt-4">Tools Proficiency</h6>
                        <div class="d-flex flex-column gap-3">
                            @php
                                $displayedSkills = $user->skills->take(3); // Show top 3 for bars
                            @endphp
                            @foreach($displayedSkills as $skill)
                                @php
                                    $levelMap = ['beginner' => 25, 'intermediate' => 50, 'advanced' => 75, 'expert' => 95];
                                    $percent = $levelMap[$skill->pivot->proficiency_level] ?? 50;
                                @endphp
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-bold">{{ $skill->name }}</span>
                                        <span class="small text-primary fw-bold">{{ $percent }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($user->skills->isEmpty())
                                <div class="text-center p-3 bg-light rounded-3">
                                    <small class="text-secondary">Add skills to see your proficiency stats.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: GITHUB ACTIVITY -->
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background-color: var(--bs-card-bg);">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-dark text-white rounded-3 p-2 d-flex align-items-center justify-content-center">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                                GitHub Activity 
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill small fw-bold px-2 py-1" style="font-size: 10px;">Connected</span>
                            </h5>
                            <p class="text-secondary small mb-0">Displaying public contributions</p>
                        </div>
                    </div>
                    <button class="btn btn-light border rounded-pill px-3 py-1 small fw-bold d-flex align-items-center gap-2">
                         <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                         Sync Now
                    </button>
                </div>

                <!-- Fake Contribution Graph -->
                <div class="d-flex flex-wrap gap-1 px-1">
                    @for($w = 0; $w < 40; $w++) 
                         <div class="d-flex flex-column gap-1">
                             @for($d = 0; $d < 5; $d++)
                                 @php 
                                                                 $active = rand(0, 10) > 6;
                                    $opacity = $active ? rand(30, 90) : 10;
                                 @endphp
                                 <div class="rounded-1" style="width: 12px; height: 12px; background-color: rgba(16, 185, 129, {{ $opacity / 100 }});"></div>
                             @endfor
                         </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- ROW 4: PROJECT HISTORY -->
        <div class="card border-0 shadow-sm rounded-4" style="background-color: var(--bs-card-bg);">
             <div class="card-header bg-transparent border-0 pt-4 px-4 px-md-5 d-flex justify-content-between align-items-center">
                 <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <svg class="text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Project History
                 </h5>
                 <a href="{{ route('projects.index') }}" class="text-primary small fw-bold text-decoration-none">View All Projects</a>
             </div>
             <div class="card-body p-0">
                 <div class="table-responsive">
                     <table class="table align-middle mb-0 text-nowrap">
                         <thead class="bg-light">
                             <tr>
                                 <th class="px-4 px-md-5 py-3 small text-secondary fw-bold text-uppercase border-0 rounded-start-4">Project Name</th>
                                 <th class="px-4 py-3 small text-secondary fw-bold text-uppercase border-0">Role</th>
                                 <th class="px-4 py-3 small text-secondary fw-bold text-uppercase border-0">Status</th>
                                 <th class="px-4 px-md-5 py-3 small text-secondary fw-bold text-uppercase border-0 rounded-end-4 text-end">Action</th>
                             </tr>
                         </thead>
                         <tbody>
                            @forelse($user->projects->take(5) as $project)
                                <tr>
                                    <td class="px-4 px-md-5 py-3 border-bottom-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 bg-secondary bg-opacity-10 p-2 text-secondary">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                            </div>
                                            <span class="fw-bold">{{ $project->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 border-bottom-0">
                                         <span class="badge bg-secondary-subtle text-dark border rounded-pill fw-normal px-3">{{ $project->pivot->role }}</span>
                                    </td>
                                    <td class="px-4 py-3 border-bottom-0">
                                         <span class="d-flex align-items-center gap-2">
                                             <span class="rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                                             Completed
                                         </span>
                                    </td>
                                    <td class="px-4 px-md-5 py-3 border-bottom-0 text-end">
                                         <a href="{{ route('projects.show', $project) }}" class="btn btn-light border btn-sm rounded-pill fw-bold">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">No project history found.</td>
                                </tr>
                            @endforelse
                         </tbody>
                     </table>
                 </div>
             </div>
        </div>

    </div>

    <!-- Theming -->
    <style>
        :root {
            --bs-card-bg: #fff;
        }
        [data-bs-theme="dark"] {
            --bs-card-bg: #1e293b;
            --bs-body-bg: #0f172a;
        }
        [data-bs-theme="dark"] .bg-light {
            background-color: #334155 !important;
        }
    </style>
</x-app-layout>