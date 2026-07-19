@section('title', 'Browse Projects')
<x-app-layout>
    <div class="container mx-auto py-6" x-data="{ showFilters: false }">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-3">
                <!-- Mobile Filter Toggle -->
                <button @click="showFilters = !showFilters" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-sm lg:hidden mb-4 flex items-center justify-center gap-2">
                    <i class="bi bi-funnel-fill"></i> Filters & Search
                </button>
 
                <div class="lg:block" :class="showFilters ? 'block' : 'hidden'">
                    <div class="flex flex-col gap-4 sticky top-[100px] z-10">
                        <div>
                            <h5 class="font-bold text-slate-800 dark:text-slate-100 mb-3 hidden lg:block">Filter By</h5>
                            
                            <form action="{{ route('projects.index') }}" method="GET">
                                <!-- Search -->
                                <div class="relative mb-4">
                                    <span class="absolute top-1/2 left-3 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                        </svg>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                        class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full pl-10 pr-4 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500" 
                                        placeholder="Search keywords...">
                                </div>
 
                                <!-- Filters Panel -->
                                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden" x-data="{ openCat: true, openStatus: true }">
                                    
                                    <!-- Project Type (Category) -->
                                    <div>
                                        <button @click="openCat = !openCat" type="button" class="w-full flex justify-between items-center font-bold text-slate-800 dark:text-slate-100 p-4 text-sm bg-transparent border-0 outline-none text-left">
                                            <span>Project Type</span>
                                            <i class="bi" :class="openCat ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                        </button>
                                        <div x-show="openCat" class="px-4 pb-4 pt-0">
                                            @foreach(['Development', 'Design', 'Marketing', 'Research'] as $cat)
                                                <label class="flex items-center gap-2 my-2 cursor-pointer text-slate-600 dark:text-slate-400 text-sm">
                                                    <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500" type="radio" name="category" value="{{ $cat }}" id="cat-{{ $cat }}" 
                                                        {{ request('category') == $cat ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <span>{{ $cat }}</span>
                                                </label>
                                            @endforeach
                                            <label class="flex items-center gap-2 my-2 cursor-pointer text-slate-600 dark:text-slate-400 text-sm">
                                                <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500" type="radio" name="category" value="" id="cat-all" 
                                                    {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                                <span>All Types</span>
                                            </label>
                                        </div>
                                    </div>
 
                                    <div class="border-t border-slate-100 dark:border-slate-700/50"></div>
 
                                    <!-- Status -->
                                    <div>
                                        <button @click="openStatus = !openStatus" type="button" class="w-full flex justify-between items-center font-bold text-slate-800 dark:text-slate-100 p-4 text-sm bg-transparent border-0 outline-none text-left">
                                            <span>Status</span>
                                            <i class="bi" :class="openStatus ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                        </button>
                                        <div x-show="openStatus" class="px-4 pb-4 pt-0">
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-650 dark:text-slate-400 text-sm">Open for application</span>
                                                <input class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500" type="checkbox" checked disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Main Content -->
            <div class="lg:col-span-9">
                <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-3">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-1">Explore Student Projects</h2>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-0">Find the perfect team for your next big idea.</p>
                    </div>
                    <span class="text-slate-450 dark:text-slate-500 text-xs font-semibold">Showing {{ $projects->count() }} projects</span>
                </div>
 
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($projects as $project)
                        <div class="col-span-1">
                            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 h-full hover-lift transition-all">
                                <div class="p-5 flex flex-col h-full">
                                    <!-- Header -->
                                    <div class="flex justify-between items-start mb-3 gap-2">
                                        <h5 class="font-bold text-base mb-0 text-slate-800 dark:text-slate-100 truncate flex-1" title="{{ $project->title }}">
                                            {{ $project->title }}
                                        </h5>
                                        @php
                                            $statusBadge = match ($project->status) {
                                                'active' => ['text' => 'text-emerald-600 dark:text-emerald-400', 'bg' => 'bg-emerald-500/10', 'label' => 'Open'],
                                                'planning' => ['text' => 'text-indigo-600 dark:text-indigo-400', 'bg' => 'bg-indigo-500/10', 'label' => 'Planning'],
                                                default => ['text' => 'text-slate-650 dark:text-slate-400', 'bg' => 'bg-slate-500/10', 'label' => ucfirst($project->status)],
                                            };
                                        @endphp
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold shrink-0 {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </div>
 
                                    <!-- Description -->
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 flex-grow line-clamp-3" style="min-height: 4.5em;">
                                        {{ Str::limit($project->description, 120) }}
                                    </p>
 
                                    <!-- Tags -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-655 dark:text-slate-350 rounded text-[10px] px-2 py-0.5 border border-slate-200 dark:border-slate-600">
                                            {{ $project->category ?? 'General' }}
                                        </span>
                                        @if($project->start_date)
                                            <span class="bg-slate-100 dark:bg-slate-700/50 text-slate-655 dark:text-slate-350 rounded text-[10px] px-2 py-0.5 border border-slate-200 dark:border-slate-600">
                                                {{ $project->start_date->format('M Y') }}
                                            </span>
                                        @endif
                                    </div>
 
                                    <!-- Footer -->
                                    <div class="flex justify-between items-center mt-auto pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <img src="{{ $project->owner->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($project->owner->name) . '&background=random' }}" 
                                                 class="rounded-circle object-cover" width="24" height="24" alt="Author">
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate" style="max-width: 80px;">{{ $project->owner->name }}</span>
                                        </div>
                                        <a href="{{ route('projects.show', $project) }}" class="text-indigo-650 hover:text-indigo-700 text-xs font-bold no-underline">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 py-10 text-center">
                            <div class="mb-4">
                                <svg class="text-slate-400 dark:text-slate-600 mx-auto opacity-50" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h5 class="font-bold text-slate-500 dark:text-slate-400 mb-3">No projects found</h5>
                            <a href="{{ route('projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-full text-xs shadow transition-colors">Create Project</a>
                        </div>
                    @endforelse
                </div>
 
                <!-- Pagination -->
                <div class="flex justify-center mt-8">
                    {{ $projects->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>