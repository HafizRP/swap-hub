@section('title', $project->title)
<x-app-layout>
    <div x-data="{ showInviteModal: false }" class="container mx-auto py-6">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-grow">
                    <div class="mb-3">
                        <a href="{{ route('projects.index') }}" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-750 dark:text-slate-300 rounded-full px-4 py-1.5 text-xs font-bold inline-flex items-center gap-2 transition-colors duration-150 no-underline">
                            <i class="bi bi-arrow-left"></i> Back to Projects
                        </a>
                    </div>
 
                    <h2 class="text-3xl font-black text-slate-800 dark:text-slate-100 mb-0">
                        {{ $project->title }}
                    </h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if(auth()->id() == $project->owner_id)
                        <a href="{{ route('projects.edit', $project) }}"
                            class="border border-slate-350 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-750 font-bold py-2.5 px-5 rounded-full text-xs transition-colors inline-block text-center no-underline">Edit
                            Project</a>
                        @if($project->conversation)
                            <a href="{{ route('chat', $project->conversation) }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 px-5 rounded-full text-xs transition-colors shadow shadow-indigo-500/20 inline-block text-center no-underline">Enter Squad Chat</a>
                        @endif
                    @else
                        @php
                            $member = $project->members->firstWhere('id', auth()->id());
                            $status = $member ? $member->pivot->status : null;
                        @endphp
                        @if($status === 'active')
                            @if($project->conversation)
                                <a href="{{ route('chat', $project->conversation) }}"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 px-5 rounded-full text-xs transition-colors shadow shadow-indigo-500/20 inline-block text-center no-underline">Enter Squad Chat</a>
                            @else
                                <div class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-450 px-5 py-2.5 rounded-full text-xs font-black border border-emerald-500/25 flex items-center gap-2">
                                    <svg style="width: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Already in Squad
                                </div>
                            @endif
                        @elseif($status === 'pending')
                             <div class="bg-amber-500/10 text-amber-600 dark:text-amber-450 px-5 py-2.5 rounded-full text-xs font-black border border-amber-500/25 flex items-center gap-2">
                                <i class="bi bi-hourglass-split"></i>
                                Request Pending
                            </div>
                        @else
                            {{-- Not a member or rejected. The CTA is shown in the banner below --}}
                        @endif
                    @endif
                </div>
            </div>
        </div>
 
        {{-- Action Card for Non-Members --}}
        @if(auth()->id() !== $project->owner_id)
            @php
                $member = $project->members->firstWhere('id', auth()->id());
                $status = $member ? $member->pivot->status : null;
            @endphp
 
            @if($status === null || $status === 'rejected')
                <div class="bg-indigo-50 dark:bg-indigo-950/20 border-0 shadow-sm rounded-xl flex flex-col md:flex-row md:items-center justify-between mb-6 p-5">
                    <div class="mb-3 md:mb-0">
                        <h5 class="font-bold text-slate-800 dark:text-slate-100 text-lg mb-2"><i class="bi bi-info-circle-fill me-2 text-indigo-600 dark:text-indigo-400"></i>Interested in this project?</h5>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-0">Join the squad and start collaborating with the team!</p>
                    </div>
                    <form action="{{ route('projects.members.add', $project) }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="member">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-full shadow text-sm border-0 transition-colors">
                            <i class="bi bi-person-plus-fill me-2"></i>Apply to Join Squad
                        </button>
                    </form>
                </div>
            @elseif($status === 'pending')
                <div class="bg-amber-50 dark:bg-amber-950/20 border-0 shadow-sm rounded-xl flex items-center gap-4 mb-6 p-5 text-amber-800 dark:text-amber-300">
                    <i class="bi bi-hourglass-split text-2xl"></i>
                    <div>
                        <h5 class="font-bold text-lg mb-1">Request Pending</h5>
                        <p class="text-sm mb-0 text-amber-700/80 dark:text-amber-400/80">Your application to join this project is awaiting approval from the owner.</p>
                    </div>
                </div>
            @elseif($status === 'active' && $project->conversation)
                <div class="bg-emerald-50 dark:bg-emerald-950/20 border-0 shadow-sm rounded-xl flex flex-col md:flex-row md:items-center justify-between mb-6 p-5 text-emerald-800 dark:text-emerald-300">
                    <div class="flex items-center gap-4 mb-3 md:mb-0">
                        <i class="bi bi-check-circle-fill text-2xl"></i>
                        <div>
                            <h5 class="font-bold text-lg mb-1">You're part of this squad!</h5>
                            <p class="text-sm mb-0 text-emerald-700/80 dark:text-emerald-450/85">Join the conversation and collaborate with your team.</p>
                        </div>
                    </div>
                    <a href="{{ route('chat', $project->conversation) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-full shadow text-sm border-0 transition-colors no-underline">
                        <i class="bi bi-chat-dots-fill me-2"></i>Enter Squad Chat
                    </a>
                </div>
            @endif
        @endif
 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
 
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <div class="flex flex-col gap-6">
 
                    <!-- Description Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="border-b border-slate-200 dark:border-slate-700 p-5 bg-transparent">
                            <h5 class="font-black text-slate-850 dark:text-slate-100 text-lg mb-0">Project Overview</h5>
                        </div>
                        <div class="p-6">
                            <div class="project-description text-slate-600 dark:text-slate-400 text-base leading-relaxed">
                                {!! nl2br(e($project->description)) !!}
                            </div>
 
                            <style>
                                .project-description p {
                                    margin-bottom: 1.5rem;
                                }
                                .project-description p:last-child {
                                    margin-bottom: 0;
                                }
                                .project-description ul, .project-description ol {
                                    margin-bottom: 1.5rem;
                                    padding-left: 1.5rem;
                                }
                                .project-description li {
                                    margin-bottom: 0.5rem;
                                }
                                .project-description h1, .project-description h2, .project-description h3, .project-description h4 {
                                    color: var(--bs-body-color);
                                    font-weight: 700;
                                    margin-top: 1.5rem;
                                    margin-bottom: 1rem;
                                }
                                .project-description strong,
                                .project-description b {
                                    font-weight: 700;
                                    color: var(--bs-body-color);
                                }
                            </style>
 
                            @if($project->github_repo_url)
                                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 flex flex-col md:flex-row md:items-center justify-between border border-slate-200 dark:border-slate-700 mt-5 gap-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="bg-white dark:bg-slate-800 rounded-full p-2.5 flex items-center justify-content-center shrink-0 w-12 h-12">
                                            <svg style="width: 24px;" class="fill-black dark:fill-white" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0">
                                                {{ $project->github_repo_name ?? 'Repository linked' }}
                                            </h6>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mb-0">Live GitHub integration active</p>
                                        </div>
                                    </div>
                                    <a href="{{ $project->github_repo_url }}" target="_blank"
                                        class="text-indigo-650 hover:text-indigo-700 text-xs font-black no-underline">
                                        Open Repo ↗
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
 
                    <!-- GitHub Activity Timeline -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div
                            class="border-b border-slate-200 dark:border-slate-700 p-5 flex justify-between items-center bg-transparent">
                            <h5 class="font-black text-slate-850 dark:text-slate-100 text-lg mb-0">Activity Feed</h5>
                            <span
                                class="bg-indigo-150 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full">Live
                                Updates</span>
                        </div>
                        <div class="p-6">
                            <div class="timeline relative pl-5">
                                @forelse($project->githubActivities as $activity)
                                    <div class="timeline-item pb-4 relative">
                                        <div class="timeline-dot absolute left-0 translate-x-[-50%] bg-indigo-600 rounded-full"
                                            style="width: 14px; height: 14px; left: -21px !important; top: 4px; border: 4px solid var(--bs-body-bg) !important;"></div>
                                        <div class="pl-2">
                                            <p class="text-xs text-slate-800 dark:text-slate-200 mb-1"><span
                                                    class="font-black">{{ $activity->user->name }}</span> committed to <span
                                                    class="badge bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-mono">{{ substr($activity->commit_sha, 0, 7) }}</span>
                                            </p>
                                            <p
                                                class="text-xs text-slate-500 font-italic mb-2 pl-2 border-l border-indigo-500/25">
                                                "{{ $activity->commit_message }}"</p>
                                            <span class="text-slate-400 dark:text-slate-500"
                                                style="font-size: 10px;">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <p class="text-slate-400 dark:text-slate-500 text-xs mb-0">Waiting for contribution activity...</p>
                                    </div>
                                @endforelse
                                <div class="timeline-line absolute left-0 top-0 bottom-0 bg-indigo-500/10"
                                    style="width: 2px; left: -21px !important; z-index: 1;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="flex flex-col gap-6">
 
                    <!-- Squad Members -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="border-b border-slate-200 dark:border-slate-700 p-5 bg-transparent">
                            <h5 class="font-black text-slate-850 dark:text-slate-100 text-lg mb-0">Squad Members</h5>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col gap-4">
                                @foreach($project->members as $member)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                                class="rounded-lg object-cover" width="44" height="44">
                                            <div>
                                                <h6 class="font-bold text-slate-800 dark:text-slate-100 text-sm mb-0">{{ $member->name }}</h6>
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ $member->pivot->role }}</span>
                                            </div>
                                        </div>
                                        @if($member->pivot->is_validated)
                                            <svg style="width: 20px;" class="text-indigo-600 shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20" title="Validated Contributor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
 
                            @if(auth()->id() == $project->owner_id)
                                <button @click="showInviteModal = true"
                                    class="border border-dashed border-slate-350 dark:border-slate-600 text-slate-550 dark:text-slate-400 w-full mt-4 rounded-xl py-3.5 font-bold hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                                    <i class="bi bi-plus-lg me-2"></i>Invite Talents
                                </button>
                            @endif
                        </div>
                    </div>
 
                    <!-- Project Stats -->
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-5">
                        <h5 class="font-black text-slate-850 dark:text-slate-100 text-base mb-4">Collaboration Stats</h5>
                        <div class="flex flex-col gap-3">
                            <!-- Commits -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center w-10 h-10">
                                        <i class="bi bi-git"></i>
                                    </div>
                                    <span class="font-bold text-xs text-slate-400 dark:text-slate-550 uppercase">Commits</span>
                                </div>
                                <span class="text-lg font-black text-slate-800 dark:text-slate-100">{{ $project->githubActivities->count() }}</span>
                            </div>
                            
                            <!-- Health -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center w-10 h-10">
                                       <i class="bi bi-activity"></i>
                                    </div>
                                    <span class="font-bold text-xs text-slate-400 dark:text-slate-550 uppercase">Health</span>
                                </div>
                                <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">Good</span>
                            </div>
 
                            <!-- Active -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/40 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 rounded-full flex items-center justify-center w-10 h-10">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <span class="font-bold text-xs text-slate-400 dark:text-slate-550 uppercase">Active</span>
                                </div>
                                <span class="text-lg font-black text-slate-800 dark:text-slate-100">{{ (int) ($project->created_at->diffInDays() + 1) }}d</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Invite Modal (Alpine.js) -->
        <div x-show="showInviteModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/60 p-4" style="display: none;" x-transition>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full shadow-2xl p-6" @click.outside="showInviteModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50 mb-4">
                    <h5 class="font-black text-slate-800 dark:text-slate-100 text-lg">Invite Talent</h5>
                    <button @click="showInviteModal = false" class="text-slate-400 hover:text-slate-655 dark:hover:text-slate-300">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>
                <form action="{{ route('projects.members.add', $project) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-slate-500 dark:text-slate-400 text-xs font-bold mb-2 uppercase">User Email</label>
                        <input type="email" name="email" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2.5 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-5">
                        <label for="role" class="block text-slate-500 dark:text-slate-400 text-xs font-bold mb-2 uppercase">Role</label>
                        <select name="role" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2.5 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                            <option value="collaborator">Collaborator</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-lg text-sm transition-colors border-0">Add Member</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>