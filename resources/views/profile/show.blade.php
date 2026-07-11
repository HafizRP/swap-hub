@section('title', $user->name)
<x-app-layout>
    <div class="container mx-auto py-6">
        
        <!-- ROW 1: HEADER CARD -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 mb-6 overflow-hidden relative">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8">
                    <!-- Avatar -->
                    <div class="shrink-0 text-center md:text-left">
                        <div class="relative inline-block">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=random' }}" 
                                 class="rounded-full border-4 border-white dark:border-slate-700 shadow-sm" 
                                 width="128" height="128" style="object-fit: cover;">
                            <!-- Status Indicator -->
                            <div class="absolute bottom-0 right-0 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-700" 
                                 style="width: 24px; height: 24px; bottom: 8px !important; right: 8px !important;"></div>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-grow text-center md:text-left">
                        <h1 class="text-3xl font-black text-slate-850 dark:text-slate-100 mb-1">{{ $user->name }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 mb-2 text-lg">{{ $user->university }} <span class="mx-1">•</span> {{ $user->major }}</p>
                        <div class="flex items-center justify-center md:justify-start gap-3 mt-2">
                             <span class="text-slate-450 dark:text-slate-500 text-sm flex items-center gap-1">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $user->location ?? 'Indonesia, ID' }}
                             </span>
                        </div>
                    </div>
 
                    <!-- Actions -->
                    <div class="shrink-0 flex flex-col sm:flex-row gap-2 justify-center">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="bg-indigo-600 hover:bg-indigo-750 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm flex items-center gap-2 text-sm border-0 transition-colors duration-150 no-underline">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                Edit Profile
                            </a>
                            <a href="{{ route('profile.resume', $user) }}" class="bg-white hover:bg-slate-50 dark:bg-slate-750 dark:hover:bg-slate-700 text-slate-750 dark:text-slate-250 border border-slate-250 dark:border-slate-600 font-bold py-2.5 px-4 rounded-xl flex items-center gap-2 text-sm transition-colors duration-150 no-underline">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Resume
                            </a>
                        @else
                            <form action="{{ route('chat.direct', $user) }}" method="POST">
                                @csrf
                                <button class="bg-indigo-605 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow flex items-center gap-2 text-sm border-0 transition-colors duration-150">
                                     <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                     Message
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
 
        <!-- ROW 2: SPLIT CONTENT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            
            <!-- ABOUT ME -->
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 h-full flex flex-col">
                    <div class="p-6 md:p-8 flex flex-col h-full">
                        <div class="flex items-center gap-2 mb-4">
                            <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0">About Me</h5>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm mb-6 flex-grow">
                            {{ $user->bio ?? "Passionate {$user->major} student looking for collaborative opportunities. Experienced in building web applications and working in agile teams. Always eager to learn new technologies." }}
                        </p>
 
                        <!-- Stats Row -->
                        <div class="mt-auto border-t border-slate-150 dark:border-slate-700/50 pt-4">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <h6 class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider mb-1">Experience</h6>
                                    <p class="font-bold text-sm text-slate-800 dark:text-slate-250 mb-0">Student</p>
                                </div>
                                <div>
                                    <h6 class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider mb-1">Projects</h6>
                                    <p class="font-bold text-sm text-slate-800 dark:text-slate-250 mb-0">{{ $user->projects->count() }}+ Completed</p>
                                </div>
                                <div>
                                    <h6 class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider mb-1">Reputation</h6>
                                    <p class="font-bold text-sm text-slate-800 dark:text-slate-250 mb-0">{{ $user->reputation_points }} CP</p>
                                </div>
                                <div>
                                    <h6 class="uppercase text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wider mb-1">Language</h6>
                                    <p class="font-bold text-sm text-slate-800 dark:text-slate-250 mb-0">English, ID</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- SKILLS & EXPERTISE -->
            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 h-full">
                    <div class="p-6 md:p-8">
                         <div class="flex items-center gap-2 mb-4">
                            <svg class="text-indigo-600 dark:text-indigo-400" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0">Skills & Expertise</h5>
                        </div>
 
                        <!-- Skill Tags -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            @forelse($user->skills as $skill)
                                <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-655 dark:text-slate-350 rounded-lg px-3 py-1.5 font-medium border border-slate-200 dark:border-slate-600 text-xs">
                                    {{ $skill->name }}
                                </span>
                            @empty
                                <span class="text-slate-400 dark:text-slate-500 text-xs">No specific skills listed.</span>
                            @endforelse
                        </div>
 
                        <!-- Tools Proficiency -->
                        <h6 class="text-slate-450 dark:text-slate-550 text-xs font-bold uppercase tracking-wider mb-4 mt-6">Tools Proficiency</h6>
                        <div class="flex flex-col gap-3">
                            @php
                                $displayedSkills = $user->skills->take(3); // Show top 3 for bars
                            @endphp
                            @foreach($displayedSkills as $skill)
                                @php
                                    $levelMap = ['beginner' => 25, 'intermediate' => 50, 'advanced' => 75, 'expert' => 95];
                                    $percent = $levelMap[$skill->pivot->proficiency_level] ?? 50;
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $skill->name }}</span>
                                        <span class="text-xs text-indigo-650 dark:text-indigo-400 font-bold">{{ $percent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-1.5">
                                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                            @if($user->skills->isEmpty())
                                <div class="text-center p-4 bg-slate-50 dark:bg-slate-700/40 rounded-xl">
                                    <small class="text-slate-450">Add skills to see your proficiency stats.</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- ROW 3: GITHUB ACTIVITY -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 mb-6 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-slate-900 text-white rounded-lg p-2.5 flex items-center justify-content-center shrink-0 w-11 h-11">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0 flex items-center gap-2">
                                GitHub Activity 
                                <span class="rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-450 border border-emerald-500/25 px-2 py-0.5 text-[10px] font-bold">Connected</span>
                            </h5>
                            <p class="text-slate-400 dark:text-slate-500 text-xs mb-0">Displaying public contributions</p>
                        </div>
                    </div>
                    <button class="bg-white hover:bg-slate-50 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 border border-slate-250 dark:border-slate-650 rounded-full px-4 py-1.5 text-xs font-bold flex items-center gap-2 transition-colors duration-150">
                         <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                         Sync Now
                    </button>
                </div>
 
                <!-- Fake Contribution Graph -->
                <div class="flex flex-wrap gap-1 px-1">
                    @for($w = 0; $w < 40; $w++) 
                         <div class="flex flex-col gap-1">
                             @for($d = 0; $d < 5; $d++)
                                 @php 
                                     $active = rand(0, 10) > 6;
                                     $opacity = $active ? rand(30, 90) : 10;
                                 @endphp
                                 <div class="rounded-[2px]" style="width: 12px; height: 12px; background-color: rgba(16, 185, 129, {{ $opacity / 100 }});"></div>
                             @endfor
                         </div>
                    @endfor
                </div>
            </div>
        </div>
 
        <!-- ROW 4: PROJECT HISTORY -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
             <div class="p-6 md:p-8 flex justify-between items-center pb-4">
                 <h5 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-0 flex items-center gap-2">
                    <svg class="text-indigo-650 dark:text-indigo-400" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Project History
                 </h5>
                 <a href="{{ route('projects.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-bold no-underline">View All Projects</a>
             </div>
             <div class="p-0 border-t border-slate-100 dark:border-slate-700/50">
                 <div class="overflow-x-auto">
                     <table class="w-full text-nowrap align-middle">
                          <thead class="bg-slate-50 dark:bg-slate-700/30">
                              <tr>
                                  <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Project Name</th>
                                  <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Role</th>
                                  <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Status</th>
                                  <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Action</th>
                              </tr>
                          </thead>
                          <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($user->projects->take(5) as $project)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="rounded-lg bg-slate-100 dark:bg-slate-700 p-2 text-slate-500 dark:text-slate-400">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                                            </div>
                                            <span class="font-bold text-sm text-slate-800 dark:text-slate-100">{{ $project->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                         <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-full text-xs px-3 py-1 font-medium border border-slate-200 dark:border-slate-600">{{ $project->pivot->role }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                         <span class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                             <span class="rounded-full bg-emerald-500 w-2 h-2"></span>
                                             Completed
                                         </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                         <a href="{{ route('projects.show', $project) }}" class="border border-slate-250 dark:border-slate-650 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold py-1 px-3 rounded-full no-underline transition-colors">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-slate-400 dark:text-slate-500 text-sm">No project history found.</td>
                                </tr>
                            @endforelse
                          </tbody>
                     </table>
                 </div>
             </div>
        </div>
 
    </div>
</x-app-layout>