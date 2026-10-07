@section('title', $project->title)
<x-app-layout>
    <div x-data="{ showInviteModal: false, showValidateModal: false, validateUserId: null, validateUserName: '' }" class="w-full">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-grow">
                    <!-- Back Link -->
                    <div class="mb-3">
                        <a href="{{ route('projects.index') }}" 
                           class="bg-white dark:bg-[#141414] hover:bg-stone-50 dark:hover:bg-[#2e2c29] border border-stone-200 dark:border-stone-800 text-stone-600 dark:text-stone-300 rounded-full px-3.5 py-1.5 text-xs font-bold inline-flex items-center gap-1.5 transition-colors no-underline shadow-sm">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Proyek</span>
                        </a>
                    </div>
 
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-black text-stone-900 dark:text-stone-100 tracking-tight mb-0">
                            {{ $project->title }}
                        </h1>
                        @php
                            $statusBadge = match ($project->status) {
                                'active' => ['text' => 'text-emerald-700 dark:text-emerald-300', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60', 'label' => 'Open'],
                                'planning' => ['text' => 'text-teal-700 dark:text-teal-300', 'bg' => 'bg-teal-50 dark:bg-teal-950/40 border-teal-200 dark:border-teal-800/60', 'label' => 'Planning'],
                                default => ['text' => 'text-stone-600 dark:text-stone-400', 'bg' => 'bg-stone-50 dark:bg-[#2e2c29]/50 border-stone-200 dark:border-stone-800', 'label' => ucfirst($project->status)],
                            };
                        @endphp
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                            {{ $statusBadge['label'] }}
                        </span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold bg-stone-100 dark:bg-[#2e2c29]/50 text-stone-600 dark:text-stone-300 border border-stone-200 dark:border-stone-800">
                            {{ $project->category ?? 'General' }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    @if(auth()->id() == $project->owner_id)
                        <a href="{{ route('projects.edit', $project) }}"
                            class="border border-stone-200 dark:border-stone-800 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-[#2e2c29] font-bold py-2 px-4 rounded-xl text-xs transition-colors inline-flex items-center gap-1.5 no-underline shadow-sm">
                            <i class="bi bi-pencil-square"></i>
                            <span>Edit Proyek</span>
                        </a>
                        @if($project->conversation)
                            <a href="{{ route('chat', $project->conversation) }}"
                                class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] shadow-sm shadow-teal-500/20 inline-flex items-center gap-1.5 no-underline">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Squad Chat</span>
                            </a>
                        @endif
                    @else
                        @php
                            $member = $project->members->firstWhere('id', auth()->id());
                            $status = $member ? $member->pivot->status : null;
                        @endphp
                        @if($status === 'active')
                            @if($project->conversation)
                                <a href="{{ route('chat', $project->conversation) }}"
                                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] shadow-sm shadow-teal-500/20 inline-flex items-center gap-1.5 no-underline">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <span>Squad Chat</span>
                                </a>
                            @else
                                <div class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 px-4 py-2 rounded-xl text-xs font-bold border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1.5">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Anggota Terdaftar</span>
                                </div>
                            @endif
                        @elseif($status === 'pending')
                            <div class="bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 px-4 py-2 rounded-xl text-xs font-bold border border-amber-200 dark:border-amber-800/60 flex items-center gap-1.5">
                                <i class="bi bi-hourglass-split"></i>
                                <span>Menunggu Persetujuan</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
 
        {{-- Banner for Non-Members / Pending --}}
        @if(auth()->id() !== $project->owner_id)
            @php
                $member = $project->members->firstWhere('id', auth()->id());
                $status = $member ? $member->pivot->status : null;
            @endphp

            @if($status === 'pending')
                <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 shadow-sm rounded-lg flex items-center gap-3.5 mb-6 p-4 text-amber-800 dark:text-amber-300">
                    <i class="bi bi-hourglass-split text-2xl shrink-0"></i>
                    <div>
                        <h4 class="font-bold text-sm mb-0.5">Permohonan Bergabung Sedang Ditinjau</h4>
                        <p class="text-xs text-amber-700/80 dark:text-amber-400/80 mb-0">Pengajuan bergabung Anda sedang menunggu verifikasi dari pemilik proyek.</p>
                    </div>
                </div>
            @elseif($status === 'active' && $project->conversation)
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 shadow-sm rounded-lg flex flex-col md:flex-row md:items-center justify-between mb-6 p-4 text-emerald-800 dark:text-emerald-300 gap-4">
                    <div class="flex items-center gap-3.5">
                        <i class="bi bi-check-circle-fill text-2xl shrink-0"></i>
                        <div>
                            <h4 class="font-bold text-sm mb-0.5">Anda adalah bagian dari Squad ini!</h4>
                            <p class="text-xs text-emerald-700/80 dark:text-emerald-400/80 mb-0">Diskusikan langkah selanjutnya dan kelola tugas di Squad Workspace.</p>
                        </div>
                    </div>
                    <a href="{{ route('chat', $project->conversation) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm text-xs border-0 transition-all duration-150 active:scale-[0.98] no-underline inline-flex items-center gap-1.5 shrink-0">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>Buka Squad Workspace</span>
                    </a>
                </div>
            @endif
        @endif
 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
 
            <!-- Main Content (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-4">
 
                <!-- Description Card -->
                <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] overflow-hidden">
                    <div class="border-b border-stone-100 dark:border-stone-800/60 p-4">
                        <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-base mb-0">Deskripsi Proyek</h3>
                    </div>
                    <div class="p-4">
                        <div class="text-stone-600 dark:text-stone-300 text-sm leading-relaxed whitespace-pre-line">
                            {{ $project->description }}
                        </div>
 
                        @if($project->github_repo_url)
                            <div class="bg-stone-50 dark:bg-[#2e2c29]/30 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between border border-stone-200 dark:border-stone-800 mt-6 gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 flex items-center justify-center text-stone-800 dark:text-stone-100 shrink-0">
                                        <i class="bi bi-github text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0.5">
                                            {{ $project->github_repo_name ?? 'Repositori Terhubung' }}
                                        </h4>
                                        <p class="text-[11px] text-stone-400 dark:text-stone-500 mb-0">Integrasi commit GitHub aktif</p>
                                    </div>
                                </div>
                                <a href="{{ $project->github_repo_url }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 no-underline transition-colors shrink-0">
                                    <span>Buka Repositori</span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Project Required Skills Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 p-5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-tags-fill text-indigo-600 dark:text-indigo-400"></i>
                            <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-0">Keahlian yang Dibutuhkan</h3>
                        </div>
                        <span class="text-xs font-bold text-slate-400">
                            {{ $project->skills->count() }} Keahlian
                        </span>
                    </div>
                    <div class="p-6">
                        @if($project->skills->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($project->skills as $ps)
                                    @php
                                        $userHasSkill = auth()->check() && auth()->user()->skills->contains('id', $ps->id);
                                    @endphp
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border {{ $userHasSkill ? 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800/60 text-indigo-700 dark:text-indigo-300' : 'bg-slate-50 dark:bg-slate-700/30 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}">
                                        @if($userHasSkill)
                                            <i class="bi bi-check-circle-fill text-emerald-500 text-xs"></i>
                                        @else
                                            <i class="bi bi-circle text-slate-400 text-xs"></i>
                                        @endif
                                        <span class="text-xs font-bold">{{ $ps->name }}</span>
                                        <span class="text-[9px] uppercase tracking-wider font-extrabold px-1.5 py-0.5 rounded bg-white/60 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400">
                                            {{ $ps->pivot->importance ?? 'Wajib' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 mb-0">Pemilik proyek belum menentukan daftar keahlian spesifik.</p>
                        @endif
                    </div>
                </div>

                <!-- GitHub Activity Feed Timeline -->
                <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] overflow-hidden">
                    <div class="border-b border-stone-100 dark:border-stone-800/60 p-4 flex justify-between items-center">
                        <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-base mb-0">Aktivitas GitHub</h3>
                        <span class="bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full border border-teal-200/60 dark:border-teal-800/60">
                            Live Sinkronisasi
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-stone-200 dark:before:bg-[#2e2c29]">
                            @forelse($project->githubActivities as $activity)
                                <div class="relative">
                                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-teal-600 border-2 border-white dark:border-[#242220]"></div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-xs text-stone-800 dark:text-stone-100">{{ $activity->user->name ?? 'Kontributor' }}</span>
                                            <span class="text-[10px] font-mono bg-stone-100 dark:bg-[#2e2c29]/60 text-stone-600 dark:text-stone-300 px-1.5 py-0.5 rounded">
                                                {{ substr($activity->commit_sha, 0, 7) }}
                                            </span>
                                            <span class="text-[10px] text-stone-400 dark:text-stone-500 ml-auto">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-stone-600 dark:text-stone-400 mb-0 font-normal pl-2 border-l-2 border-teal-500/20 italic">
                                            "{{ $activity->commit_message }}"
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-stone-400 dark:text-stone-500 text-xs mb-0">Belum ada riwayat aktivitas commit yang tercatat.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Sidebar (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6">

                @if(isset($skillMatch) && $skillMatch['required_count'] > 0)
                    <!-- Academic Skill Matching Analysis (Jaccard Similarity) -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm">
                                    <i class="bi bi-cpu-fill"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm mb-0">Analisis Kecocokan</h3>
                                    <span class="text-[10px] text-slate-400 font-medium">Algoritma Jaccard Similarity</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $skillMatch['badge_class'] }}">
                                {{ $skillMatch['label'] }}
                            </span>
                        </div>

                        <!-- Progress Bar & Score -->
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-3.5 border border-slate-100 dark:border-slate-700/50 mb-4">
                            <div class="flex justify-between items-baseline mb-2">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Skor Keselarasan</span>
                                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $skillMatch['match_percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $skillMatch['match_percentage'] }}%"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-3 pt-2.5 border-t border-slate-200/60 dark:border-slate-700/60 text-center">
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider">Jaccard Index</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 font-mono">{{ $skillMatch['jaccard_index'] }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider">Cakupan Kebutuhan</span>
                                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 font-mono">{{ round($skillMatch['coverage'] * 100) }}%</span>
                                </div>
                            </div>
                        </div>

                        <!-- Matched Skills -->
                        <div class="space-y-3 text-xs">
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1.5 flex items-center gap-1.5">
                                    <i class="bi bi-check-circle-fill text-emerald-500"></i>
                                    Skill Anda yang Memenuhi ({{ $skillMatch['matched_skills']->count() }})
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($skillMatch['matched_skills'] as $ms)
                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1">
                                            <i class="bi bi-check2"></i>
                                            {{ $ms->name }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">Belum ada skill profil yang cocok.</span>
                                    @endforelse
                                </div>
                            </div>

                            @if($skillMatch['missing_skills']->isNotEmpty())
                                <div class="pt-2 border-t border-slate-100 dark:border-slate-700/50">
                                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-1.5 flex items-center gap-1.5">
                                        <i class="bi bi-exclamation-circle text-amber-500"></i>
                                        Skill yang Masih Dibutuhkan ({{ $skillMatch['missing_skills']->count() }})
                                    </span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($skillMatch['missing_skills'] as $mis)
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                {{ $mis->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                <!-- Squad Members -->
                <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] overflow-hidden">
                    <div class="border-b border-stone-100 dark:border-stone-800/60 p-4 flex justify-between items-center">
                        <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-sm mb-0">Anggota Squad</h3>
                        <span class="text-xs font-bold text-stone-500 dark:text-stone-400 bg-stone-100 dark:bg-[#2e2c29]/50 px-2 py-0.5 rounded-full">
                            {{ $project->members->count() }} Orang
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            @foreach($project->members as $member)
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=0d9488&color=fff' }}"
                                            class="w-10 h-10 rounded-xl object-cover border border-stone-200 dark:border-stone-800 shrink-0" alt="{{ $member->name }}">
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-stone-800 dark:text-stone-100 text-xs mb-0.5 truncate">{{ $member->name }}</h4>
                                            <span class="text-[10px] text-stone-400 dark:text-stone-500 uppercase font-bold tracking-wider">{{ $member->pivot->role ?? 'Member' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($member->pivot->is_validated)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-500 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-lg border border-amber-200/50">
                                                <i class="bi bi-star-fill text-xs"></i>
                                                <span>{{ $member->pivot->contribution_rating }}/5</span>
                                            </span>
                                            <span class="text-teal-600 dark:text-teal-400" title="Kontributor Terverifikasi">
                                                <i class="bi bi-patch-check-fill text-lg"></i>
                                            </span>
                                        @elseif(auth()->id() == $project->owner_id && $member->id != auth()->id())
                                            <button @click="validateUserId = {{ $member->id }}; validateUserName = '{{ addslashes($member->name) }}'; showValidateModal = true"
                                                    class="px-2.5 py-1 text-[11px] font-bold text-teal-600 hover:text-white bg-teal-50 dark:bg-teal-950/50 hover:bg-teal-600 rounded-lg transition-colors border border-teal-200/60 dark:border-teal-800 cursor-pointer">
                                                Nilai
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
 
                        @if(auth()->id() == $project->owner_id)
                            <button @click="showInviteModal = true"
                                class="w-full mt-4 py-2 px-3 rounded-xl border border-dashed border-teal-300 dark:border-teal-700/60 text-teal-600 dark:text-teal-400 text-xs font-bold hover:bg-teal-50/50 dark:hover:bg-teal-950/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-transparent">
                                <i class="bi bi-person-plus"></i>
                                <span>Undang Talenta Mahasiswa</span>
                            </button>
                        @endif
                    </div>
                </div>
 
                <!-- Collaboration Stats -->
                <div class="bg-white dark:bg-[#141414] rounded-lg border border-stone-200 dark:border-stone-800 shadow-[0_1px_2px_rgba(0,0,0,0.04)] p-4">
                    <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-sm mb-4">Statistik Kolaborasi</h3>
                    <div class="space-y-3">
                        <!-- Commits -->
                        <div class="p-3 rounded-xl bg-stone-50 dark:bg-[#2e2c29]/30 flex items-center justify-between border border-stone-100 dark:border-stone-800/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                                    <i class="bi bi-git"></i>
                                </div>
                                <span class="font-bold text-xs text-stone-500 dark:text-stone-400">Total Commits</span>
                            </div>
                            <span class="text-sm font-black text-stone-900 dark:text-stone-100">{{ $project->githubActivities->count() }}</span>
                        </div>
                        
                        <!-- Health -->
                        <div class="p-3 rounded-xl bg-stone-50 dark:bg-[#2e2c29]/30 flex items-center justify-between border border-stone-100 dark:border-stone-800/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <span class="font-bold text-xs text-stone-500 dark:text-stone-400">Status Proyek</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">Optimal</span>
                        </div>
 
                        <!-- Active Days -->
                        <div class="p-3 rounded-xl bg-stone-50 dark:bg-[#2e2c29]/30 flex items-center justify-between border border-stone-100 dark:border-stone-800/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <span class="font-bold text-xs text-stone-500 dark:text-stone-400">Durasi Aktif</span>
                            </div>
                            <span class="text-sm font-black text-stone-900 dark:text-stone-100">{{ (int) ($project->created_at->diffInDays() + 1) }} Hari</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
 
        <!-- Invite Modal (Alpine.js) -->
        <div x-show="showInviteModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-stone-900/60 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl max-w-md w-full shadow-2xl p-4" @click.outside="showInviteModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-stone-100 dark:border-stone-800/50 mb-4">
                    <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-base mb-0">Undang Talenta ke Squad</h3>
                    <button @click="showInviteModal = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 border-0 bg-transparent cursor-pointer">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ route('projects.members.add', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">Email Mahasiswa</label>
                        <input type="email" name="email" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-600 rounded-xl px-3.5 py-2.5 text-xs text-stone-800 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all" placeholder="rekan@kampus.ac.id" required>
                    </div>
                    <div>
                        <label for="role" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">Peran di Tim</label>
                        <select name="role" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-600 rounded-xl px-3.5 py-2.5 text-xs text-stone-800 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                            <option value="member">Member</option>
                            <option value="collaborator">Collaborator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer shadow-sm">
                            Kirim Undangan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Validate / Review Member Modal (Alpine.js) -->
        <div x-show="showValidateModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-stone-900/60 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl max-w-md w-full shadow-2xl p-4" @click.outside="showValidateModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-stone-100 dark:border-stone-800/50 mb-4">
                    <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-base mb-0">
                        Review Kontribusi: <span x-text="validateUserName"></span>
                    </h3>
                    <button @click="showValidateModal = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 border-0 bg-transparent cursor-pointer">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form :action="'/projects/{{ $project->id }}/validate/' + validateUserId" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rating" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">Rating Kontribusi (1-5)</label>
                        <select name="rating" id="rating" required class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-600 rounded-xl px-3.5 py-2.5 text-xs text-stone-800 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all">
                            <option value="5">⭐⭐⭐⭐⭐ 5 - Sangat Luar Biasa (+50 Poin)</option>
                            <option value="4">⭐⭐⭐⭐ 4 - Baik & Konsisten (+40 Poin)</option>
                            <option value="3" selected>⭐⭐⭐ 3 - Cukup (+30 Poin)</option>
                            <option value="2">⭐⭐ 2 - Kurang Aktif (+20 Poin)</option>
                            <option value="1">⭐ 1 - Minimal (+10 Poin)</option>
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-xs font-bold text-stone-700 dark:text-stone-300 uppercase tracking-wider mb-2">Catatan / Ulasan Kontribusi</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-600 rounded-xl px-3.5 py-2.5 text-xs text-stone-800 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all" placeholder="Tuliskan apresiasi atau feedback konstruktif..."></textarea>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-3 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer shadow-sm">
                            Simpan Review & Beri Poin
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>