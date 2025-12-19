<section>
    <header class="mb-4">
        <h4 class="fw-black text-white">
            {{ __('Skill Matrix') }}
        </h4>

        <p class="small text-secondary fw-bold text-uppercase tracking-widest opacity-50">
            {{ __("Optimize the technical capabilities you provide to the ecosystem.") }}
        </p>
    </header>

    <!-- Current Skills -->
    <div class="d-flex flex-column gap-3 mt-4">
        @forelse($user->skills as $skill)
            <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border border-white border-opacity-5 transition hover-bg-light shadow-sm"
                style="background-color: rgba(255, 255, 255, 0.05) !important;">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold text-white small">{{ $skill->name }}</span>
                    <span class="badge text-primary fw-black text-uppercase px-2 py-1"
                        style="font-size: 8px; border: 1px solid rgba(99, 102, 241, 0.2); background-color: rgba(99, 102, 241, 0.1) !important;">{{ $skill->pivot->proficiency_level }}</span>
                </div>
                <form method="post" action="{{ route('profile.skills.remove', $skill) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link text-secondary p-0 opacity-50 hover-danger transition">
                        <svg style="width: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-4 rounded-4 border border-white border-opacity-5"
                style="background-color: rgba(255, 255, 255, 0.05) !important;">
                <p class="small text-secondary fw-bold text-uppercase tracking-widest mb-0 opacity-50">
                    {{ __('No skills locked in.') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Add Skill Form -->
    <form method="post" action="{{ route('profile.skills.add') }}"
        class="mt-5 p-4 p-md-5 rounded-5 border border-primary border-opacity-10 shadow-sm"
        style="background-color: rgba(99, 102, 241, 0.1) !important;">
        @csrf
        <h6 class="small fw-black text-primary text-uppercase tracking-widest mb-4">{{ __('Add Skill Component') }}</h6>

        <div class="row g-4">
            <div class="col-md-6">
                <label for="skill_id"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest opacity-75">{{ __('Select Capability') }}</label>
                <select id="skill_id" name="skill_id" required
                    class="form-select bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                    <option value="" disabled selected>Choose a skill...</option>
                    @foreach(\App\Models\Skill::all()->groupBy('category') as $category => $skillList)
                        <optgroup label="{{ $category }}" class="bg-dark">
                            @foreach($skillList as $skill)
                                <option value="{{ $skill->id }}" class="bg-dark text-white">{{ $skill->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="proficiency_level"
                    class="form-label small fw-black text-secondary text-uppercase tracking-widest opacity-75">{{ __('Proficiency Rank') }}</label>
                <select id="proficiency_level" name="proficiency_level" required
                    class="form-select bg-dark border-0 rounded-4 px-4 py-3 text-white focus:ring-2 focus:ring-primary shadow-inner"
                    style="background-color: rgba(255,255,255,0.05) !important;">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                {{ __('Integrate Skill') }}
            </button>
        </div>
    </form>
</section>

<style>
    .hover-danger:hover {
        color: #ef4444 !important;
        opacity: 1 !important;
        transform: scale(1.1);
    }

    .rounded-5 {
        border-radius: 2rem !important;
    }
</style>