@section('title', 'Edit Project')
<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="mb-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2">
                            <li class="breadcrumb-item"><a href="{{ route('projects.show', $project) }}"
                                    class="text-decoration-none text-secondary small fw-bold">Back to Project</a></li>
                            <li class="breadcrumb-item active small fw-bold" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                    <h2 class="h3 fw-black text-body mb-0">{{ __('Edit Project Details') }}</h2>
                </div>

                <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                    <div class="p-1" style="background: linear-gradient(to right, #4f46e5, #818cf8);"></div>
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('projects.update', $project) }}">
                            @csrf
                            @method('PUT')

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title"
                                    class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">Project
                                    Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}"
                                    required
                                    class="form-control form-control-lg rounded-3 px-4 py-3 fw-bold border-secondary-subtle focus-ring"
                                    placeholder="e.g. Building a Sustainable Campus Mobile App">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description"
                                    class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">Description</label>
                                <textarea id="description" name="description" rows="6" required
                                    class="form-control rounded-3 px-4 py-3 border-secondary-subtle focus-ring"
                                    placeholder="Describe your project...">{{ old('description', $project->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="row g-4 mb-5">
                                <!-- GitHub Integration -->
                                <div class="col-12">
                                    <div class="bg-body-tertiary rounded-4 p-4 border border-secondary-subtle">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="bi bi-github fs-5"></i>
                                            <h6 class="fw-bold mb-0">GitHub Integration</h6>
                                        </div>

                                        @if(count($repositories) > 0)
                                            <label for="github_repo_url"
                                                class="form-label small fw-bold text-secondary">Select Repository</label>
                                            <select id="github_repo_url" name="github_repo_url"
                                                onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                                class="form-select form-select-lg border-secondary-subtle mb-3">
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
                                                <div
                                                    class="d-flex justify-content-between align-items-center bg-body p-3 rounded-3 border border-secondary-subtle">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="small fw-bold text-secondary">Webhook Status:</span>
                                                        <span
                                                            class="badge {{ $project->github_webhook_status === 'active' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $project->github_webhook_status === 'active' ? 'success' : 'warning' }} border border-{{ $project->github_webhook_status === 'active' ? 'success' : 'warning' }} border-opacity-25">
                                                            {{ ucfirst($project->github_webhook_status ?? 'Not Setup') }}
                                                        </span>
                                                    </div>
                                                    <button form="reconnectWebhookForm" type="submit"
                                                        class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="tooltip"
                                                        title="Reconnect GitHub Webhook">
                                                        <i class="bi bi-arrow-repeat me-1"></i>Reconnect Loop
                                                    </button>
                                                </div>
                                            @endif

                                        @else
                                            <div class="alert alert-warning border-0 small mb-0">
                                                <i class="bi bi-exclamation-circle me-1"></i> No repositories found.
                                                <a href="{{ route('profile.edit') }}"
                                                    class="text-decoration-underline fw-bold">Connect GitHub Account</a>
                                            </div>
                                        @endif
                                        <x-input-error :messages="$errors->get('github_repo_url')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <label for="category"
                                        class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">Category</label>
                                    <select id="category" name="category" required
                                        class="form-select form-select-lg border-secondary-subtle">
                                        <option value="Development" {{ old('category', $project->category) == 'Development' ? 'selected' : '' }}>Development</option>
                                        <option value="Design" {{ old('category', $project->category) == 'Design' ? 'selected' : '' }}>Design</option>
                                        <option value="Marketing" {{ old('category', $project->category) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status"
                                        class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">Status</label>
                                    <select id="status" name="status" required
                                        class="form-select form-select-lg border-secondary-subtle">
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
                                        class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">Start
                                        Date</label>
                                    <input type="date" id="start_date" name="start_date"
                                        value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                        class="form-control form-control-lg border-secondary-subtle">
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <label for="end_date"
                                        class="form-label small fw-bold text-uppercase text-secondary tracking-wide mb-2">End
                                        Date</label>
                                    <input type="date" id="end_date" name="end_date"
                                        value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                        class="form-control form-control-lg border-secondary-subtle">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </div>

                            <div class="d-flex gap-3 pt-4 border-top border-secondary-subtle">
                                <a href="{{ route('projects.show', $project) }}"
                                    class="btn btn-outline-secondary rounded-pill py-3 px-5 fw-bold flex-grow-1">
                                    Cancel
                                </a>
                                <button type="submit"
                                    class="btn btn-primary rounded-pill py-3 px-5 fw-bold shadow-sm transition flex-grow-1">
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
        class="d-none">
        @csrf
    </form>
</x-app-layout>