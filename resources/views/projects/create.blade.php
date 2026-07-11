@section('title', 'Create Project')
<x-app-layout>
    <div class="container mx-auto py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-1">Create a New Project</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mb-0">Fill in the details below to start collaborating.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-bold text-xs no-underline">Cancel</a>
                <div class="flex items-center gap-2 border-l border-slate-200 dark:border-slate-700 pl-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                         class="rounded-circle" width="32" height="32">
                    <div class="hidden md:block text-right" style="line-height: 1.1;">
                        <p class="mb-0 font-bold text-xs text-slate-800 dark:text-slate-100">{{ auth()->user()->name }}</p>
                        <span class="text-slate-400 dark:text-slate-500 text-[10px]">{{ auth()->user()->role->name ?? 'Student' }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <form method="POST" action="{{ route('projects.store') }}" id="createProjectForm">
                            @csrf
                            
                            <!-- PROJECT BASICS -->
                            <h6 class="font-bold uppercase text-slate-450 dark:text-slate-500 mb-3 text-[11px] tracking-wider">Project Basics</h6>
                            <div class="border-t border-slate-200 dark:border-slate-700 mb-6"></div>
 
                            <!-- Project Name -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Project Name <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                    placeholder="e.g., AI-Powered Study Assistant">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
 
                            <!-- Category -->
                            <div class="mb-5">
                                <label for="category" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                    <option value="Development" {{ old('category') == 'Development' ? 'selected' : '' }}>Development</option>
                                    <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design</option>
                                    <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                </select>
                            </div>
 
                            <!-- Description -->
                            <div class="mb-5 relative">
                                <label for="description" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Description <span class="text-red-500">*</span></label>
                                <textarea id="description" name="description" rows="6" required
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                    placeholder="Describe the problem you are solving, the tech stack, and what you hope to achieve...">{{ old('description') }}</textarea>
                                <div class="text-right mt-1 text-slate-400 dark:text-slate-500 text-[10px]">0 / 500 characters</div>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
 
                            <!-- TEAM & SCOPE -->
                            <h6 class="font-bold uppercase text-slate-450 dark:text-slate-500 mb-3 mt-8 text-[11px] tracking-wider">Team & Scope</h6>
                            <div class="border-t border-slate-200 dark:border-slate-700 mb-6"></div>
 
                            <!-- Skills Needed -->
                            <div class="mb-5">
                                <label class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Skills Needed</label>
                                <div class="bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 flex items-center flex-wrap gap-2">
                                    <div id="skillsContainer" class="flex flex-wrap gap-2">
                                        <!-- Tags will appear here -->
                                        <span class="bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 rounded-full px-2.5 py-1 text-xs flex items-center gap-1 skill-tag">
                                            React <span class="cursor-pointer ms-1 flex items-center" onclick="this.parentElement.remove()"><svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></span>
                                        </span>
                                    </div>
                                    <input type="text" id="skillInput" class="bg-transparent border-0 text-sm flex-1 p-1 outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400" placeholder="Type a skill & press Enter..." style="min-width: 150px;">
                                </div>
                                <input type="hidden" name="skills_hidden" id="skillsHidden">
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                <!-- Team Size -->
                                <div class="col-span-1">
                                    <label class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Team Size</label>
                                    <select id="teamSize" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="3-4 Members">3-4 Members</option>
                                        <option value="5-10 Members">5-10 Members</option>
                                        <option value="10+ Members">10+ Members</option>
                                    </select>
                                </div>
                                
                                <!-- Estimated Timeline -->
                                <div class="col-span-1">
                                    <label class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Estimated Timeline</label>
                                    <select id="timeline" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">1 Year</option>
                                    </select>
                                    <!-- Hidden End Date Input -->
                                    <input type="hidden" name="end_date" id="end_date">
                                </div>
                            </div>
 
                            <!-- VISIBILITY -->
                            <h6 class="font-bold uppercase text-slate-450 dark:text-slate-500 mb-3 mt-8 text-[11px] tracking-wider">Visibility</h6>
                            <div class="border-t border-slate-200 dark:border-slate-700 mb-6"></div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ visibility: 'public' }">
                                <div class="col-span-1">
                                    <label class="flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                                           :class="visibility === 'public' ? 'border-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30'">
                                        <input type="radio" class="hidden" name="visibility" value="public" checked @click="visibility = 'public'">
                                        <div class="bg-indigo-500/10 rounded-full p-2.5 text-indigo-650 dark:text-indigo-400">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h6 class="font-bold mb-1 text-sm text-slate-800 dark:text-slate-100">Public Project</h6>
                                            <p class="mb-0 text-slate-400 dark:text-slate-500 text-[11px] leading-snug">Visible to all students on Swap Hub. Best for finding new teammates.</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-span-1">
                                    <label class="flex items-start gap-3 p-4 rounded-xl border cursor-pointer transition-all"
                                           :class="visibility === 'invite' ? 'border-indigo-500 bg-indigo-50/20 dark:bg-indigo-950/10' : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30'">
                                        <input type="radio" class="hidden" name="visibility" value="invite" @click="visibility = 'invite'">
                                        <div class="bg-slate-500/10 rounded-full p-2.5 text-slate-500 dark:text-slate-400">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </div>
                                        <div>
                                            <h6 class="font-bold mb-1 text-sm text-slate-800 dark:text-slate-100">Invite Only</h6>
                                            <p class="mb-0 text-slate-400 dark:text-slate-500 text-[11px] leading-snug">Only people you invite can see and join this project.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
 
                            <!-- GitHub Integration -->
                            <div class="mt-6" x-data="{ gitOpen: false }">
                                <button @click="gitOpen = !gitOpen" type="button" class="text-slate-500 hover:text-slate-750 dark:text-slate-450 dark:hover:text-slate-200 text-xs font-bold flex items-center gap-2 border-0 bg-transparent outline-none">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
                                    GitHub Integration Settings
                                    <i class="bi" :class="gitOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                </button>
                                <div x-show="gitOpen" class="mt-4" style="display: none;" x-transition>
                                     <!-- Repo Selector -->
                                    <label for="github_repo_url" class="block font-bold text-slate-700 dark:text-slate-355 text-xs mb-2">Link Repository</label>
                                    @if(count($repositories) > 0)
                                        <select id="github_repo_url" name="github_repo_url"
                                            onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                            class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                            <option value="">Select a repository (Optional)</option>
                                            @foreach($repositories as $repo)
                                                <option value="{{ $repo['html_url'] }}">{{ $repo['full_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="github_repo_name" name="github_repo_name" value="{{ old('github_repo_name') }}">
                                        
                                        @if(auth()->user()->github_token)
                                        <div class="flex items-center gap-2 mt-3">
                                            <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500" type="checkbox" id="setup_webhook" name="setup_webhook" value="1" checked>
                                            <label class="text-xs text-slate-600 dark:text-slate-400" for="setup_webhook">
                                                Auto-setup Webhook (Recommended)
                                            </label>
                                        </div>
                                        @endif
                                    @else
                                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-lg p-3 text-xs text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            No repositories found. <a href="{{ route('auth.github') }}" class="font-bold underline text-indigo-650">Link GitHub Account</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
 
                            <div class="border-t border-slate-200 dark:border-slate-700 my-8"></div>
 
                            <div class="flex justify-between items-center">
                                <a href="#" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 text-sm font-bold no-underline">Save as Draft</a>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm flex items-center gap-2 transition-colors border-0">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Create Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Scripts for Visual Inputs and Data Processing -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Skill Input Logic
            const skillInput = document.getElementById('skillInput');
            const skillsContainer = document.getElementById('skillsContainer');
            
            skillInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const val = this.value.trim();
                    if (val) {
                        addSkillTag(val);
                        this.value = '';
                    }
                }
            });
 
            function addSkillTag(text) {
                const tag = document.createElement('span');
                tag.className = 'bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 rounded-full px-2.5 py-1 text-xs flex items-center gap-1 skill-tag';
                tag.innerHTML = `${text} <span class="cursor-pointer ms-1 flex items-center" onclick="this.parentElement.remove()"><svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></span>`;
                skillsContainer.appendChild(tag);
            }
 
            // 2. Timeline Logic (Set End Date)
            const timelineSelect = document.getElementById('timeline');
            const endDateInput = document.getElementById('end_date');
            
            function updateEndDate() {
                const months = parseInt(timelineSelect.value);
                const date = new Date();
                date.setMonth(date.getMonth() + months);
                const dateString = date.toISOString().split('T')[0];
                endDateInput.value = dateString;
            }
            timelineSelect.addEventListener('change', updateEndDate);
            updateEndDate(); // Init
 
            // 3. Form Submission (Append Data to Description)
            const form = document.getElementById('createProjectForm');
            form.addEventListener('submit', function(e) {
                const skills = Array.from(skillsContainer.querySelectorAll('.skill-tag')).map(el => el.innerText.trim());
                const teamSize = document.getElementById('teamSize').value;
                const visibility = document.querySelector('input[name="visibility"]:checked').value;
                const visibilityLabel = visibility === 'public' ? 'Public' : 'Invite Only';
 
                const descInput = document.getElementById('description');
                let appendText = `\n\n---\n**Project Details:**`;
                if (skills.length) appendText += `\n- **Skills Needed:** ${skills.join(', ')}`;
                appendText += `\n- **Team Size:** ${teamSize}`;
                appendText += `\n- **Visibility:** ${visibilityLabel}`;
                
                descInput.value += appendText;
            });
        });
    </script>
</x-app-layout>