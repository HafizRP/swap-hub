@section('title', 'Edit Project')
<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-teal-500 to-teal-400"></div>
                    <div class="p-4 md:p-6">
                        <form method="POST" action="{{ route('admin.projects.update', $project) }}">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Project Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                                    required autofocus
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    placeholder="e.g. Building a Sustainable Campus Mobile App">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="mb-5">
                                <label for="description" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Description</label>
                                <textarea id="description" name="description" rows="5" required
                                    class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-3 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                    placeholder="Describe what you want to achieve, the stack you're using, and who you're looking for...">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- GitHub Repo URL -->
                                <div class="col-span-1">
                                    <label for="github_repo_url" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">GitHub Repository</label>
                                    <div class="flex rounded-xl overflow-hidden border border-stone-200 dark:border-stone-700">
                                        <span class="inline-flex items-center px-3 bg-stone-100 dark:bg-stone-800 border-r border-stone-200 dark:border-stone-700 text-stone-400 text-sm">
                                            <i class="bi bi-github"></i>
                                        </span>
                                        <input type="url" id="github_repo_url" name="github_repo_url"
                                            value="{{ old('github_repo_url', $project->github_repo_url) }}"
                                            class="flex-1 w-full bg-stone-50 dark:bg-[#1a1917] border-0 px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-colors"
                                            placeholder="https://github.com/user/repo">
                                    </div>
                                    <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                                </div>

                                <!-- GitHub Repo Name -->
                                <div class="col-span-1">
                                    <label for="github_repo_name" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Repository Name</label>
                                    <input type="text" id="github_repo_name" name="github_repo_name"
                                        value="{{ old('github_repo_name', $project->github_repo_name) }}"
                                        class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors"
                                        placeholder="e.g. username/repo">
                                    <x-input-error :messages="$errors->get('github_repo_name')" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div class="col-span-1">
                                    <label for="category" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Project Category</label>
                                    <select id="category" name="category" required
                                        class="w-full bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="col-span-1">
                                    <label for="status" class="block font-bold text-stone-500 dark:text-stone-400 text-xs mb-2 uppercase tracking-wider">Project Status</label>
                                    <select id="status" name="status" required
                                        class="w-full bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-colors">
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex gap-4 pt-6 border-t border-stone-200 dark:border-stone-800 mt-6">
                                <a href="{{ route('admin.projects.show', $project) }}"
                                    class="w-1/2 block text-center border border-stone-200 dark:border-stone-700 text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800 font-medium py-2 px-3 rounded-lg text-xs transition-colors no-underline">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="w-1/2 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-3 rounded-lg text-xs shadow border-0 transition-colors cursor-pointer">
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
