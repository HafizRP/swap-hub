@section('title', 'Buat Proyek Baru')
<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-brand-600 mb-1">
                    <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                </a>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Inisiasi Proyek Baru
                </h1>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                Squad Builder
            </span>
        </div>

        <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-6">
            <form method="POST" action="{{ route('projects.store') }}" class="space-y-6" id="createProjectForm">
                @csrf

                <!-- Section: Informasi Utama -->
                <div class="space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                        1. Informasi Utama Proyek
                    </h3>

                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Judul Proyek <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                               placeholder="Contoh: EduMatch - AI Study Partner Platform"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="Development" {{ old('category') == 'Development' ? 'selected' : '' }}>Development</option>
                                <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Research" {{ old('category') == 'Research' ? 'selected' : '' }}>Research</option>
                                <option value="Mobile" {{ old('category') == 'Mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="AI & Data" {{ old('category') == 'AI & Data' ? 'selected' : '' }}>AI & Data</option>
                            </select>
                        </div>

                        <div>
                            <label for="timeline" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Estimasi Durasi
                            </label>
                            <select id="timeline"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="1">1 Bulan</option>
                                <option value="3" selected>3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">1 Tahun</option>
                            </select>
                            <input type="hidden" name="end_date" id="end_date">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Deskripsi & Ruang Lingkup <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required
                                  placeholder="Jelaskan tujuan proyek, masalah yang ingin diselesaikan, stack teknologi, dan peran yang dicari..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-brand-500 outline-none leading-relaxed">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                </div>

                <!-- Section: Skill & Tim -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                        2. Kebutuhan Keahlian (Skills)
                    </h3>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                            Tag Skill yang Dibutuhkan
                        </label>
                        <div class="p-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-wrap items-center gap-2">
                            <div id="skillsContainer" class="flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 flex items-center gap-1.5">
                                    Laravel
                                    <button type="button" class="text-brand-400 hover:text-brand-600" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                                </span>
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 flex items-center gap-1.5">
                                    Tailwind
                                    <button type="button" class="text-brand-400 hover:text-brand-600" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                                </span>
                            </div>
                            <input type="text" id="skillInput"
                                   placeholder="Ketik nama skill lalu tekan Enter..."
                                   class="bg-transparent border-0 text-xs flex-1 p-1 outline-none text-slate-900 dark:text-white placeholder-slate-400 min-w-[180px]">
                        </div>
                        <input type="hidden" name="skills_hidden" id="skillsHidden" value="Laravel,Tailwind">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3">
                    <a href="{{ route('projects.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-300 text-xs font-bold transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                        <span>Publikasikan Proyek</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const skillInput = document.getElementById('skillInput');
            const skillsContainer = document.getElementById('skillsContainer');
            const skillsHidden = document.getElementById('skillsHidden');

            function updateSkillsHidden() {
                const tags = Array.from(skillsContainer.querySelectorAll('span')).map(s => s.innerText.trim());
                if (skillsHidden) skillsHidden.value = tags.join(',');
            }

            if (skillInput) {
                skillInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const val = skillInput.value.trim();
                        if (val) {
                            const pill = document.createElement('span');
                            pill.className = 'px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-50 dark:bg-brand-950/40 text-brand-700 dark:text-brand-300 flex items-center gap-1.5';
                            pill.innerHTML = `${val} <button type="button" class="text-brand-400 hover:text-brand-600" onclick="this.parentElement.remove();"><i class="bi bi-x"></i></button>`;
                            skillsContainer.appendChild(pill);
                            skillInput.value = '';
                            updateSkillsHidden();
                        }
                    }
                });
            }

            // Timeline auto calculate
            const timelineSelect = document.getElementById('timeline');
            const endDateInput = document.getElementById('end_date');
            function calcEndDate() {
                if (!timelineSelect || !endDateInput) return;
                const months = parseInt(timelineSelect.value, 10);
                const date = new Date();
                date.setMonth(date.getMonth() + months);
                endDateInput.value = date.toISOString().split('T')[0];
            }
            if (timelineSelect) {
                timelineSelect.addEventListener('change', calcEndDate);
                calcEndDate();
            }
        });
    </script>
</x-app-layout>
