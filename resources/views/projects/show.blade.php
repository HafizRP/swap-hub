@section('title', $project->title)
<x-app-layout>
    <div x-data="{ showInviteModal: false }" class="container mx-auto py-6">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                <div class="flex-grow">
                    <!-- Back Link -->
                    <div class="mb-3">
                        <a href="{{ route('projects.index') }}" 
                           class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 rounded-full px-3.5 py-1.5 text-xs font-bold inline-flex items-center gap-1.5 transition-colors no-underline shadow-sm">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali ke Proyek</span>
                        </a>
                    </div>
 
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100 tracking-tight mb-0">
                            {{ $project->title }}
                        </h1>
                        @php
                            $statusBadge = match ($project->status) {
                                'active' => ['text' => 'text-emerald-700 dark:text-emerald-300', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60', 'label' => 'Open'],
                                'planning' => ['text' => 'text-indigo-700 dark:text-indigo-300', 'bg' => 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800/60', 'label' => 'Planning'],
                                default => ['text' => 'text-slate-700 dark:text-slate-300', 'bg' => 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700', 'label' => ucfirst($project->status)],
                            };
                        @endphp
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                            {{ $statusBadge['label'] }}
                        </span>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50">
                            {{ $project->category ?? 'General' }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    @if(auth()->id() == $project->owner_id)
                        <a href="{{ route('projects.edit', $project) }}"
                            class="border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/60 font-bold py-2 px-4 rounded-xl text-xs transition-colors inline-flex items-center gap-1.5 no-underline shadow-sm">
                            <i class="bi bi-pencil-square"></i>
                            <span>Edit Proyek</span>
                        </a>
                        @if($project->conversation)
                            <a href="{{ route('chat', $project->conversation) }}"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] shadow-sm shadow-indigo-500/25 inline-flex items-center gap-1.5 no-underline">
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
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] shadow-sm shadow-indigo-500/25 inline-flex items-center gap-1.5 no-underline">
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
 
            @if($status === null || $status === 'rejected')
                <div class="bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-200/80 dark:border-indigo-800/60 shadow-sm rounded-2xl flex flex-col md:flex-row md:items-center justify-between mb-6 p-5 gap-4">
                    <div>
                        <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-1 flex items-center gap-2">
                            <i class="bi bi-stars text-indigo-600 dark:text-indigo-400"></i>
                            Tertarik bergabung dengan proyek ini?
                        </h4>
                        <p class="text-slate-600 dark:text-slate-400 text-xs mb-0">
                            Ajukan diri Anda sekarang untuk mulai berkolaborasi dengan tim dan bangun reputasi portofolio.
                        </p>
                    </div>
                    <form action="{{ route('projects.members.add', $project) }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="member">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-sm text-xs border-0 transition-all duration-150 active:scale-[0.98] inline-flex items-center gap-1.5 cursor-pointer shrink-0">
                            <i class="bi bi-person-plus-fill"></i>
                            <span>Lamar ke Squad</span>
                        </button>
                    </form>
                </div>
            @elseif($status === 'pending')
                <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 shadow-sm rounded-2xl flex items-center gap-3.5 mb-6 p-5 text-amber-800 dark:text-amber-300">
                    <i class="bi bi-hourglass-split text-2xl shrink-0"></i>
                    <div>
                        <h4 class="font-bold text-sm mb-0.5">Permohonan Bergabung Sedang Ditinjau</h4>
                        <p class="text-xs text-amber-700/80 dark:text-amber-400/80 mb-0">Pengajuan bergabung Anda sedang menunggu verifikasi dari pemilik proyek.</p>
                    </div>
                </div>
            @elseif($status === 'active' && $project->conversation)
                <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 shadow-sm rounded-2xl flex flex-col md:flex-row md:items-center justify-between mb-6 p-5 text-emerald-800 dark:text-emerald-300 gap-4">
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
 
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
 
            <!-- Main Content (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
 
                <!-- Description Card -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 p-5">
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-0">Deskripsi Proyek</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                            {{ $project->description }}
                        </div>
 
                        @if($project->github_repo_url)
                            <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between border border-slate-200/80 dark:border-slate-700 mt-6 gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-800 dark:text-slate-100 shrink-0">
                                        <i class="bi bi-github text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-slate-100 text-xs mb-0.5">
                                            {{ $project->github_repo_name ?? 'Repositori Terhubung' }}
                                        </h4>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mb-0">Integrasi commit GitHub aktif</p>
                                    </div>
                                </div>
                                <a href="{{ $project->github_repo_url }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 no-underline transition-colors shrink-0">
                                    <span>Buka Repositori</span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
 
                <!-- GitHub Activity Feed Timeline -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 p-5 flex justify-between items-center">
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-0">Aktivitas GitHub</h3>
                        <span class="bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full border border-indigo-200/60 dark:border-indigo-800/60">
                            Live Sinkronisasi
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-700">
                            @forelse($project->githubActivities as $activity)
                                <div class="relative">
                                    <div class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-indigo-600 border-2 border-white dark:border-slate-800"></div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-xs text-slate-800 dark:text-slate-100">{{ $activity->user->name ?? 'Kontributor' }}</span>
                                            <span class="text-[10px] font-mono bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded">
                                                {{ substr($activity->commit_sha, 0, 7) }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 ml-auto">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-0 font-normal pl-2 border-l-2 border-indigo-500/20 italic">
                                            "{{ $activity->commit_message }}"
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-slate-400 dark:text-slate-500 text-xs mb-0">Belum ada riwayat aktivitas commit yang tercatat.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Sidebar (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
 
                <!-- Squad Members -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 overflow-hidden">
                    <div class="border-b border-slate-100 dark:border-slate-700/60 p-5 flex justify-between items-center">
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm mb-0">Anggota Squad</h3>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700/50 px-2 py-0.5 rounded-full">
                            {{ $project->members->count() }} Orang
                        </span>
                    </div>
                    <div class="p-5">
                        <div class="space-y-3">
                            @foreach($project->members as $member)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                            class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700" alt="{{ $member->name }}">
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-slate-100 text-xs mb-0.5">{{ $member->name }}</h4>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">{{ $member->pivot->role ?? 'Member' }}</span>
                                        </div>
                                    </div>
                                    @if($member->pivot->is_validated)
                                        <span class="text-indigo-600 dark:text-indigo-400" title="Kontributor Terverifikasi">
                                            <i class="bi bi-patch-check-fill text-lg"></i>
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
 
                        @if(auth()->id() == $project->owner_id)
                            <button @click="showInviteModal = true"
                                class="w-full mt-4 py-2.5 px-4 rounded-xl border border-dashed border-indigo-300 dark:border-indigo-700/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 transition-all flex items-center justify-center gap-1.5 cursor-pointer bg-transparent">
                                <i class="bi bi-person-plus"></i>
                                <span>Undang Talenta Mahasiswa</span>
                            </button>
                        @endif
                    </div>
                </div>
 
                <!-- Collaboration Stats -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-slate-200/80 dark:border-slate-700/80 p-5">
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm mb-4">Statistik Kolaborasi</h3>
                    <div class="space-y-3">
                        <!-- Commits -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 flex items-center justify-between border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                    <i class="bi bi-git"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-500 dark:text-slate-400">Total Commits</span>
                            </div>
                            <span class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $project->githubActivities->count() }}</span>
                        </div>
                        
                        <!-- Health -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 flex items-center justify-between border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-500 dark:text-slate-400">Status Proyek</span>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-200/50 dark:border-emerald-800/50">Optimal</span>
                        </div>
 
                        <!-- Active Days -->
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 flex items-center justify-between border border-slate-100 dark:border-slate-700/50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-500 dark:text-slate-400">Durasi Aktif</span>
                            </div>
                            <span class="text-sm font-black text-slate-900 dark:text-slate-100">{{ (int) ($project->created_at->diffInDays() + 1) }} Hari</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
 
        <!-- Invite Modal (Alpine.js) -->
        <div x-show="showInviteModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;" x-transition>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl max-w-md w-full shadow-2xl p-6" @click.outside="showInviteModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50 mb-4">
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-0">Undang Talenta ke Squad</h3>
                    <button @click="showInviteModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 border-0 bg-transparent cursor-pointer">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ route('projects.members.add', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Email Mahasiswa</label>
                        <input type="email" name="email" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" placeholder="rekan@kampus.ac.id" required>
                    </div>
                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Peran di Tim</label>
                        <select name="role" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
                            <option value="member">Member</option>
                            <option value="collaborator">Collaborator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer shadow-sm">
                            Kirim Undangan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
        </div>
    </div>
</x-app-layout>