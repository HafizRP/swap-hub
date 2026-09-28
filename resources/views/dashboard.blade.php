@section('title', 'Dashboard')
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 justify-center">
 
            <!-- LEFT COLUMN: Main Content (8 cols) -->
            <div class="lg:col-span-8">
 
                <!-- 1. Header & Welcome -->
                <div class="flex flex-col md:flex-row justify-between md:items-end mb-6 gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight mb-1">Selamat Datang, {{ explode(' ', $user->name)[0] }} 👋</h2>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-0">Here is what's happening with your projects today.</p>
                    </div>
                    <div>
                        <a href="{{ route('projects.create') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl transition-all duration-150 active:scale-[0.98] shadow-sm flex items-center gap-2 text-sm w-fit no-underline">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create New Project
                        </a>
                    </div>
                </div>
 
                <!-- 2. Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <!-- Reputation -->
                    <div class="col-span-1">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-slate-200/80 dark:border-slate-700/60 h-full hover-lift transition-all">
                            <div class="p-4 flex items-center gap-3.5">
                                <div class="bg-amber-500/10 rounded-xl p-3 flex items-center justify-center text-amber-600 dark:text-amber-400 w-12 h-12 shrink-0">
                                    <svg width="22" height="22" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-slate-400 dark:text-slate-500 uppercase font-bold text-[10px] tracking-wider mb-0">Total Reputation</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">{{ number_format($user->reputation_points) }}</h4>
                                    <span class="text-emerald-600 dark:text-emerald-400 text-xs font-bold">Elite Member</span>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <!-- Completed -->
                    <div class="col-span-1">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-slate-200/80 dark:border-slate-700/60 h-full hover-lift transition-all">
                            <div class="p-4 flex items-center gap-3.5">
                                <div class="bg-indigo-500/10 rounded-xl p-3 flex items-center justify-center text-indigo-600 dark:text-indigo-400 w-12 h-12 shrink-0">
                                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-slate-400 dark:text-slate-500 uppercase font-bold text-[10px] tracking-wider mb-0">Projects Done</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">{{ $completedProjectsCount }}</h4>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
 
                    <!-- Invites -->
                    <div class="col-span-1">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-slate-200/80 dark:border-slate-700/60 h-full hover-lift transition-all">
                            <div class="p-4 flex items-center gap-3.5">
                                <div class="bg-sky-500/10 rounded-xl p-3 flex items-center justify-center text-sky-600 dark:text-sky-400 w-12 h-12 shrink-0">
                                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-slate-400 dark:text-slate-500 uppercase font-bold text-[10px] tracking-wider mb-0">Invites</p>
                                    <h4 class="text-2xl font-black text-slate-900 dark:text-slate-100 tabular-nums tracking-tight mb-0">{{ $collaborationInvitesCount }}</h4>
                                    <span class="text-slate-500 dark:text-slate-400 text-xs">New Requests</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
 
                <!-- 3. Active Projects -->
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0">Active Projects</h5>
                    <a href="{{ route('projects.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold no-underline hover:underline">View All</a>
                </div>
 
                <div class="flex flex-col gap-3 mb-8">
                    @forelse($activeProjects as $project)
                        <div class="w-full">
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-slate-200/80 dark:border-slate-700/60 p-4 hover-lift transition-all">
                                <div class="flex items-center gap-3.5">
                                    <!-- Icon / Letter -->
                                    <div class="rounded-xl bg-indigo-500/10 p-3 flex items-center justify-center w-12 h-12 shrink-0">
                                        <span class="font-black text-lg text-indigo-600 dark:text-indigo-400">{{ substr($project->title, 0, 1) }}</span>
                                    </div>
 
                                    <!-- Details -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-col sm:flex-row sm:justify-between mb-1 gap-1">
                                            <h6 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-0 truncate">{{ $project->title }}</h6>
                                            <div class="flex items-center gap-2">
                                                <span class="bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] px-2.5 py-1 font-medium">{{ ucfirst($project->status) }}</span>
                                                @if($project->end_date)
                                                    <span class="bg-slate-500/10 text-slate-600 dark:text-slate-400 rounded-full text-[10px] px-2.5 py-1 font-medium">Due: {{ \Carbon\Carbon::parse($project->end_date)->format('M d') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-slate-500 dark:text-slate-400 text-xs">Role: {{ ucfirst($project->pivot?->role ?? 'Member') }}</span>
                                            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-bold flex items-center no-underline hover:translate-x-0.5 transition-transform">
                                                Details 
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="ml-1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <!-- Fake Progress Bar -->
                                        <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-1 mt-2.5">
                                            @php $prog = rand(20, 90); @endphp
                                            <div class="bg-indigo-600 h-1 rounded-full" style="width: {{ $prog }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full">
                            <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 p-8 rounded-xl text-center">
                                <p class="text-slate-500 dark:text-slate-400 mb-0">You have no active projects currently.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
 
                <!-- 4. Recommendations -->
                <div class="flex justify-between items-center mb-3">
                    <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0">Project Recommendations</h5>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors active:scale-95">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button class="w-8 h-8 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors active:scale-95">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
 
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($recommendedProjects as $project)
                        <div class="col-span-1">
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-card border border-slate-200/80 dark:border-slate-700/60 p-5 h-full flex flex-col relative hover-lift transition-all">
                                <span class="bg-slate-500/10 text-slate-600 dark:text-slate-400 absolute top-4 right-4 rounded-full text-[10px] px-2.5 py-1 font-medium">{{ $project->category }}</span>
                                <div class="mb-3.5">
                                    <div class="bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl p-2.5 inline-flex w-fit">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <h6 class="font-bold text-slate-800 dark:text-slate-100 text-base mb-1.5 truncate">{{ $project->title }}</h6>
                                <p class="text-slate-500 dark:text-slate-400 text-xs mb-4 line-clamp-2"
                                    style="min-height: 2.5em;">{{ $project->description }}</p>
 
                                <div class="mt-auto">
                                    <div class="flex gap-2 mb-3">
                                        <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-350 text-xs px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600">React</span>
                                        <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-350 text-xs px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600">API</span>
                                    </div>
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="block text-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] no-underline">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2">
                            <p class="text-slate-400 text-sm mb-0">No recommendations at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
                    @empty
                        <div class="col-span-2">
                            <p class="text-slate-400 text-sm mb-0">No recommendations at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
 
            <!-- RIGHT COLUMN: Sidebar (4 cols) -->
            <div class="lg:col-span-4">
 
                <!-- Notifications -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
                    <div class="p-4 pb-2 flex justify-between items-center border-b border-slate-100 dark:border-slate-700/50">
                        <h6 class="font-bold text-slate-800 dark:text-slate-100 mb-0 text-base">Notifications</h6>
                        <a href="#" class="text-xs text-indigo-600 dark:text-indigo-400 font-bold no-underline">Mark all read</a>
                    </div>
                    <div class="flex flex-col p-2 gap-1">
                        @forelse($notifications as $notif)
                            <a href="{{ $notif->data['link'] ?? '#' }}"
                                class="flex items-center gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors rounded-lg no-underline w-full">
                                <div class="shrink-0">
                                    <img src="{{ $notif->data['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode(substr($notif->data['message'] ?? 'S', 0, 1)) . '&background=random' }}"
                                        class="rounded-circle object-cover" width="36" height="36">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="mb-1 text-xs lh-sm text-slate-700 dark:text-slate-300 truncate">
                                        {{ $notif->data['message'] ?? 'New message' }}</p>
                                    <small class="text-slate-400 dark:text-slate-500 block"
                                        style="font-size: 10px;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @empty
                            <div class="text-center p-6">
                                <p class="text-slate-400 dark:text-slate-500 text-sm mb-0">No new messages</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-4 pt-0 border-0 text-center pb-3">
                        <a href="#" class="text-slate-400 dark:text-slate-500 text-xs font-bold no-underline hover:text-indigo-600">VIEW ALL NOTIFICATIONS</a>
                    </div>
                </div>
 
                <!-- Upcoming Deadlines -->
                <div class="bg-indigo-600 dark:bg-indigo-700 text-white rounded-xl shadow-sm overflow-hidden relative">
                    <div class="absolute top-0 right-0 opacity-10 p-3">
                        <svg width="100" height="100" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="p-5 position-relative z-10">
                        <h6 class="font-bold mb-4 text-base">Upcoming Deadlines</h6>
 
                        <div class="flex flex-col gap-3">
                            @forelse($upcomingDeadlines as $deadline)
                                <div class="bg-white/10 rounded-lg p-3 border border-white/10">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="bg-amber-400 rounded-full w-2 h-2"></div>
                                        <small class="font-bold text-[10px] text-amber-200">{{ \Carbon\Carbon::parse($deadline->end_date)->format('M d, H:i A') }}</small>
                                    </div>
                                    <h6 class="font-bold mb-0 text-sm truncate">{{ $deadline->title }}</h6>
                                </div>
                            @empty
                                <div class="bg-white/10 rounded-lg p-3 border border-white/10">
                                    <p class="mb-0 text-sm">No upcoming deadlines.</p>
                                </div>
                            @endforelse
                        </div>
 
                        <button class="w-full mt-4 bg-white hover:bg-slate-50 text-indigo-700 font-bold py-2.5 px-4 rounded-full transition-colors shadow-sm text-sm border-0">
                            Open Calendar
                        </button>
                    </div>
                </div>
 
            </div>
 
        </div>
    </div>
</x-app-layout>