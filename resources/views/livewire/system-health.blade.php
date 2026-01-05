<div wire:poll.5s>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-secondary mb-0">Real-time health check of application services</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span wire:loading class="text-primary small">
                <i class="bi bi-arrow-clockwise spin me-1"></i>Updating...
            </span>
            <button wire:click="$refresh" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-clockwise" wire:loading.class="spin"></i> Refresh
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Web Application -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        @if($webStatus == 'OK')
                            <div class="bg-success bg-opacity-20 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-check-circle-fill fs-1"></i>
                            </div>
                        @else
                            <div class="bg-danger bg-opacity-20 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-x-circle-fill fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold text-white mb-2">Web Application</h5>
                    <p class="text-secondary small mb-4">Core Application Framework</p>

                    <div class="bg-dark bg-opacity-50 rounded-3 p-3 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Status:</span>
                            <span class="fw-bold {{ $webStatus == 'OK' ? 'text-success' : 'text-danger' }}">
                                {{ $webStatus }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary small">URL:</span>
                            <span class="fw-bold text-white text-truncate" style="max-width: 150px;">
                                {{ $appUrl }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        @if($dbStatus == 'OK')
                            <div class="bg-success bg-opacity-20 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-database-fill-check fs-1"></i>
                            </div>
                        @else
                            <div class="bg-danger bg-opacity-20 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-database-fill-x fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold text-white mb-2">Database</h5>
                    <p class="text-secondary small mb-4">Primary Data Storage</p>

                    <div class="bg-dark bg-opacity-50 rounded-3 p-3 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Connection:</span>
                            <span class="fw-bold {{ $dbStatus == 'OK' ? 'text-success' : 'text-danger' }}">
                                {{ $dbStatus == 'OK' ? 'Connected' : 'Failed' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Latency:</span>
                            <span class="fw-bold text-white">{{ $dbLatency }} ms</span>
                        </div>
                        @if($dbStatus != 'OK')
                            <div class="mt-2 p-2 bg-danger bg-opacity-10 rounded text-danger small">
                                {{ $dbStatus }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-time Service -->
        <div class="col-md-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-4">
                        @if($pusherStatus == 'OK')
                            <div class="bg-info bg-opacity-20 text-info rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-broadcast fs-1"></i>
                            </div>
                        @else
                            <div class="bg-warning bg-opacity-20 text-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="fw-bold text-white mb-2">Real-time Service</h5>
                    <p class="text-secondary small mb-4">Reverb / Pusher WebSocket</p>

                    <div class="bg-dark bg-opacity-50 rounded-3 p-3 text-start">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Service:</span>
                            <span class="fw-bold {{ $pusherStatus == 'OK' ? 'text-success' : 'text-warning' }}">
                                {{ $pusherStatus == 'OK' ? 'Reachable' : 'Unreachable' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Host:</span>
                            <span class="fw-bold text-white">{{ $host }}:{{ $port }}</span>
                        </div>
                        @if($pusherError)
                            <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded text-warning small text-break">
                                {{ $pusherError }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <small class="text-secondary">
            <i class="bi bi-clock me-1"></i>
            Last checked: {{ now()->format('M d, Y H:i:s') }}
        </small>
    </div>

    <style>
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin 1s linear infinite;
        }
    </style>
</div>