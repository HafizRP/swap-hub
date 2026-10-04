<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
    <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-wider">Team Matchmaker</span>
                <h1 class="text-xl font-bold text-stone-900 dark:text-stone-100 mt-1 mb-0">{{ $project->title }}</h1>
                <p class="text-stone-500 dark:text-stone-400 text-xs mt-1 mb-0">Rekomendasi teman tim berdasarkan kecocokan skill & course.</p>
            </div>
            <a href="{{ route('projects.show', $project->id) }}" class="px-3.5 py-1.5 border border-stone-200 dark:border-stone-700 rounded-lg text-xs font-medium text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-800 no-underline transition-colors">
                &larr; Kembali ke Proyek
            </a>
        </div>

        <div class="mt-4 flex flex-wrap gap-2 items-center">
            <span class="text-xs font-semibold text-stone-500">Skill Dibutuhkan:</span>
            @forelse($project->requiredSkills as $sk)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800">
                    {{ $sk->name }}
                </span>
            @empty
                <span class="text-xs text-stone-400">Belum ada spesifikasi skill.</span>
            @endforelse
        </div>
    </div>

    <!-- Match recommendations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($recommendedPeers as $peer)
            <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 flex flex-col justify-between" wire:key="peer-{{ $peer->id }}">
                <div>
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $peer->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($peer->name) . '&background=0d9488&color=fff' }}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h3 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0">{{ $peer->name }}</h3>
                                <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">{{ $peer->university ?? 'Mahasiswa' }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $peer->match_percentage >= 70 ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800' : 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800' }}">
                            {{ $peer->match_percentage }}% Match
                        </span>
                    </div>

                    <div class="mt-4 space-y-1.5">
                        <p class="text-xs font-semibold text-stone-500 mb-1">Skill Rekan:</p>
                        <div class="flex flex-wrap gap-1">
                            @forelse($peer->skills as $psk)
                                <span class="px-2 py-0.5 rounded text-[11px] bg-stone-100 dark:bg-stone-800 text-stone-700 dark:text-stone-300">
                                    {{ $psk->name }}
                                </span>
                            @empty
                                <span class="text-xs text-stone-400 italic">Belum menambahkan skill</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-3 border-t border-stone-100 dark:border-stone-800 flex items-center justify-between">
                    <a href="{{ route('portfolio.show', $peer->id) }}" class="text-xs text-teal-600 dark:text-teal-400 hover:underline font-semibold no-underline">
                        Lihat Portofolio
                    </a>
                    <form action="{{ route('chat.direct', $peer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-medium transition-colors border-0 cursor-pointer shadow-sm">
                            <i class="bi bi-chat-text"></i> Undang / Chat
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white dark:bg-[#141414] p-8 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 text-center">
                <i class="bi bi-people text-4xl text-stone-300 dark:text-stone-600 mb-2 block"></i>
                <p class="text-stone-600 dark:text-stone-400 text-sm font-semibold mb-1">Belum menemukan rekan yang cocok.</p>
                <small class="text-stone-400 dark:text-stone-500 text-xs">Tambahkan tag mata kuliah atau keahlian spesifik pada proyek Anda untuk mendapatkan rekomendasi terbaik.</small>
            </div>
        @endforelse
    </div>
</div>
