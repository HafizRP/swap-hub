@section('title', 'Buat Proyek Baru')
<x-app-layout>
    <div class="w-full">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
            <div>
                <div class="mb-2">
                    <a href="{{ route('projects.index') }}" 
                       class="text-xs font-bold text-stone-500 dark:text-stone-400 hover:text-teal-600 dark:hover:text-teal-400 no-underline inline-flex items-center gap-1.5 transition-colors">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali ke Jelajah Proyek</span>
                    </a>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-stone-900 dark:text-stone-100 tracking-tight mb-1">
                    Buat Proyek Baru
                </h1>
                <p class="text-xs sm:text-sm text-stone-500 dark:text-stone-400 mb-0">
                    Lengkapi detail proyek kolaborasi untuk mulai mengundang talenta rekan mahasiswa.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 px-3.5 py-2 rounded-lg shadow-sm">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=0d9488&color=fff' }}" 
                         class="w-8 h-8 rounded-xl object-cover" alt="{{ auth()->user()->name }}">
                    <div class="text-left leading-tight">
                        <p class="mb-0 font-bold text-xs text-stone-900 dark:text-stone-100">{{ auth()->user()->name }}</p>
                        <span class="text-stone-400 dark:text-stone-500 text-[10px]">{{ auth()->user()->role->name ?? 'Mahasiswa' }}</span>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="flex justify-center">
            <div class="w-full max-w-3xl">
                <div class="bg-white dark:bg-[#141414] rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 overflow-hidden">
                    <div class="p-4 md:p-6">
                        <form method="POST" action="{{ route('projects.store') }}" id="createProjectForm">
                            @csrf
                            
                            <!-- PROJECT BASICS -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">1</span>
                                <h2 class="font-extrabold text-sm uppercase tracking-wider text-stone-900 dark:text-stone-100 mb-0">Informasi Dasar</h2>
                            </div>
 
                            <!-- Project Name -->
                            <div class="mb-5">
                                <label for="title" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                                    Judul Proyek <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                                    class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                                    placeholder="Contoh: AI-Powered Study Assistant Mahasiswa">
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
 
                            <!-- Category -->
                            <div class="mb-5">
                                <label for="category" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                                    Kategori Proyek <span class="text-red-500">*</span>
                                </label>
                                <select id="category" name="category" required 
                                    class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-sm text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                    <option value="Development" {{ old('category') == 'Development' ? 'selected' : '' }}>Development (Web / Mobile / AI)</option>
                                    <option value="Design" {{ old('category') == 'Design' ? 'selected' : '' }}>Design (UI/UX, Branding)</option>
                                    <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing & Growth</option>
                                    <option value="Research" {{ old('category') == 'Research' ? 'selected' : '' }}>Academic & Scientific Research</option>
                                </select>
                            </div>
 
                            <!-- Description -->
                            <div class="mb-6 relative">
                                <label for="description" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">
                                    Deskripsi & Roadmap Proyek <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="5" required
                                    class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-3 text-sm text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all"
                                    placeholder="Jelaskan masalah yang ingin diselesaikan, stack teknologi yang digunakan, serta kriteria rekan tim yang dicari...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
 
                            <!-- TEAM & SCOPE -->
                            <div class="flex items-center gap-2 mb-4 mt-8 pt-6 border-t border-stone-100 dark:border-stone-800">
                                <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">2</span>
                                <h2 class="font-extrabold text-sm uppercase tracking-wider text-stone-900 dark:text-stone-100 mb-0">Kebutuhan Tim & Jadwal</h2>
                            </div>
 
                            <!-- Skills Needed -->
                            <div class="mb-5">
                                <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Keahlian yang Dibutuhkan</label>
                                <div class="bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2.5 flex items-center flex-wrap gap-2 focus-within:ring-2 focus-within:ring-teal-500/20 focus-within:border-teal-600 transition-all">
                                    <div id="skillsContainer" class="flex flex-wrap gap-1.5">
                                        <span class="bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/60 rounded-lg px-2.5 py-1 text-xs font-bold flex items-center gap-1.5 skill-tag">
                                            Laravel <span class="cursor-pointer text-teal-400 hover:text-teal-600" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></span>
                                        </span>
                                    </div>
                                    <input type="text" id="skillInput" class="bg-transparent border-0 text-xs flex-1 p-1 outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400" placeholder="Ketik skill & tekan Enter..." style="min-width: 160px;">
                                </div>
                                <input type="hidden" name="skills_hidden" id="skillsHidden">
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                <!-- Team Size -->
                                <div>
                                    <label for="teamSize" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Target Ukuran Tim</label>
                                    <select id="teamSize" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-sm text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                        <option value="2-3 Members">2-3 Anggota (Kecil & Gesit)</option>
                                        <option value="4-6 Members" selected>4-6 Anggota (Ideal)</option>
                                        <option value="7+ Members">7+ Anggota (Proyek Besar)</option>
                                    </select>
                                </div>
                                
                                <!-- Estimated Timeline -->
                                <div>
                                    <label for="timeline" class="block font-bold text-stone-700 dark:text-stone-300 text-xs uppercase tracking-wider mb-2">Estimasi Durasi Pengerjaan</label>
                                    <select id="timeline" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-sm text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                        <option value="1">1 Bulan (Sprint Singkat)</option>
                                        <option value="3" selected>3 Bulan (Satu Semester)</option>
                                        <option value="6">6 Bulan (Skripsi / Capstone)</option>
                                        <option value="12">1 Tahun (Jangka Panjang)</option>
                                    </select>
                                    <input type="hidden" name="end_date" id="end_date">
                                </div>
                            </div>
 
                            <!-- VISIBILITY & INTEGRATIONS -->
                            <div class="flex items-center gap-2 mb-4 mt-8 pt-6 border-t border-stone-100 dark:border-stone-800">
                                <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">3</span>
                                <h2 class="font-extrabold text-sm uppercase tracking-wider text-stone-900 dark:text-stone-100 mb-0">Visibilitas & Integrasi</h2>
                            </div>
 
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" x-data="{ visibility: 'public' }">
                                <div>
                                    <label class="flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-all"
                                           :class="visibility === 'public' ? 'border-teal-500 bg-teal-50/30 dark:bg-teal-950/20 shadow-sm' : 'border-stone-200 dark:border-stone-800 bg-stone-50/50 dark:bg-[#2e2c29]/20'">
                                        <input type="radio" class="hidden" name="visibility" value="public" checked @click="visibility = 'public'">
                                        <div class="w-9 h-9 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0">
                                            <i class="bi bi-globe2 text-base"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-stone-900 dark:text-stone-100 mb-0.5">Proyek Publik</h4>
                                            <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-0 leading-relaxed">Terbuka untuk dilamar oleh semua mahasiswa terdaftar.</p>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-all"
                                           :class="visibility === 'invite' ? 'border-teal-500 bg-teal-50/30 dark:bg-teal-950/20 shadow-sm' : 'border-stone-200 dark:border-stone-800 bg-stone-50/50 dark:bg-[#2e2c29]/20'">
                                        <input type="radio" class="hidden" name="visibility" value="invite" @click="visibility = 'invite'">
                                        <div class="w-9 h-9 rounded-xl bg-stone-200 dark:bg-stone-800 text-stone-600 dark:text-stone-300 flex items-center justify-center shrink-0">
                                            <i class="bi bi-lock text-base"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-xs text-stone-900 dark:text-stone-100 mb-0.5">Hanya Undangan</h4>
                                            <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-0 leading-relaxed">Hanya anggota yang diundang langsung yang dapat bergabung.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
 
                            <!-- GitHub Integration Collapsible -->
                            <div class="mb-6 p-4 rounded-lg bg-stone-50 dark:bg-[#1a1917] border border-stone-200/80 dark:border-stone-800" x-data="{ gitOpen: false }">
                                <button @click="gitOpen = !gitOpen" type="button" class="w-full text-stone-700 dark:text-stone-200 text-xs font-bold flex items-center justify-between border-0 bg-transparent outline-none cursor-pointer p-0">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-github text-base"></i>
                                        <span>Hubungkan Repositori GitHub (Opsional)</span>
                                    </span>
                                    <i class="bi" :class="gitOpen ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                                </button>
                                
                                <div x-show="gitOpen" class="mt-4 pt-4 border-t border-stone-200/60 dark:border-stone-800" style="display: none;" x-transition>
                                    @if(count($repositories) > 0)
                                        <label for="github_repo_url" class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-2">Pilih Repositori</label>
                                        <select id="github_repo_url" name="github_repo_url"
                                            onchange="document.getElementById('github_repo_name').value = this.options[this.selectedIndex].text"
                                            class="w-full bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-700 rounded-xl px-4 py-2.5 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                                            <option value="">Pilih repositori (Opsional)</option>
                                            @foreach($repositories as $repo)
                                                <option value="{{ $repo['html_url'] }}">{{ $repo['full_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="github_repo_name" name="github_repo_name" value="{{ old('github_repo_name') }}">
                                        
                                        @if(auth()->user()->github_token)
                                            <div class="flex items-center gap-2 mt-3">
                                                <input class="rounded border-stone-300 dark:border-stone-700 text-teal-600 focus:ring-teal-500 dark:bg-stone-800" 
                                                    type="checkbox" id="setup_webhook" name="setup_webhook" value="1" checked>
                                                <label class="text-xs text-stone-600 dark:text-stone-400" for="setup_webhook">
                                                    Pasang webhook otomatis untuk pelacakan commit & reputasi (Direkomendasikan)
                                                </label>
                                            </div>
                                        @endif
                                    @else
                                        <div class="p-3 rounded-xl bg-white dark:bg-[#141414] text-xs text-stone-500 dark:text-stone-400 border border-stone-200 dark:border-stone-800 flex items-center justify-between">
                                            <span>Belum ada repositori yang terhubung.</span>
                                            <a href="{{ route('auth.github') }}" class="font-bold text-teal-600 dark:text-teal-400 hover:underline no-underline">
                                                Tautkan Akun GitHub
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
 
                            <!-- Actions -->
                            <div class="pt-6 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between">
                                <a href="{{ route('projects.index') }}" class="text-stone-500 hover:text-stone-700 dark:text-stone-400 dark:hover:text-stone-200 text-xs font-bold no-underline transition-colors">
                                    Batal
                                </a>
                                <button type="submit" 
                                    class="py-3 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-md shadow-teal-500/20 transition-all duration-150 active:scale-[0.98] flex items-center gap-2 border-0 cursor-pointer">
                                    <span>Publikasikan Proyek</span>
                                    <i class="bi bi-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Skill Input Logic
            const skillInput = document.getElementById('skillInput');
            const skillsContainer = document.getElementById('skillsContainer');
            
            skillInput?.addEventListener('keydown', function(e) {
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
                tag.className = 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200/60 dark:border-teal-800/60 rounded-lg px-2.5 py-1 text-xs font-bold flex items-center gap-1.5 skill-tag';
                tag.innerHTML = `${text} <span class="cursor-pointer text-teal-400 hover:text-teal-600" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></span>`;
                skillsContainer.appendChild(tag);
            }
 
            // 2. Timeline Logic (Set End Date)
            const timelineSelect = document.getElementById('timeline');
            const endDateInput = document.getElementById('end_date');
            
            function updateEndDate() {
                if (!timelineSelect || !endDateInput) return;
                const months = parseInt(timelineSelect.value);
                const date = new Date();
                date.setMonth(date.getMonth() + months);
                const dateString = date.toISOString().split('T')[0];
                endDateInput.value = dateString;
            }
            timelineSelect?.addEventListener('change', updateEndDate);
            updateEndDate();
 
            // 3. Form Submission (Append Data to Description)
            const form = document.getElementById('createProjectForm');
            form?.addEventListener('submit', function(e) {
                const skills = Array.from(skillsContainer.querySelectorAll('.skill-tag')).map(el => el.innerText.trim());
                const teamSize = document.getElementById('teamSize')?.value;
                const visibilityEl = document.querySelector('input[name="visibility"]:checked');
                const visibility = visibilityEl ? visibilityEl.value : 'public';
                const visibilityLabel = visibility === 'public' ? 'Public' : 'Invite Only';
 
                const descInput = document.getElementById('description');
                let appendText = `\n\n---\n**Detail Tambahan:**`;
                if (skills.length) appendText += `\n- **Keahlian Dibutuhkan:** ${skills.join(', ')}`;
                if (teamSize) appendText += `\n- **Ukuran Tim:** ${teamSize}`;
                appendText += `\n- **Visibilitas:** ${visibilityLabel}`;
                
                descInput.value += appendText;
            });
        });
    </script>
</x-app-layout>