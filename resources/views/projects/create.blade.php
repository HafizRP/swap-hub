@section('title', 'Create Project')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Start New Initiative') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="p-1" style="background: linear-gradient(to right, #4f46e5, #818cf8);"></div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Project
                                    Mission Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                    class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                    placeholder="e.g. Building a Sustainable Campus Mobile App"
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description"
                                    class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">The
                                    Grand Vision (Description)</label>
                                <textarea id="description" name="description" rows="5" required
                                    class="form-control bg-dark border-0 rounded-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                    placeholder="Describe what you want to achieve, the stack you're using, and who you're looking for..."
                                    style="background-color: rgba(255,255,255,0.05) !important;">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="row g-4 mb-5">
                                <!-- GitHub Repo Selector / URL -->
                                <div class="col-md-6">
                                    <label for="github_repo_url"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">GitHub
                                        Repository</label>
                                    @if(count($repositories) > 0)
                                        <select id="github_repo_url" name="github_repo_url"
                                            onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                            class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                            style="background-color: rgba(255,255,255,0.05) !important;">
                                            <option value="">Select a repository (Optional)</option>
                                            @foreach($repositories as $repo)
                                                <option value="{{ $repo['html_url'] }}" {{ old('github_repo_url') == $repo['html_url'] ? 'selected' : '' }}>
                                                    {{ $repo['full_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="github_repo_name" name="github_repo_name"
                                            value="{{ old('github_repo_name') }}">
                                    @else
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-0 text-secondary rounded-start-4"
                                                style="background-color: rgba(255,255,255,0.05) !important;">
                                                <svg style="width: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12" />
                                                </svg>
                                            </span>
                                            <input type="url" id="github_repo_url" name="github_repo_url"
                                                value="{{ old('github_repo_url') }}"
                                                class="form-control form-control-lg bg-dark border-0 rounded-end-4 px-4 py-3 text-white placeholder-secondary focus:ring-2 focus:ring-primary transition shadow-inner"
                                                placeholder="https://github.com/user/repo"
                                                style="background-color: rgba(255,255,255,0.05) !important;">
                                        </div>
                                        @if(!auth()->user()->github_token)
                                            <p class="mt-2 mb-0"
                                                style="font-size: 10px; font-weight: 900; letter-spacing: 0.05em; text-transform: uppercase;">
                                                <a href="{{ route('auth.github') }}"
                                                    class="text-primary text-decoration-none">Link GitHub</a> to select from
                                                your repositories
                                            </p>
                                        @endif
                                    @endif
                                    <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label for="category"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Project
                                        Category</label>
                                    <select id="category" name="category" required
                                        class="form-select form-select-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                        <option value="Development" {{ old('category') == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design
                                        </option>
                                        <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>
                                            Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label for="start_date"
                                        class="form-label small fw-black text-uppercase text-secondary tracking-widest mb-2">Estimated
                                        Launch</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                        class="form-control form-control-lg bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary transition shadow-inner"
                                        style="background-color: rgba(255,255,255,0.05) !important;">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                            </div>

                            <div class="pt-4 border-top border-white border-opacity-10 mt-5">
                                <button type="submit"
                                    class="btn btn-primary w-100 rounded-4 py-3 fw-black fs-5 shadow-lg transition">
                                    Launch Project
                                </button>
                                <p class="text-center text-secondary small mt-4 mb-0 fw-bold opacity-50">By launching,
                                    you agree to lead this project with transparency and provide validation for all
                                    contributors.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>