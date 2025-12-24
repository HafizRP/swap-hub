@section('title', 'Edit Project')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="p-1" style="background: linear-gradient(to right, #4f46e5, #818cf8);"></div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('projects.update', $project) }}">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Project
                                    Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                                    required autofocus
                                    class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                    placeholder="e.g. Building a Sustainable Campus Mobile App"
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Description</label>
                                <textarea id="description" name="description" rows="5" required
                                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                    placeholder="Describe what you want to achieve, the stack you're using, and who you're looking for..."
                                    style="background-color: rgba(255,255,255,0.05) !important;">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="row g-4 mb-5">
                                <!-- GitHub Repo URL -->
                                <div class="col-md-6">
                                    <label for="github_repo_url"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">GitHub
                                        Repository</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-0 text-secondary rounded-start-4"
                                            style="background-color: rgba(255,255,255,0.05) !important;">
                                            <svg style="width: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                            </svg>
                                        </span>
                                        <input type="url" id="github_repo_url" name="github_repo_url"
                                            value="{{ old('github_repo_url', $project->github_repo_url) }}"
                                            class="form-control form-control-lg bg-dark border-0 rounded-end-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                            placeholder="https://github.com/user/repo"
                                            style="background-color: rgba(255,255,255,0.05) !important;">
                                    </div>
                                    <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />

                                    @if($project->github_webhook_status)
                                        <div class="mt-2">
                                            <span
                                                class="badge {{ $project->github_webhook_status === 'active' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $project->github_webhook_status === 'active' ? 'success' : 'warning' }} small">
                                                Webhook: {{ ucfirst($project->github_webhook_status) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- GitHub Repo Name -->
                                <div class="col-md-6">
                                    <label for="github_repo_name"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Repository
                                        Name</label>
                                    <input type="text" id="github_repo_name" name="github_repo_name"
                                        value="{{ old('github_repo_name', $project->github_repo_name) }}"
                                        class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                        placeholder="e.g. username/repo"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                    <x-input-error :messages="$errors->get('github_repo_name')" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label for="category"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Project
                                        Category</label>
                                    <select id="category" name="category" required
                                        class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design
                                        </option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>
                                            Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Project
                                        Status</label>
                                    <select id="status" name="status" required
                                        class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="archived" {{ old('status', $project->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label for="start_date"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Start
                                        Date</label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                        class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <label for="end_date"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">End
                                        Date</label>
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                        class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </div>

                            <div class="pt-4 border-top border-white border-opacity-10 mt-5">
                                <div class="d-flex gap-3">
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-outline-secondary rounded-4 py-3 fw-black fs-6 flex-grow-1">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                        class="btn btn-primary rounded-4 py-3 fw-black fs-6 shadow-lg transition flex-grow-1">
                                        Update Project
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>