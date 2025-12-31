@section('title', 'Create Project')
<x-app-layout>
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">Create a New Project</h2>
                <p class="text-secondary mb-0">Fill in the details below to start collaborating.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('projects.index') }}" class="text-secondary text-decoration-none fw-bold small">Cancel</a>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                         class="rounded-circle" width="32" height="32">
                    <div class="d-none d-md-block text-end">
                        <p class="mb-0 fw-bold small lh-1">{{ auth()->user()->name }}</p>
                        <span class="text-secondary" style="font-size: 10px;">{{ auth()->user()->role ?? 'Student' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--bs-body-bg);">
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('projects.store') }}" id="createProjectForm">
                            @csrf
                            
                            <!-- PROJECT BASICS -->
                            <h6 class="fw-bold text-uppercase text-secondary mb-4" style="font-size: 11px; letter-spacing: 1px;">Project Basics</h6>
                            <hr class="border-secondary opacity-10 mb-4">

                            <!-- Project Name -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold small">Project Name <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                    class="form-control bg-secondary-subtle border-0 rounded-3 px-3 py-3"
                                    placeholder="e.g., AI-Powered Study Assistant">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <!-- Category (Mapped from existing logic, but styled) -->
                            <div class="mb-4">
                                <label for="category" class="form-label fw-bold small">Category <span class="text-danger">*</span></label>
                                <select id="category" name="category" required class="form-select bg-secondary-subtle border-0 rounded-3 px-3 py-3">
                                    <option value="Development" {{ old('category') == 'Development' ? 'selected' : '' }}>Development</option>
                                    <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design</option>
                                    <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-4 position-relative">
                                <label for="description" class="form-label fw-bold small">Description <span class="text-danger">*</span></label>
                                <textarea id="description" name="description" rows="6" required
                                    class="form-control bg-secondary-subtle border-0 rounded-3 px-3 py-3"
                                    placeholder="Describe the problem you are solving, the tech stack, and what you hope to achieve...">{{ old('description') }}</textarea>
                                <div class="text-end mt-1 text-secondary" style="font-size: 10px;">0 / 500 characters</div>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- TEAM & SCOPE -->
                            <h6 class="fw-bold text-uppercase text-secondary mb-4 mt-5" style="font-size: 11px; letter-spacing: 1px;">Team & Scope</h6>
                            <hr class="border-secondary opacity-10 mb-4">

                            <!-- Skills Needed (Visual - Appended to Description) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Skills Needed</label>
                                <div class="bg-secondary-subtle rounded-3 px-3 py-2 d-flex align-items-center flex-wrap gap-2 focus-within-ring">
                                    <div id="skillsContainer" class="d-flex flex-wrap gap-2">
                                        <!-- Tags will appear here -->
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 d-flex align-items-center gap-1" style="font-size: 11px;">
                                            Example: React <i class="bi bi-x cursor-pointer" onclick="this.parentElement.remove()"></i>
                                        </span>
                                    </div>
                                    <input type="text" id="skillInput" class="bg-transparent border-0 small flex-grow-1 p-1" placeholder="Type a skill & press Enter..." style="outline: none; min-width: 150px;">
                                </div>
                                <input type="hidden" name="skills_hidden" id="skillsHidden">
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Team Size (Visual - Appended to Description) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Team Size</label>
                                    <select id="teamSize" class="form-select bg-secondary-subtle border-0 rounded-3 px-3 py-3">
                                        <option value="3-4 Members">3-4 Members</option>
                                        <option value="5-10 Members">5-10 Members</option>
                                        <option value="10+ Members">10+ Members</option>
                                    </select>
                                </div>
                                
                                <!-- Estimated Timeline (Map to end_date) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Estimated Timeline</label>
                                    <select id="timeline" class="form-select bg-secondary-subtle border-0 rounded-3 px-3 py-3">
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
                            <h6 class="fw-bold text-uppercase text-secondary mb-4 mt-5" style="font-size: 11px; letter-spacing: 1px;">Visibility</h6>
                            <hr class="border-secondary opacity-10 mb-4">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check p-0 m-0 w-100 h-100">
                                        <input type="radio" class="btn-check" name="visibility" id="public" value="public" checked>
                                        <label class="btn btn-outline-secondary border-0 bg-secondary-subtle text-start w-100 h-100 p-3 rounded-3 d-flex align-items-start gap-3" for="public">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 text-primary">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 fs-6">Public Project</h6>
                                                <p class="mb-0 small text-secondary lh-sm" style="font-size: 11px;">Visible to all students on Swap Hub. Best for finding new teammates.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check p-0 m-0 w-100 h-100">
                                        <input type="radio" class="btn-check" name="visibility" id="invite" value="invite">
                                        <label class="btn btn-outline-secondary border-0 bg-secondary-subtle text-start w-100 h-100 p-3 rounded-3 d-flex align-items-start gap-3" for="invite">
                                            <div class="bg-secondary bg-opacity-10 rounded-circle p-2 text-secondary">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 fs-6">Invite Only</h6>
                                                <p class="mb-0 small text-secondary lh-sm" style="font-size: 11px;">Only people you invite can see and join this project.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- GitHub Integration (Collapsed / Optional) -->
                            <div class="mt-5">
                                <a class="text-decoration-none text-secondary small fw-bold d-flex align-items-center gap-2" data-bs-toggle="collapse" href="#githubSection" role="button" aria-expanded="false">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
                                    GitHub Integration Settings
                                    <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16" class="ms-1"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>
                                </a>
                                <div class="collapse mt-3" id="githubSection">
                                     <!-- Repo Selector -->
                                    <label for="github_repo_url" class="form-label small fw-bold">Link Repository</label>
                                    @if(count($repositories) > 0)
                                        <select id="github_repo_url" name="github_repo_url"
                                            onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                            class="form-select bg-secondary-subtle border-0 rounded-3 px-3 py-3">
                                            <option value="">Select a repository (Optional)</option>
                                            @foreach($repositories as $repo)
                                                <option value="{{ $repo['html_url'] }}">{{ $repo['full_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="github_repo_name" name="github_repo_name" value="{{ old('github_repo_name') }}">
                                        
                                        @if(auth()->user()->github_token)
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="setup_webhook" name="setup_webhook" value="1" checked>
                                            <label class="form-check-label small" for="setup_webhook">
                                                Auto-setup Webhook (Recommended)
                                            </label>
                                        </div>
                                        @endif
                                    @else
                                        <div class="alert alert-secondary border-0 small">
                                            No repositories found. <a href="{{ route('auth.github') }}" class="fw-bold text-decoration-underline">Link GitHub Account</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <hr class="border-secondary opacity-10 my-5">

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="#" class="text-secondary fw-bold small text-decoration-none">Save as Draft</a>
                                <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold d-flex align-items-center gap-2">
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
                tag.className = 'badge bg-primary-subtle text-primary rounded-pill px-2 py-1 d-flex align-items-center gap-1 skill-tag';
                tag.style.fontSize = '11px';
                tag.innerHTML = `${text} <span class="cursor-pointer ms-1 d-flex align-items-center" onclick="this.parentElement.remove()"><svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></span>`;
                // tag.querySelector('i').onclick = ... (Handled inline now)
                skillsContainer.appendChild(tag);
            }

            // 2. Timeline Logic (Set End Date)
            const timelineSelect = document.getElementById('timeline');
            const endDateInput = document.getElementById('end_date');
            
            function updateEndDate() {
                const months = parseInt(timelineSelect.value);
                const date = new Date();
                date.setMonth(date.getMonth() + months);
                // Format YYYY-MM-DD
                const dateString = date.toISOString().split('T')[0];
                endDateInput.value = dateString;
            }
            timelineSelect.addEventListener('change', updateEndDate);
            updateEndDate(); // Init

            // 3. Form Submission (Append Data to Description)
            const form = document.getElementById('createProjectForm');
            form.addEventListener('submit', function(e) {
                // Collect Skills
                const skills = Array.from(skillsContainer.querySelectorAll('.skill-tag')).map(el => el.innerText.trim());
                
                // Collect Team Size & Visibility
                const teamSize = document.getElementById('teamSize').value;
                const visibility = document.querySelector('input[name="visibility"]:checked').value;
                const visibilityLabel = visibility === 'public' ? 'Public' : 'Invite Only';

                // Append to Description
                const descInput = document.getElementById('description');
                let appendText = `\n\n---\n**Project Details:**`;
                if (skills.length) appendText += `\n- **Skills Needed:** ${skills.join(', ')}`;
                appendText += `\n- **Team Size:** ${teamSize}`;
                appendText += `\n- **Visibility:** ${visibilityLabel}`;
                
                descInput.value += appendText;
            });
        });
    </script>

    <style>
        .bg-secondary-subtle {
            background-color: #f3f4f6 !important; /* Tailwind gray-100 */
        }
        [data-bs-theme="dark"] .bg-secondary-subtle {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border: 1px solid var(--bs-primary);
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</x-app-layout>