@section('title', $user->name . ' - Profil')
<x-app-layout>
    <div class="space-y-6">

        <!-- Profile Header Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    <div class="relative">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=6366f1&color=fff' }}" 
                             alt="{{ $user->name }}"
                             class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover ring-4 ring-slate-100 dark:ring-slate-800 shadow-sm">
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900" title="Online"></span>
                    </div>

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                {{ $user->name }}
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                                {{ $user->reputation_points ?? 0 }} CP
                            </span>
                        </div>

                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">
                            {{ $user->university ?? 'Mahasiswa' }} <span class="text-slate-300 dark:text-slate-700">•</span> {{ $user->major ?? 'Teknologi Informasi' }}
                        </p>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-semibold text-slate-500 dark:text-slate-400 pt-1">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill text-brand-500"></i>
                                {{ $user->location ?? 'Indonesia' }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-folder-check text-emerald-500"></i>
                                {{ $user->projects->count() }} Proyek Selesai
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action CTAs -->
                <div class="flex flex-wrap items-center justify-center gap-2.5 shrink-0">
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                            <i class="bi bi-pencil-square"></i>
                            <span>Edit Profil</span>
                        </a>
                        <a href="{{ route('profile.resume', $user) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all shadow-sm">
                            <i class="bi bi-file-earmark-pdf-fill text-rose-500"></i>
                            <span>Download CV</span>
                        </a>
                    @else
                        <form action="{{ route('chat.direct', $user) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Kirim Pesan</span>
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>

        <!-- Bento Grid: Bio & Skills -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- About Section -->
            <div class="lg:col-span-7 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400 mb-3 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Tentang Saya</span>
                    </h3>
                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                        {{ $user->bio ?? "Mahasiswa {$user->major} di {$user->university} yang berfokus pada kolaborasi proyek, pengembangan perangkat lunak, dan eksplorasi teknologi baru." }}
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="block text-[10px] font-extrabold uppercase text-slate-400">Pengalaman</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Mahasiswa</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="block text-[10px] font-extrabold uppercase text-slate-400">Proyek</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $user->projects->count() }} Kolaborasi</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="block text-[10px] font-extrabold uppercase text-slate-400">Reputasi</span>
                        <span class="text-xs font-bold text-brand-600 dark:text-brand-400">{{ $user->reputation_points }} CP</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="block text-[10px] font-extrabold uppercase text-slate-400">Bahasa</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">ID, EN</span>
                    </div>
                </div>
            </div>

            <!-- Skills & Proficiency -->
            <div class="lg:col-span-5 p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-brand-600 dark:text-brand-400 flex items-center gap-2">
                    <i class="bi bi-stars"></i>
                    <span>Keahlian & Kemampuan</span>
                </h3>

                <!-- Skill Badges -->
                <div class="flex flex-wrap gap-2">
                    @forelse($user->skills as $skill)
                        <span class="px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200/80 dark:border-slate-700 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-500"></span>
                            {{ $skill->name }}
                        </span>
                    @empty
                        <p class="text-xs text-slate-400">Belum ada keahlian yang ditambahkan.</p>
                    @endforelse
                </div>

                <!-- Proficiency Bars -->
                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <span class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Tingkat Kemahiran</span>
                    @php
                        $levelMap = ['beginner' => 35, 'intermediate' => 65, 'advanced' => 85, 'expert' => 98];
                    @endphp
                    @forelse($user->skills->take(4) as $skill)
                        @php
                            $val = $levelMap[$skill->pivot->proficiency_level] ?? 60;
                        @endphp
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-slate-700 dark:text-slate-300">{{ $skill->name }}</span>
                                <span class="text-brand-600 dark:text-brand-400">{{ ucfirst($skill->pivot->proficiency_level ?? 'Intermediate') }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-brand-600 to-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $val }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Tingkat kemahiran akan muncul setelah menambahkan keahlian.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Project History List -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-collection-play-fill text-brand-600"></i>
                    <span>Riwayat Kolaborasi Proyek</span>
                </h3>
                <a href="{{ route('projects.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                    Semua Proyek <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 uppercase font-extrabold text-[10px]">
                            <th class="py-3 px-4">Proyek</th>
                            <th class="py-3 px-4">Peran</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($user->projects->take(6) as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/50 text-brand-600 flex items-center justify-center shrink-0">
                                        <i class="bi bi-folder-fill"></i>
                                    </div>
                                    <span>{{ $project->title }}</span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400 font-semibold">
                                    {{ $project->pivot->role ?? 'Anggota' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        {{ ucfirst($project->status ?? 'Active') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('projects.show', $project) }}" class="text-xs font-bold text-brand-600 hover:underline">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat kolaborasi proyek.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
