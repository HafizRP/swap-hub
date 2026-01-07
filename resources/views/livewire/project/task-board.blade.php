<div class="h-100 d-flex flex-column position-relative">
    
    <div class="d-flex justify-content-between align-items-center mb-4 px-3 pt-2">
        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-body">
            <i class="bi bi-kanban text-primary"></i> Board
        </h5>
        
        <div class="d-flex align-items-center gap-3">
            <!-- View Toggle Segmented Control -->
            <div class="bg-body-tertiary p-1 rounded-3 d-inline-flex border border-secondary-subtle">
                <button wire:click="$set('viewType', 'board')" 
                    class="btn btn-sm border-0 rounded-2 d-flex align-items-center gap-2 {{ $viewType === 'board' ? 'bg-body shadow-sm text-primary fw-bold' : 'text-secondary opacity-75 hover-opacity-100' }}"
                    style="transition: all 0.2s ease; min-width: 80px; justify-content: center;">
                    <i class="bi bi-grid-fill"></i> <span class="small">Board</span>
                </button>
                <button wire:click="$set('viewType', 'list')" 
                    class="btn btn-sm border-0 rounded-2 d-flex align-items-center gap-2 {{ $viewType === 'list' ? 'bg-body shadow-sm text-primary fw-bold' : 'text-secondary opacity-75 hover-opacity-100' }}"
                    style="transition: all 0.2s ease; min-width: 80px; justify-content: center;">
                    <i class="bi bi-list-ul"></i> <span class="small">List</span>
                </button>
            </div>

            <button wire:click="$set('showCreateModal', true)" class="btn btn-primary btn-sm d-flex align-items-center gap-2 shadow-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">New Task</span>
            </button>
        </div>
    </div>

    <div class="flex-grow-1 overflow-hidden px-2 pb-2" style="min-height: 0;">
        @php
            $statuses = [
                'todo' => ['label' => 'To Do', 'color' => 'secondary'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'primary'],
                'review' => ['label' => 'Review', 'color' => 'warning'],
                'done' => ['label' => 'Done', 'color' => 'success']
            ];
        @endphp

        @if($viewType === 'board')
            <div class="row g-3 h-100 flex-nowrap overflow-x-auto pb-2" style="scrollbar-width: thin; scroll-behavior: smooth;">
                @foreach($statuses as $key => $status)
                    <div class="col-12 col-md-6 col-lg-3 h-100" style="min-width: 320px;">
                        <div class="bg-body-tertiary rounded-4 p-3 h-100 d-flex flex-column border border-secondary-subtle shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-block rounded-circle bg-{{ $status['color'] }}" style="width: 8px; height: 8px;"></span>
                                    <h6 class="fw-bold text-uppercase small text-body mb-0 tracking-wide">{{ $status['label'] }}</h6>
                                </div>
                                <span class="badge bg-body text-body border border-secondary-subtle shadow-sm rounded-pill">{{ $tasks->get($key)?->count() ?? 0 }}</span>
                            </div>
        
                            <div class="d-flex flex-column gap-2 flex-grow-1 overflow-y-auto custom-scrollbar pe-1" style="min-height: 0;">
                                @forelse($tasks->get($key) ?? [] as $task)
                                    @php
                                        $priorityColor = $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info');
                                    @endphp
                                    <div class="card border-0 shadow-sm task-card hover-lift border-start border-3 border-{{ $priorityColor }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge rounded-pill bg-{{ $priorityColor }} bg-opacity-10 text-{{ $priorityColor }} small fw-bold" style="font-size: 10px;">
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
                                                        <li><button class="dropdown-item text-danger small" wire:click="deleteTask({{ $task->id }})" wire:confirm="Are you sure you want to delete this task?">Delete</button></li>
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
                                    <!-- Empty state per column handled by flex layout usually, or we can leave empty -->
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="card border-0 shadow-sm h-100 overflow-hidden rounded-4">
                <div class="card-body p-0 overflow-auto custom-scrollbar">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-body-secondary sticky-top z-1">
                            <tr>
                                <th class="ps-4 text-secondary small text-uppercase" style="width: 40%">Task</th>
                                <th class="text-secondary small text-uppercase">Status</th>
                                <th class="text-secondary small text-uppercase">Priority</th>
                                <th class="text-secondary small text-uppercase">Assignee</th>
                                <th class="text-secondary small text-uppercase">Due Date</th>
                                <th class="text-end pe-4 text-secondary small text-uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $allTasks = $tasks->flatten(); @endphp
                            @forelse($allTasks as $task)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-body">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="small text-secondary text-truncate" style="max-width: 300px;">{{ $task->description }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                             <button class="btn btn-sm badge bg-{{ $statuses[$task->status]['color'] ?? 'secondary' }} bg-opacity-10 text-{{ $statuses[$task->status]['color'] ?? 'secondary' }} border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                {{ $statuses[$task->status]['label'] ?? ucfirst($task->status) }}
                                             </button>
                                             <ul class="dropdown-menu shadow-sm border-0">
                                                @foreach($statuses as $sKey => $sVal)
                                                    <li><button class="dropdown-item small" wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">{{ $sVal['label'] }}</button></li>
                                                @endforeach
                                             </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }} bg-opacity-10 text-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'dark' : 'info') }}" style="font-size: 11px;">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->assignee)
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=random" class="rounded-circle" width="24" height="24">
                                                <span class="small">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="small text-secondary font-italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->due_date)
                                            <span class="small text-{{ $task->due_date->isPast() ? 'danger' : 'secondary' }}">
                                                {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="small text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-link btn-sm text-danger p-0" wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 opacity-50">
                                        <i class="bi bi-clipboard fs-1 mb-2"></i>
                                        <p class="small mb-0">No tasks found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Task Modal Overlay -->
    @if($showCreateModal)
        <div class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center p-3" style="z-index: 2000; backdrop-filter: blur(4px);">
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
