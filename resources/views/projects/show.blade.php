@section('title', $project->title)
<x-app-layout>
    <div x-data="{ showInviteModal: false }" class="space-y-6">

        <!-- Top Navigation & Action Banner -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('projects.index') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 text-xs font-bold transition-colors">
                            <i class="bi bi-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300">
                            {{ $project->category ?? 'General' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $project->title }}
                    </h1>
                </div>

                <!-- Squad Action Buttons -->
                <div class="flex flex-wrap items-center gap-2.5">
                    @if(auth()->id() == $project->owner_id)
                        <a href="{{ route('projects.edit', $project) }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all shadow-sm">
                            <i class="bi bi-pencil mr-1"></i> Edit Proyek
                        </a>
                        @if($project->conversation)
                            <a href="{{ route('chat', $project->conversation) }}"
                               class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 active:scale-[0.98] text-white text-xs font-bold transition-all shadow-md shadow-brand-500/20 flex items-center gap-1.5">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span>Buka Chat Tim</span>
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
                                   class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-md shadow-brand-500/20 flex items-center gap-1.5">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <span>Buka Chat Tim</span>
                                </a>
                            @endif
                        @elseif($status === 'pending')
                            <div class="px-4 py-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 text-xs font-bold flex items-center gap-2">
                                <i class="bi bi-hourglass-split"></i>
                                <span>Permintaan Menunggu Persetujuan</span>
                            </div>
                        @else
                            <form action="{{ route('projects.members.add', $project) }}" method="POST">
                                @csrf
                                <input type="hidden" name="role" value="member">
                                <button type="submit"
                                        class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-md shadow-brand-500/20 flex items-center gap-1.5">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Ajukan Diri Bergabung</span>
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content & Sidebar Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

            <!-- Main Overview Column (8 cols) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Description Card -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800">
                        Deskripsi & Ringkasan Proyek
                    </h2>
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3">
                        {!! nl2br(e($project->description)) !!}
                    </div>

                    @if($project->github_repo_url)
                        <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white dark:bg-slate-700 flex items-center justify-center text-xl shrink-0">
                                    <i class="bi bi-github"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">
                                        {{ $project->github_repo_name ?? 'GitHub Repository Terhubung' }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400">Sinkronisasi aktivitas commit & reputasi aktif</p>
                                </div>
                            </div>
                            <a href="{{ $project->github_repo_url }}" target="_blank"
                               class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 transition-colors shrink-0">
                                <span>Buka Repository</span>
                                <i class="bi bi-box-arrow-up-right text-[10px]"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Live GitHub Feed Card -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-activity text-brand-500"></i>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Aktivitas GitHub Tim</h2>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 dark:bg-brand-950/40 dark:text-brand-300 uppercase">
                            Webhook Feed
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($project->githubActivities as $activity)
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80 space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-200">
                                        <i class="bi bi-git text-brand-500"></i>
                                        <span>{{ $activity->user->name }}</span>
                                        <span class="font-mono text-[10px] px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                            {{ substr($activity->commit_sha, 0, 7) }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-slate-400">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 pl-4 border-l-2 border-brand-500/40 italic">
                                    "{{ $activity->commit_message }}"
                                </p>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 text-xs">
                                Belum ada aktivitas commit pada repository ini.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column: Squad Roster & Stats (4 cols) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Squad Members Card -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-people-fill text-brand-500"></i>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Anggota Squad ({{ $project->members->count() }})</h3>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @foreach($project->members as $member)
                            <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $member->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=6366f1&color=fff' }}"
                                         class="w-9 h-9 rounded-xl object-cover shrink-0"
                                         alt="{{ $member->name }}">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate mb-0">{{ $member->name }}</p>
                                        <span class="text-[10px] text-slate-400 font-semibold uppercase">{{ $member->pivot->role }}</span>
                                    </div>
                                </div>

                                @if($member->pivot->is_validated)
                                    <span class="text-emerald-500" title="Kontributor Terverifikasi">
                                        <i class="bi bi-patch-check-fill text-base"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(auth()->id() == $project->owner_id)
                        <button @click="showInviteModal = true"
                                class="w-full mt-2 py-2.5 px-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-plus-lg"></i>
                            <span>Undang Anggota Tim</span>
                        </button>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800">
                        Statistik Proyek
                    </h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Total Commit</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $project->githubActivities->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Status Kesehatan</span>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Aktif</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Durasi Berjalan</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ (int) ($project->created_at->diffInDays() + 1) }} hari</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Invite Modal (Alpine.js) -->
        <div x-show="showInviteModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
             x-transition>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl p-6 space-y-4"
                 @click.outside="showInviteModal = false">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h4 class="text-base font-bold text-slate-900 dark:text-white">Undang Rekan ke Squad</h4>
                    <button @click="showInviteModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form action="{{ route('projects.members.add', $project) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Mahasiswa</label>
                        <input type="email" name="email" required placeholder="nama@university.ac.id"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Peran / Role</label>
                        <select name="role" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                            <option value="collaborator">Collaborator</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                            Kirim Undangan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
