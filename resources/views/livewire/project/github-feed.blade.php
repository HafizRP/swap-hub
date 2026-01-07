<div class="h-100 p-4 overflow-auto custom-scrollbar">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-github me-2"></i>Repository Activity
        </h5>
        @if($project->github_repo_url)
            <a href="{{ $project->github_repo_url }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                View on GitHub
            </a>
        @endif
    </div>

    @if($activities->count() > 0)
        <div class="position-relative ps-3 border-start border-secondary-subtle">
            @foreach($activities as $activity)
                <div class="mb-4 position-relative">
                    <!-- Dot -->
                    <div class="position-absolute start-0 top-0 translate-middle-x bg-body rounded-circle d-flex align-items-center justify-content-center"
                        style="left: -17px; width: 32px; height: 32px; border: 4px solid var(--bs-body-bg);">
                        @if($activity->type === 'push')
                            <i class="bi bi-code-square text-primary small"></i>
                        @elseif($activity->type === 'pull_request')
                            <i class="bi bi-git text-warning small"></i>
                        @elseif($activity->type === 'issues')
                            <i class="bi bi-record-circle text-danger small"></i>
                        @else
                            <i class="bi bi-activity text-secondary small"></i>
                        @endif
                    </div>

                    <div class="card border-0 shadow-sm bg-body-tertiary ms-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 mb-1"
                                        style="font-size: 10px;">
                                        {{ str_replace('_', ' ', strtoupper($activity->type)) }}
                                    </span>
                                    @php
                                        $payload = is_string($activity->payload) ? json_decode($activity->payload, true) : $activity->payload;
                                        // Fallback rendering
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
                                    <h6 class="fw-bold mb-0 text-body">{{ $actor }} {{ $message }}</h6>
                                </div>
                                <small class="text-secondary" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </small>
                            </div>

                            @if($activity->type === 'push' && isset($payload['commits']))
                                <ul class="list-unstyled mb-0 mt-2 bg-body rounded p-2 border border-secondary-subtle">
                                    @foreach(array_slice($payload['commits'], 0, 3) as $commit)
                                        <li class="d-flex align-items-start gap-2 mb-1 last-mb-0">
                                            <i class="bi bi-git text-secondary opacity-50 small mt-1"></i>
                                            <div class="small w-100">
                                                <a href="{{ $commit['url'] ?? '#' }}" target="_blank"
                                                    class="text-secondary text-decoration-none hover-text-primary d-block text-truncate">
                                                    {{ $commit['message'] }}
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                    @if(count($payload['commits']) > 3)
                                        <li class="text-center small text-secondary fst-italic mt-1">
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
        <div class="text-center py-5 opacity-50">
            <i class="bi bi-github display-1 mb-3"></i>
            <p class="text-secondary">No activity recorded yet.</p>
            <small class="text-secondary">Connect your repository and push code to see updates here.</small>
        </div>
    @endif
</div>