<div class="h-full p-4 overflow-auto custom-scrollbar">
    <div class="flex justify-between items-center mb-6">
        <h5 class="font-bold text-slate-800 dark:text-slate-100 mb-0 text-base">
            <i class="bi bi-github mr-2"></i>Repository Activity
        </h5>
        @if($project->github_repo_url)
            <a href="{{ $project->github_repo_url }}" target="_blank" class="border border-slate-250 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-bold py-1.5 px-4 rounded-full text-xs transition-colors no-underline">
                View on GitHub
            </a>
        @endif
    </div>

    @if($activities->count() > 0)
        <div class="relative pl-5 border-l border-slate-200 dark:border-slate-700 ml-3">
            @foreach($activities as $activity)
                @php
                    $type = $activity->activity_type ?? $activity->type ?? 'commit';
                    $metadata = is_array($activity->metadata ?? null)
                        ? $activity->metadata
                        : (is_string($activity->metadata ?? null) ? json_decode($activity->metadata, true) : []);
                    $payload = is_array($activity->payload ?? null)
                        ? $activity->payload
                        : (is_string($activity->payload ?? null) ? json_decode($activity->payload, true) : $metadata);

                    $user = $activity->user ?? null;
                    $actor = $user->name ?? $payload['sender']['login'] ?? $payload['pusher']['name'] ?? 'Team Member';
                    $commitMessage = $activity->commit_message ?? $payload['commits'][0]['message'] ?? 'Commit update';
                    $branch = $activity->branch ?? str_replace('refs/heads/', '', $payload['ref'] ?? 'main');
                    $activityDate = $activity->activity_at ?? $activity->created_at ?? now();
                @endphp
                <div class="mb-5 relative">
                    <!-- Dot -->
                    <div class="absolute left-0 top-0 translate-middle-x bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shrink-0 shadow-sm"
                        style="left: -21px; width: 32px; height: 32px; border: 4px solid var(--bs-body-bg) !important;">
                        @if(in_array($type, ['commit', 'push']))
                            <i class="bi bi-code-square text-indigo-600 dark:text-indigo-400 text-xs"></i>
                        @elseif(in_array($type, ['pull_request', 'pr']))
                            <i class="bi bi-git text-amber-500 text-xs"></i>
                        @elseif(in_array($type, ['issues', 'issue']))
                            <i class="bi bi-record-circle text-red-500 text-xs"></i>
                        @else
                            <i class="bi bi-activity text-slate-400 text-xs"></i>
                        @endif
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-700/40 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm ml-4">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <div>
                                    <span class="inline-block bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider mb-1">
                                        {{ str_replace('_', ' ', strtoupper($type)) }}
                                    </span>
                                    <h6 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-0">
                                        {{ $actor }}
                                        @if(in_array($type, ['commit', 'push']))
                                            pushed to <code class="text-xs bg-slate-200 dark:bg-slate-600 px-1 py-0.5 rounded text-indigo-600 dark:text-indigo-400">{{ $branch }}</code>
                                        @elseif(in_array($type, ['pull_request', 'pr']))
                                            updated pull request
                                        @else
                                            recorded {{ $type }}
                                        @endif
                                    </h6>
                                </div>
                                <small class="text-slate-400 dark:text-slate-500 text-[10px] shrink-0">
                                    {{ \Carbon\Carbon::parse($activityDate)->diffForHumans() }}
                                </small>
                            </div>

                            @if(!empty($commitMessage))
                                <div class="mt-2.5 bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700">
                                    <div class="flex items-start gap-2">
                                        <i class="bi bi-git text-slate-400 text-xs mt-0.5"></i>
                                        <div class="text-xs w-full min-w-0">
                                            <p class="text-slate-700 dark:text-slate-300 font-medium mb-1 truncate">{{ $commitMessage }}</p>
                                            @if(!empty($activity->commit_sha))
                                                <span class="font-mono text-[10px] text-slate-400 bg-slate-100 dark:bg-slate-900 px-1.5 py-0.5 rounded">
                                                    {{ substr($activity->commit_sha, 0, 7) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($payload['commits']) && is_array($payload['commits']) && count($payload['commits']) > 1)
                                <ul class="list-none mb-0 mt-2 bg-white dark:bg-slate-800 rounded-lg p-2.5 border border-slate-200 dark:border-slate-700 text-xs">
                                    @foreach(array_slice($payload['commits'], 1, 3) as $commit)
                                        <li class="flex items-center gap-2 py-1 text-slate-600 dark:text-slate-400 truncate">
                                            <span class="font-mono text-[10px] text-slate-400">{{ substr($commit['id'] ?? '', 0, 7) }}</span>
                                            <span class="truncate">{{ $commit['message'] ?? '' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-10 opacity-50">
            <i class="bi bi-github text-5xl mb-3 block"></i>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-bold mb-1">No activity recorded yet.</p>
            <small class="text-slate-400 dark:text-slate-650 text-xs">Connect your repository and push code to see updates here.</small>
        </div>
    @endif
</div>
