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
                <div class="mb-5 relative">
                    <!-- Dot -->
                    <div class="absolute left-0 top-0 translate-middle-x bg-white dark:bg-slate-800 rounded-full flex items-center justify-center shrink-0 shadow-sm"
                        style="left: -21px; width: 32px; height: 32px; border: 4px solid var(--bs-body-bg) !important;">
                        @if($activity->type === 'push')
                            <i class="bi bi-code-square text-indigo-600 dark:text-indigo-400 text-xs"></i>
                        @elseif($activity->type === 'pull_request')
                            <i class="bi bi-git text-amber-500 text-xs"></i>
                        @elseif($activity->type === 'issues')
                            <i class="bi bi-record-circle text-red-500 text-xs"></i>
                        @else
                            <i class="bi bi-activity text-slate-400 text-xs"></i>
                        @endif
                    </div>
 
                    <div class="bg-slate-50 dark:bg-slate-700/40 border border-slate-205 border-slate-200 dark:border-slate-700 rounded-xl shadow-sm ml-4">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <div>
                                    <span class="inline-block bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider mb-1">
                                        {{ str_replace('_', ' ', strtoupper($activity->type)) }}
                                    </span>
                                    @php
                                        $payload = is_string($activity->payload) ? json_decode($activity->payload, true) : $activity->payload;
                                        $actor = $payload['sender']['login'] ?? 'Unknown';
                                        $message = '';
 
                                        if ($activity->type === 'push') {
                                            $count = count($payload['commits'] ?? []);
                                            $message = "pushed $count commits";
                                        } elseif ($activity->type === 'pull_request') {
                                            $action = $payload['action'] ?? 'opened';
                                            $message = "$action pull request";
                                        }
                                    @endphp
                                    <h6 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-0">{{ $actor }} {{ $message }}</h6>
                                </div>
                                <small class="text-slate-400 dark:text-slate-500 text-[10px] shrink-0">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </small>
                            </div>
 
                            @if($activity->type === 'push' && isset($payload['commits']))
                                <ul class="list-none mb-0 mt-3 bg-white dark:bg-slate-800 rounded-lg p-3 border border-slate-200 dark:border-slate-700">
                                    @foreach(array_slice($payload['commits'], 0, 3) as $commit)
                                        <li class="flex items-start gap-2 mb-2 last:mb-0">
                                            <i class="bi bi-git text-slate-400 text-xs mt-0.5"></i>
                                            <div class="text-xs w-full min-w-0">
                                                <a href="{{ $commit['url'] ?? '#' }}" target="_blank"
                                                    class="text-slate-500 dark:text-slate-450 hover:text-indigo-650 no-underline block truncate">
                                                    {{ $commit['message'] }}
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                    @if(count($payload['commits']) > 3)
                                        <li class="text-center text-xs text-slate-400 dark:text-slate-500 italic mt-2 border-t border-slate-100 dark:border-slate-700/50 pt-2">
                                            +{{ count($payload['commits']) - 3 }} more commits
                                        </li>
                                    @endif
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