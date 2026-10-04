<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <!-- Profile Card Header -->
    <div class="bg-white dark:bg-[#141414] p-6 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d9488&color=fff' }}" class="w-20 h-20 rounded-full object-cover ring-2 ring-teal-500/20 shadow-sm">
            <div class="flex-grow space-y-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-extrabold text-stone-900 dark:text-stone-100 mb-0">{{ $user->name }}</h1>
                        <p class="text-xs text-stone-500 dark:text-stone-400 mb-0 mt-0.5">{{ $user->university ?? 'Mahasiswa' }} &bull; {{ $user->major ?? 'Informatika' }}</p>
                    </div>
                    @if(auth()->id() !== $user->id)
                        <form action="{{ route('chat.direct', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold border-0 cursor-pointer shadow-sm flex items-center gap-1.5 transition-colors">
                                <i class="bi bi-chat-dots-fill"></i> Kirim Pesan
                            </button>
                        </form>
                    @endif
                </div>

                @if($user->bio)
                    <p class="text-xs text-stone-600 dark:text-stone-300 mb-0 max-w-2xl">{{ $user->bio }}</p>
                @endif

                <!-- Highlights bar -->
                <div class="flex flex-wrap gap-4 pt-3 border-t border-stone-100 dark:border-stone-800 text-xs text-stone-600 dark:text-stone-400">
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-trophy text-amber-500"></i>
                        <span><strong>{{ $user->reputation_points }}</strong> Reputasi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-coin text-teal-600"></i>
                        <span><strong>{{ $user->credits }}</strong> Kredit</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-check-circle text-emerald-600"></i>
                        <span><strong>{{ $completed_skill_swaps_count }}</strong> Skill Swap</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <i class="bi bi-github text-stone-800 dark:text-stone-200"></i>
                        <span><strong>{{ $github_activity_count }}</strong> Aktivitas Git</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Verified Projects -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0 flex items-center gap-2">
                        <i class="bi bi-patch-check-fill text-teal-600 text-base"></i>
                        Kontribusi Proyek Terverifikasi ({{ $verified_projects->count() }})
                    </h2>
                </div>

                <div class="space-y-3">
                    @forelse($verified_projects as $membership)
                        <div class="p-4 rounded-xl border border-stone-100 dark:border-stone-800 bg-stone-50/50 dark:bg-[#1a1917] space-y-2">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-bold text-xs text-stone-900 dark:text-stone-100 mb-0">
                                        {{ $membership->project->title }}
                                    </h3>
                                    <span class="text-[10px] text-stone-400">Role: {{ $membership->role }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                    Rating: {{ $membership->contribution_rating ?? 5 }}/5
                                </span>
                            </div>
                            @if($membership->contribution_notes)
                                <p class="text-xs text-stone-600 dark:text-stone-400 mb-0 italic">"{{ $membership->contribution_notes }}"</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-stone-400 text-xs">
                            Belum ada kontribusi proyek yang divalidasi oleh project owner.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Courses Enrolled / Completed -->
            @if($user->courses->isNotEmpty())
                <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-3">
                    <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Mata Kuliah Diambil</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($user->courses as $course)
                            <div class="p-3 bg-stone-50 dark:bg-[#1a1917] rounded-lg border border-stone-100 dark:border-stone-800">
                                <span class="text-[10px] font-bold text-teal-600 uppercase">{{ $course->code }}</span>
                                <h4 class="text-xs font-bold text-stone-800 dark:text-stone-200 mb-0">{{ $course->name }}</h4>
                                <span class="text-[10px] text-stone-400">{{ $course->department }} &bull; Semester {{ $course->semester }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar: Skills & Badges -->
        <div class="space-y-6">
            <!-- Badges Section -->
            <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-3">
                <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0 flex items-center gap-1.5">
                    <i class="bi bi-award text-amber-500"></i> Lencana & Prestasi ({{ $badges->count() }})
                </h2>
                <div class="space-y-2">
                    @forelse($badges as $badge)
                        <div class="flex items-center gap-3 p-2.5 bg-stone-50 dark:bg-[#1a1917] rounded-lg border border-stone-100 dark:border-stone-800">
                            <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center text-sm shrink-0">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-stone-900 dark:text-stone-100 truncate mb-0">{{ $badge->name }}</h4>
                                <p class="text-[10px] text-stone-400 truncate mb-0">{{ $badge->description ?? 'Pencapaian kolaborasi' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400 mb-0 text-center py-4">Belum ada lencana yang diraih.</p>
                    @endforelse
                </div>
            </div>

            <!-- Skills Section -->
            <div class="bg-white dark:bg-[#141414] p-5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-3">
                <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Keahlian & Teknologi ({{ $skills->count() }})</h2>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($skills as $skill)
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-200 border border-stone-200/60 dark:border-stone-700">
                            {{ $skill->name }}
                        </span>
                    @empty
                        <p class="text-xs text-stone-400 mb-0">Belum ada skill yang ditambahkan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
