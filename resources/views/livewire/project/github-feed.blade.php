<div class="space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-2">
            <i class="bi bi-github text-base text-slate-900 dark:text-white"></i>
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white">
                Aktivitas Repository GitHub
            </h3>
        </div>
        @if($project->github_repo_url)
            <a href="{{ $project->github_repo_url }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 hover:text-brand-700">
                <span>Buka di GitHub</span>
                <i class="bi bi-box-arrow-up-right text-[10px]"></i>
            </a>
        @endif
    </div>

    @if($activities->count() > 0)
        <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-800 space-y-6 ml-2 my-4">
            @foreach($activities as $activity)
                <div class="relative group">
                    <!-- Timeline Marker -->
                    <div class="absolute -left-[33px] top-1.5 w-6 h-6 rounded-full bg-white dark:bg-slate-900 border-2 border-brand-500 flex items-center justify-center shadow-sm">
                        @if($activity->type === 'push')
                            <i class="bi bi-code-slash text-[10px] text-brand-600"></i>
                        @elseif($activity->type === 'pull_request')
                            <i class="bi bi-git text-[10px] text-emerald-600"></i>
                        @else
                            <i class="bi bi-activity text-[10px] text-amber-500"></i>
                        @endif
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 space-y-2">
                        <div class="flex items-center justify-between">
                            @php
                                $payload = is_string($activity->payload) ? json_decode($activity->payload, true) : $activity->payload;
                                $actor = $payload['sender']['login'] ?? 'GitHub Member';
                                $count = count($payload['commits'] ?? []);
                            @endphp
                            <span class="text-xs font-bold text-slate-900 dark:text-white">
                                <span class="text-brand-600 dark:text-brand-400 font-black">{{ $actor }}</span>
                                @if($activity->type === 'push')
                                    mendorong {{ $count }} commit
                                @elseif($activity->type === 'pull_request')
                                    membuat Pull Request
                                @else
                                    {{ $activity->type }}
                                @endif
                            </span>
                            <span class="text-[10px] font-semibold text-slate-400">
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </span>
                        </div>

                        @if($activity->type === 'push' && isset($payload['commits']))
                            <div class="space-y-1.5 pt-2 border-t border-slate-200/50 dark:border-slate-700/50">
                                @foreach(array_slice($payload['commits'], 0, 3) as $commit)
                                    <div class="flex items-center gap-2 text-xs">
                                        <i class="bi bi-git text-slate-400 text-[10px]"></i>
                                        <span class="text-slate-600 dark:text-slate-300 font-medium truncate">{{ $commit['message'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-12 text-center text-slate-400 space-y-2">
            <i class="bi bi-github text-3xl"></i>
            <p class="text-xs font-bold">Belum ada riwayat aktivitas GitHub.</p>
            <p class="text-[11px]">Hubungkan repositori dan kirim commit untuk melihat feed aktivitas real-time.</p>
        </div>
    @endif
</div>
