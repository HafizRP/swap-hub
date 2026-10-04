@section('title', 'Ajukan Pertukaran Skill')
<x-app-layout>
    <div class="py-2 max-w-2xl mx-auto space-y-4">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('skills.swap.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-stone-500 hover:text-teal-600 no-underline mb-2 transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Direktori Swap</span>
                </a>
                <h1 class="text-2xl font-black text-stone-900 dark:text-stone-100 mb-0">
                    Ajukan Pertukaran Skill Baru
                </h1>
                <p class="text-xs text-stone-500 dark:text-stone-400 mt-1 mb-0">
                    Tentukan keahlian yang dapat kamu ajarkan dan keahlian yang ingin kamu pelajari.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 text-xs font-semibold">
                <p class="font-bold mb-1">Periksa kembali formulir:</p>
                <ul class="list-disc pl-5 space-y-0.5 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-[#141414] rounded-xl border border-stone-200/80 dark:border-stone-800 p-4 sm:p-5 shadow-card">
            <form action="{{ route('skills.swap.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Offered Skill -->
                <div>
                    <label for="offered_skill_id" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                        Skill yang Kamu Tawarkan (Keahlianmu) *
                    </label>
                    <select name="offered_skill_id" id="offered_skill_id" required
                            class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                        <option value="">-- Pilih Skill yang Ditawarkan --</option>
                        @foreach($allSkills as $skill)
                            <option value="{{ $skill->id }}" {{ old('offered_skill_id') == $skill->id ? 'selected' : '' }}>
                                {{ $skill->name }} ({{ $skill->category ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-stone-400 mt-1 mb-0">
                        Pilih skill yang paling kamu kuasai untuk diajarkan atau dibantu ke partner.
                    </p>
                </div>

                <!-- Requested Skill -->
                <div>
                    <label for="requested_skill_id" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                        Skill yang Kamu Cari (Ingin Kamu Pelajari) *
                    </label>
                    <select name="requested_skill_id" id="requested_skill_id" required
                            class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
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
                    <label for="points_offered" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                        Poin Reputasi yang Ditawarkan
                    </label>
                    <input type="number" name="points_offered" id="points_offered" min="1" max="100" value="{{ old('points_offered', 10) }}"
                           class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    <p class="text-[11px] text-stone-400 mt-1 mb-0">
                        Default 10 poin. Poin ini akan diberikan kepada partner setelah sesi swap selesai.
                    </p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">
                        Deskripsi Pertukaran & Topik yang Ingin Dibahas *
                    </label>
                    <textarea name="description" id="description" rows="4" required minlength="10" maxlength="1000"
                              class="w-full bg-stone-50 dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-xl p-3.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"
                              placeholder="Jelaskan apa yang bisa kamu ajarkan dan tujuan belajarmu, misalnya: 'Saya bisa mengajari React Hooks dan Tailwind, sedang butuh bimbingan Docker & CI/CD deployment.'">{{ old('description') }}</textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <a href="{{ route('skills.swap.index') }}" 
                       class="px-4 py-2 rounded-lg border border-stone-200 dark:border-stone-700 text-xs font-medium text-stone-600 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800 no-underline transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium shadow-sm transition-all border-0 cursor-pointer">
                        Publikasikan Permintaan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
