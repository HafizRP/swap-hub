<div class="h-100 d-flex flex-column position-relative">
    
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-kanban"></i> Board
        </h5>
        <button wire:click="$set('showCreateModal', true)" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">New Task</span>
        </button>
    </div>

    <div class="row g-3 flex-grow-1 overflow-hidden px-2 pb-2" style="min-height: 0;">
        @php
            $statuses = [
                'todo' => ['label' => 'To Do', 'color' => 'secondary'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'primary'],
                'review' => ['label' => 'Review', 'color' => 'warning'],
                'done' => ['label' => 'Done', 'color' => 'success']
            ];
        @endphp

        @foreach($statuses as $key => $status)
            <div class="col-12 col-md-6 col-lg-3 h-100">
                <div class="bg-body-secondary rounded-4 p-3 h-100 d-flex flex-column border border-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-uppercase small text-body-secondary mb-0 tracking-wide">{{ $status['label'] }}</h6>
                        <span class="badge bg-white text-dark border shadow-sm rounded-pill">{{ $tasks->get($key)?->count() ?? 0 }}</span>
                    </div>

                    <div class="d-flex flex-column gap-2 flex-grow-1 overflow-y-auto custom-scrollbar pe-1" style="min-height: 0;">
                        @forelse($tasks->get($key) ?? [] as $task)
                            <div class="card border-0 shadow-sm task-card hover-lift">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge rounded-pill bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }} bg-opacity-10 text-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'dark' : 'info') }} small" style="font-size: 10px;">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        
                                        <div class="dropdown">
                                            <button class="btn btn-link btn-sm p-0 text-muted" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                <li><h6 class="dropdown-header">Move To</h6></li>
                                                @foreach($statuses as $sKey => $sVal)
                                                    @if($sKey !== $key)
                                                        <li><button class="dropdown-item small" wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">{{ $sVal['label'] }}</button></li>
                                                    @endif
                                                @endforeach
                                                <li><hr class="dropdown-divider"></li>
                                                <li><button class="dropdown-item text-danger small" wire:click="deleteTask({{ $task->id }})">Delete</button></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <h6 class="fw-bold mb-1 text-truncate" title="{{ $task->title }}">{{ $task->title }}</h6>
                                    @if($task->description)
                                        <p class="small text-secondary mb-3 text-truncate">{{ $task->description }}</p>
                                    @else
                                        <div class="mb-3"></div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-end pt-2 border-top border-light">
                                        <div class="d-flex align-items-center gap-1" title="Assignee: {{ $task->assignee->name ?? 'Unassigned' }}">
                                            @if($task->assignee)
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=random" class="rounded-circle" width="24" height="24">
                                            @else
                                                <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary" style="width: 24px; height: 24px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                            @if($task->due_date)
                                                <span class="small text-{{ $task->due_date->isPast() ? 'danger' : 'secondary' }} ms-1" style="font-size: 10px;">
                                                    <i class="bi bi-calendar"></i> {{ $task->due_date->format('M d') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 opacity-25">
                                <i class="bi bi-clipboard fs-1"></i>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Task Modal Overlay -->
    @if($showCreateModal)
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center" style="z-index: 1050; backdrop-filter: blur(2px);">
            <div class="card border-0 shadow-lg rounded-4" style="width: 90%; max-width: 500px;">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Create New Task</h5>
                    <button wire:click="$set('showCreateModal', false)" class="btn-close"></button>
                </div>
                <div class="card-body px-4 pb-4">
                    <form wire:submit.prevent="createTask">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Task Title</label>
                            <input type="text" wire:model="title" class="form-control" placeholder="What needs to be done?">
                            @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Description</label>
                            <textarea wire:model="description" class="form-control" rows="3" placeholder="Add details..."></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Assign To</label>
                                <select wire:model="assigned_to" class="form-select">
                                    <option value="">Unassigned</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Priority</label>
                                <select wire:model="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary">Due Date</label>
                                <input type="date" wire:model="due_date" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="btn btn-light rounded-pill px-4">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
