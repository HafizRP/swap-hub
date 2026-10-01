@section('title', 'Ajukan Pertukaran Skill')
<x-app-layout>
    <div class="py-2 max-w-2xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('skills.swap.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 no-underline mb-2 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Direktori Swap</span>
                </a>
                <h1 class="text-2xl font-black text-slate-900 dark:text-slate-100">
                    Ajukan Pertukaran Skill Baru
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Tentukan keahlian yang dapat kamu ajarkan dan keahlian yang ingin kamu pelajari.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold">
                <p class="font-bold mb-1">Periksa kembali formulir:</p>
                <ul class="list-disc pl-5 space-y-0.5 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700/60 p-6 sm:p-8 shadow-sm">
            <form action="{{ route('skills.swap.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Offered Skill -->
                <div>
                    <label for="offered_skill_id" class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                        Skill yang Kamu Tawarkan (Keahlianmu) *
                    </label>
                    <select name="offered_skill_id" id="offered_skill_id" required
                            class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none">
                        <option value="">-- Pilih Skill yang Ditawarkan --</option>
                        @foreach($allSkills as $skill)
                            <option value="{{ $skill->id }}" {{ old('offered_skill_id') == $skill->id ? 'selected' : '' }}>
                                {{ $skill->name }} ({{ $skill->category ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1 mb-0">
                        Pilih skill yang paling kamu kuasai untuk diajarkan atau dibantu ke partner.
                    </p>
                </div>

                <!-- Requested Skill -->
                <div>
                    <label for="requested_skill_id" class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                        Skill yang Kamu Cari (Ingin Kamu Pelajari) *
                    </label>
                    <select name="requested_skill_id" id="requested_skill_id" required
                            class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none">
                        <option value="">-- Pilih Skill yang Dicari --</option>
                        @foreach($allSkills as $skill)
                            <option value="{{ $skill->id }}" {{ old('requested_skill_id') == $skill->id ? 'selected' : '' }}>
                                {{ $skill->name }} ({{ $skill->category ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Points Offered -->
                <div>
                    <label for="points_offered" class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                        Poin Reputasi yang Ditawarkan
                    </label>
                    <input type="number" name="points_offered" id="points_offered" min="1" max="100" value="{{ old('points_offered', 10) }}"
                           class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none">
                    <p class="text-[11px] text-slate-400 mt-1 mb-0">
                        Default 10 poin. Poin ini akan diberikan kepada partner setelah sesi swap selesai.
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                        Deskripsi Pertukaran & Topik yang Ingin Dibahas *
                    </label>
                    <textarea name="description" id="description" rows="4" required minlength="10" maxlength="1000"
                              class="w-full bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl p-3.5 text-xs text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none"
                              placeholder="Jelaskan apa yang bisa kamu ajarkan dan tujuan belajarmu, misalnya: 'Saya bisa mengajari React Hooks dan Tailwind, sedang butuh bimbingan Docker & CI/CD deployment.'">{{ old('description') }}</textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <a href="{{ route('skills.swap.index') }}" 
                       class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 no-underline">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
                        Publikasikan Permintaan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
