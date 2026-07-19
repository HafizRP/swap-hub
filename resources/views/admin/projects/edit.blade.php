@section('title', 'Edit Project')
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-right bg-gradient-to-r from-indigo-500 to-indigo-400"></div>
                    <div class="p-6 md:p-8">
                        <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                            @csrf
                            @method('PUT')
 
                            <!-- Title -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Project Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                                    required autofocus
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                    placeholder="e.g. Building a Sustainable Campus Mobile App">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
 
                            <!-- Description -->
                            <div class="mb-5">
                                <label for="description" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Description</label>
                                <textarea id="description" name="description" rows="5" required
                                    class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                    placeholder="Describe what you want to achieve, the stack you're using, and who you're looking for...">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- GitHub Repo URL -->
                                <div class="col-span-1">
                                    <label for="github_repo_url" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">GitHub Repository</label>
                                    <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-600">
                                        <span class="inline-flex items-center px-3 bg-slate-100 dark:bg-slate-700 border-r border-slate-200 dark:border-slate-600 text-slate-455 text-slate-400 text-sm">
                                            <svg style="width: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                        </span>
                                        <input type="url" id="github_repo_url" name="github_repo_url"
                                            value="{{ old('github_repo_url', $project->github_repo_url) }}"
                                            class="flex-1 w-full bg-slate-50 dark:bg-slate-700/50 border-0 px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition-colors"
                                            placeholder="https://github.com/user/repo">
                                    </div>
                                    <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                                </div>
 
                                <!-- GitHub Repo Name -->
                                <div class="col-span-1">
                                    <label for="github_repo_name" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Repository Name</label>
                                    <input type="text" id="github_repo_name" name="github_repo_name"
                                        value="{{ old('github_repo_name', $project->github_repo_name) }}"
                                        class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
                                        placeholder="e.g. username/repo">
                                    <x-input-error :messages="$errors->get('github_repo_name')" class="mt-2" />
                                </div>
 
                                <!-- Category -->
                                <div class="col-span-1">
                                    <label for="category" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Project Category</label>
                                    <select id="category" name="category" required
                                        class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>
 
                                <!-- Status -->
                                <div class="col-span-1">
                                    <label for="status" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase tracking-wider">Project Status</label>
                                    <select id="status" name="status" required
                                        class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>
 
                            <div class="flex gap-4 pt-6 border-t border-slate-200 dark:border-slate-700 mt-6">
                                <a href="{{ route('admin.projects.show', $project) }}"
                                    class="w-1/2 block text-center border border-slate-250 dark:border-slate-700 text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-bold py-3 px-4 rounded-xl text-xs transition-colors no-underline">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="w-1/2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl text-xs shadow border-0 transition-colors">
                                    Update Project
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>