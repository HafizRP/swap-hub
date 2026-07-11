<section>
    <header class="mb-6">
        <h4 class="text-xl font-black text-slate-800 dark:text-slate-100">
            {{ __('Skill Matrix') }}
        </h4>
 
        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">
            {{ __("Optimize the technical capabilities you provide to the ecosystem.") }}
        </p>
    </header>
 
    <!-- Current Skills -->
    <div class="flex flex-col gap-3 mt-4">
        @forelse($user->skills as $skill)
            <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 transition hover:bg-slate-50 dark:hover:bg-slate-705 bg-slate-50 dark:bg-slate-700/20 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-sm text-slate-850 dark:text-slate-100">{{ $skill->name }}</span>
                    <span class="bg-indigo-500/10 text-indigo-650 dark:text-indigo-400 border border-indigo-500/20 rounded px-2 py-0.5 text-[9px] font-black uppercase">
                        {{ $skill->pivot->proficiency_level }}
                    </span>
                </div>
                <form method="post" action="{{ route('profile.skills.remove', $skill) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="text-slate-400 hover:text-red-500 p-0 transition-all hover:scale-105 duration-200 bg-transparent border-0">
                        <svg style="width: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/20">
                <p class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-0">
                    {{ __('No skills locked in.') }}</p>
            </div>
        @endforelse
    </div>
 
    <!-- Add Skill Form -->
    <form method="post" action="{{ route('profile.skills.add') }}"
        class="mt-6 p-5 md:p-6 rounded-2xl border border-indigo-500/15 shadow-sm bg-indigo-500/5">
        @csrf
        <h6 class="text-xs font-black text-indigo-650 dark:text-indigo-400 text-uppercase tracking-wider mb-4">{{ __('Add Skill Component') }}</h6>
 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-1">
                <label for="skill_id" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Select Capability') }}</label>
                <select id="skill_id" name="skill_id" required
                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                    <option value="" disabled selected>Choose a skill...</option>
                    @foreach(\App\Models\Skill::all()->groupBy('category') as $category => $skillList)
                        <optgroup label="{{ $category }}">
                            @foreach($skillList as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
 
            <div class="col-span-1">
                <label for="proficiency_level" class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">{{ __('Proficiency Rank') }}</label>
                <select id="proficiency_level" name="proficiency_level" required
                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-3.5 py-3 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500 transition-colors">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
        </div>
 
        <div class="mt-5">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-full text-xs shadow-lg transition-colors border-0">
                {{ __('Integrate Skill') }}
            </button>
        </div>
    </form>
</section>