<section>
    <header class="mb-6">
        <h3 class="text-base font-black text-stone-900 dark:text-stone-100 flex items-center gap-2 mb-0">
            <i class="bi bi-tools text-amber-500"></i>
            <span>Keahlian & Kemampuan Teknis</span>
        </h3>
        <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 mb-0">
            Tambahkan keahlian teknis dan tingkat kemahiran untuk meningkatkan kecocokan proyek.
        </p>
    </header>

    <!-- Current Skills -->
    <div class="space-y-2 mt-4">
        @forelse($user->skills as $skill)
            <div class="flex items-center justify-between p-3 rounded-xl border border-stone-200 dark:border-stone-800 hover:bg-stone-50 dark:hover:bg-stone-800/40 bg-stone-50/50 dark:bg-[#141414] transition-colors" wire:key="skill-{{ $skill->id }}">
                <div class="flex items-center gap-2.5">
                    <span class="font-bold text-xs text-stone-900 dark:text-stone-100">{{ $skill->name }}</span>
                    <span class="bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/60 rounded-md px-2 py-0.5 text-[9px] font-bold uppercase">
                        {{ $skill->pivot->proficiency_level }}
                    </span>
                </div>
                <form method="post" action="{{ route('profile.skills.remove', $skill) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="text-stone-400 hover:text-red-500 p-1 transition-colors bg-transparent border-0 cursor-pointer" title="Hapus skill">
                        <i class="bi bi-trash text-xs"></i>
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-6 rounded-lg border border-dashed border-stone-200 dark:border-stone-800 bg-stone-50/50 dark:bg-[#141414]">
                <p class="text-xs text-stone-400 dark:text-stone-500 font-medium mb-0">
                    Belum ada keahlian yang terdaftar. Tambahkan skill pertama Anda di bawah ini.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Add Skill Form -->
    <form method="post" action="{{ route('profile.skills.add') }}"
        class="mt-6 p-4 rounded-xl border border-teal-200/60 dark:border-teal-800/50 bg-teal-50/30 dark:bg-teal-950/20">
        @csrf
        <h4 class="text-xs font-bold text-teal-700 dark:text-teal-300 uppercase tracking-wider mb-4 flex items-center gap-1.5">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Keahlian Baru</span>
        </h4>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="skill_id" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-1.5">Pilih Keahlian</label>
                <select id="skill_id" name="skill_id" required
                    class="w-full bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
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
                <label for="proficiency_level" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-1.5">Tingkat Kemahiran</label>
                <select id="proficiency_level" name="proficiency_level" required
                    class="w-full bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                    <option value="beginner">Beginner (Pemula)</option>
                    <option value="intermediate">Intermediate (Menengah)</option>
                    <option value="advanced">Advanced (Mahir)</option>
                    <option value="expert">Expert (Ahli)</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg text-xs shadow-sm transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer">
                Tambahkan ke Profil
            </button>
        </div>
    </form>
</section>
