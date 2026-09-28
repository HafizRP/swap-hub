<section>
    <header class="mb-6">
        <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i class="bi bi-stars text-brand-600"></i>
            <span>Matriks Keahlian</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Kelola keahlian teknis dan tingkat kemahiran Anda agar mudah dipasangkan dengan proyek yang relevan.
        </p>
    </header>

    <!-- Current Skills List -->
    <div class="space-y-2 mb-6">
        <span class="block text-xs font-bold text-slate-700 dark:text-slate-300">Keahlian Terpasang</span>
        
        <div class="flex flex-wrap gap-2">
            @forelse($user->skills as $skill)
                <div class="inline-flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200">
                    <span>{{ $skill->name }}</span>
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                        {{ $skill->pivot->proficiency_level }}
                    </span>
                    <form method="post" action="{{ route('profile.skills.remove', $skill) }}" class="inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="text-slate-400 hover:text-rose-500 p-1 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 text-center text-xs text-slate-400">
                    Belum ada keahlian yang ditambahkan ke profil Anda.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Skill Form -->
    <form method="post" action="{{ route('profile.skills.add') }}"
          class="p-5 rounded-2xl bg-brand-50/50 dark:bg-brand-950/20 border border-brand-200/60 dark:border-brand-900/40 space-y-4">
        @csrf
        <h4 class="text-xs font-extrabold uppercase tracking-wider text-brand-700 dark:text-brand-300 flex items-center gap-1.5">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Keahlian Baru</span>
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="skill_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Pilih Keahlian <span class="text-rose-500">*</span>
                </label>
                <select id="skill_id" name="skill_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                    <option value="" disabled selected>Pilih salah satu...</option>
                    @foreach(\App\Models\Skill::all()->groupBy('category') as $category => $skillList)
                        <optgroup label="{{ $category }}">
                            @foreach($skillList as $skill)
                                <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="proficiency_level" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Tingkat Kemahiran <span class="text-rose-500">*</span>
                </label>
                <select id="proficiency_level" name="proficiency_level" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                    <option value="beginner">Beginner (Pemula)</option>
                    <option value="intermediate" selected>Intermediate (Menengah)</option>
                    <option value="advanced">Advanced (Mahir)</option>
                    <option value="expert">Expert (Ahli)</option>
                </select>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                Tambahkan Keahlian
            </button>
        </div>
    </form>
</section>
