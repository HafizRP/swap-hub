@section('title', 'System Status')
<div wire:poll.5s>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">System Status</h2>
                <p class="text-secondary mb-0">Real-time health check of application services.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span wire:loading class="text-primary small me-2">Updating...</span>
                <button wire:click="$refresh"
                    class="btn btn-outline-primary rounded-pill btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-clockwise" wire:loading.class="spin"></i> Refresh
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Web Application -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($webStatus == 'OK')
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-check-lg fs-2"></i>
                                </div>
                            @else
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-x-lg fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="fw-bold">Web Application</h5>
                        <p class="text-secondary small mb-3">Core Application Framework</p>

                        <div class="bg-light rounded p-2 small text-start">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Status:</span>
                                <span
                                    class="fw-bold {{ $webStatus == 'OK' ? 'text-success' : 'text-danger' }}">{{ $webStatus }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">URL:</span>
                                <span class="fw-bold text-dark text-truncate"
                                    style="max-width: 120px;">{{ $appUrl }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($dbStatus == 'OK')
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-database-check fs-2"></i>
                                </div>
                            @else
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-database-x fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="fw-bold">Database</h5>
                        <p class="text-secondary small mb-3">Primary Data Storage</p>

                        <div class="bg-light rounded p-2 small text-start">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Connection:</span>
                                <span
                                    class="fw-bold {{ $dbStatus == 'OK' ? 'text-success' : 'text-danger' }}">{{ $dbStatus == 'OK' ? 'Connected' : 'Failed' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Latency:</span>
                                <span class="fw-bold text-dark">{{ $dbLatency }} ms</span>
                            </div>
                            @if($dbStatus != 'OK')
                                <div class="mt-2 text-danger x-small">{{ $dbStatus }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Real-time Service -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 p-3">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($pusherStatus == 'OK')
                                <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-broadcast fs-2"></i>
                                </div>
                            @else
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-exclamation-lg fs-2"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="fw-bold">Real-time Service</h5>
                        <p class="text-secondary small mb-3">Reverb / Pusher WebSocket</p>

                        <div class="bg-light rounded p-2 small text-start">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Service:</span>
                                <span
                                    class="fw-bold {{ $pusherStatus == 'OK' ? 'text-success' : 'text-warning' }}">{{ $pusherStatus == 'OK' ? 'Reachable' : 'Unreachable' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Host:</span>
                                <span class="fw-bold text-dark">{{ $host }}:{{ $port }}</span>
                            </div>
                            @if($pusherError)
                                <div class="mt-2 text-danger x-small text-break">{{ $pusherError }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center">
            <small class="text-secondary">Last checked: {{ now()->format('M d, Y H:i:s') }}</small>
        </div>
    </div>
</div>