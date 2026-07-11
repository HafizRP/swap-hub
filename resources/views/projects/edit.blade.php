@section('title', 'Edit Project')
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <!-- Header -->
                <div class="mb-6">
                    <nav class="mb-2">
                        <ol class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400">
                            <li><a href="{{ route('projects.show', $project) }}" class="no-underline text-slate-550 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Back to Project</a></li>
                            <li>/</li>
                            <li class="text-slate-400 dark:text-slate-500">Edit</li>
                        </ol>
                    </nav>
                    <h2 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-0">{{ __('Edit Project Details') }}</h2>
                </div>
 
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="h-1 bg-gradient-to-r from-indigo-500 to-indigo-400"></div>
                    <div class="p-6 md:p-8">
                        <form method="POST" action="{{ route('projects.update', $project) }}">
                            @csrf
                            @method('PUT')
 
                            <!-- Title -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Project Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
 
                            <!-- Description -->
                            <div class="mb-5">
                                <label for="description" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Description</label>
                                <textarea id="description" name="description" rows="6" required
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                <!-- GitHub Integration -->
                                <div class="col-span-2">
                                    <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
                                        <div class="flex items-center gap-2 mb-4 text-slate-800 dark:text-slate-100">
                                            <i class="bi bi-github text-xl"></i>
                                            <h6 class="font-bold mb-0 text-base">GitHub Integration</h6>
                                        </div>
 
                                        @if(count($repositories) > 0)
                                            <label for="github_repo_url" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Select Repository</label>
                                            <select id="github_repo_url" name="github_repo_url"
                                                onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                                class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 mb-4 transition-colors">
                                                <option value="">Select a repository (Optional)</option>
                                                @foreach($repositories as $repo)
                                                    <option value="{{ $repo['html_url'] }}" {{ old('github_repo_url', $project->github_repo_url) == $repo['html_url'] ? 'selected' : '' }}>
                                                        {{ $repo['full_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="github_repo_name" name="github_repo_name"
                                                value="{{ old('github_repo_name', $project->github_repo_name) }}">
 
                                            <!-- Webhook Status & Reconnect -->
                                            @if($project->github_repo_url)
                                                <div class="flex flex-col sm:flex-row justify-between sm:items-center bg-white dark:bg-slate-800 p-4 rounded-lg border border-slate-250 dark:border-slate-700 gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold text-slate-500 dark:text-slate-450 uppercase">Webhook Status:</span>
                                                        @php
                                                            $whColor = $project->github_webhook_status === 'active' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/25' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/25';
                                                        @endphp
                                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-semibold border {{ $whColor }}">
                                                            {{ ucfirst($project->github_webhook_status ?? 'Not Setup') }}
                                                        </span>
                                                    </div>
                                                    <button form="reconnectWebhookForm" type="submit"
                                                        class="border border-indigo-550 text-indigo-650 hover:bg-indigo-50 dark:hover:bg-indigo-950/20 font-bold py-1.5 px-4 rounded-full text-xs transition-colors shrink-0 flex items-center justify-center gap-1">
                                                        <i class="bi bi-arrow-repeat"></i>Reconnect Loop
                                                    </button>
                                                </div>
                                            @endif
 
                                        @else
                                            <div class="bg-amber-500/10 text-amber-650 rounded-lg p-3 text-xs border border-amber-500/25 mb-0">
                                                <i class="bi bi-exclamation-circle me-1"></i> No repositories found.
                                                <a href="{{ route('profile.edit') }}" class="underline font-bold">Connect GitHub Account</a>
                                            </div>
                                        @endif
                                        <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                                    </div>
                                </div>
 
                                <!-- Category -->
                                <div class="col-span-1">
                                    <label for="category" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Category</label>
                                    <select id="category" name="category" required
                                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
 
                                <!-- Status -->
                                <div class="col-span-1">
                                    <label for="status" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Status</label>
                                    <select id="status" name="status" required
                                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
 
                                <!-- Start Date -->
                                <div class="col-span-1">
                                    <label for="start_date" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">Start Date</label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
 
                                <!-- End Date -->
                                <div class="col-span-1">
                                    <label for="end_date" class="block font-bold text-slate-700 dark:text-slate-355 text-sm mb-2">End Date</label>
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </div>
 
                            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="block text-center flex-1 border border-slate-350 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-bold py-3 px-5 rounded-full text-sm transition-colors no-underline">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-750 hover:bg-indigo-700 text-white font-bold py-3 px-5 rounded-full text-sm transition-colors border-0 shadow">
                                    Update Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- External Form for Webhook Reconnect -->
    <form id="reconnectWebhookForm" action="{{ route('projects.webhook.reconnect', $project) }}" method="POST"
        class="hidden">
        @csrf
    </form>
</x-app-layout>