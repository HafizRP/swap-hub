<section>
    <header class="mb-6">
        <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
            <i class="bi bi-tools text-amber-500"></i>
            <span>Keahlian & Kemampuan Teknis</span>
        </h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Tambahkan keahlian teknis dan tingkat kemahiran untuk meningkatkan kecocokan proyek.
        </p>
    </header>
 
    <!-- Current Skills -->
    <div class="space-y-2 mt-4">
        @forelse($user->skills as $skill)
            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30 bg-slate-50/50 dark:bg-slate-800 transition-colors">
                <div class="flex items-center gap-2.5">
                    <span class="font-bold text-xs text-slate-900 dark:text-slate-100">{{ $skill->name }}</span>
                    <span class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60 rounded-md px-2 py-0.5 text-[9px] font-bold uppercase">
                        {{ $skill->pivot->proficiency_level }}
                    </span>
                </div>
                <form method="post" action="{{ route('profile.skills.remove', $skill) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="text-slate-400 hover:text-red-500 p-1 transition-colors bg-transparent border-0 cursor-pointer" title="Hapus skill">
                        <i class="bi bi-trash text-xs"></i>
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-6 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40">
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium mb-0">
                    Belum ada keahlian yang terdaftar. Tambahkan skill pertama Anda di bawah ini.
                </p>
            </div>
        @endforelse
    </div>
 
    <!-- Add Skill Form -->
    <form method="post" action="{{ route('profile.skills.add') }}"
        class="mt-6 p-4 sm:p-5 rounded-2xl border border-indigo-200/60 dark:border-indigo-800/50 bg-indigo-50/30 dark:bg-indigo-950/20">
        @csrf
        <h4 class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-wider mb-4 flex items-center gap-1.5">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Keahlian Baru</span>
        </h4>
 
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="skill_id" class="block font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider mb-1.5">Pilih Keahlian</label>
                <select id="skill_id" name="skill_id" required
                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                    <option value="" disabled selected>Pilih skill yang Anda kuasai...</option>
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
                <label for="proficiency_level" class="block font-bold text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider mb-1.5">Tingkat Kemahiran</label>
                <select id="proficiency_level" name="proficiency_level" required
                    class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                    <option value="beginner">Beginner (Pemula)</option>
                    <option value="intermediate">Intermediate (Menengah)</option>
                    <option value="advanced">Advanced (Mahir)</option>
                    <option value="expert">Expert (Ahli)</option>
                </select>
            </div>
        </div>
 
        <div class="mt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-sm transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                Tambahkan ke Profil
            </button>
        </div>
    </form>
</section>