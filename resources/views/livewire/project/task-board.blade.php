<div class="h-full flex flex-col relative" x-data="{ openDropdownId: null }">
    
    <div class="flex justify-between items-center mb-6 px-3 pt-2">
        <h5 class="mb-0 font-bold flex items-center gap-2 text-slate-800 dark:text-slate-100 text-base">
            <i class="bi bi-kanban text-indigo-600 dark:text-indigo-400"></i> Board
        </h5>
        
        <div class="flex items-center gap-3">
            <!-- View Toggle Segmented Control -->
            <div class="bg-slate-100 dark:bg-slate-700/50 p-1 rounded-lg inline-flex border border-slate-200 dark:border-slate-700 shadow-sm">
                <button wire:click="$set('viewType', 'board')" 
                    class="px-4 py-1.5 rounded-md text-xs transition-all duration-150 flex items-center gap-2 border-0 outline-none {{ $viewType === 'board' ? 'bg-white dark:bg-slate-800 shadow-sm text-indigo-650 dark:text-indigo-455 font-bold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                    <i class="bi bi-grid-fill"></i> <span>Board</span>
                </button>
                <button wire:click="$set('viewType', 'list')" 
                    class="px-4 py-1.5 rounded-md text-xs transition-all duration-150 flex items-center gap-2 border-0 outline-none {{ $viewType === 'list' ? 'bg-white dark:bg-slate-800 shadow-sm text-indigo-655 dark:text-indigo-455 font-bold' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400' }}">
                    <i class="bi bi-list-ul"></i> <span>List</span>
                </button>
            </div>
 
            <button wire:click="$set('showCreateModal', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-full text-xs transition-colors flex items-center gap-2 shadow border-0">
                <i class="bi bi-plus-lg"></i> <span>New Task</span>
            </button>
        </div>
    </div>
 
    <div class="flex-grow overflow-hidden px-2 pb-2" style="min-height: 0;">
        @php
            $statuses = [
                'todo' => ['label' => 'To Do', 'color' => 'bg-slate-500', 'text' => 'text-slate-600 dark:text-slate-400', 'badgeBg' => 'bg-slate-500/10'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'bg-indigo-600', 'text' => 'text-indigo-600 dark:text-indigo-400', 'badgeBg' => 'bg-indigo-500/10'],
                'review' => ['label' => 'Review', 'color' => 'bg-amber-500', 'text' => 'text-amber-600 dark:text-amber-400', 'badgeBg' => 'bg-amber-500/10'],
                'done' => ['label' => 'Done', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'badgeBg' => 'bg-emerald-500/10']
            ];
        @endphp
 
        @if($viewType === 'board')
            <div class="flex gap-4 h-full flex-nowrap overflow-x-auto pb-4 custom-scrollbar" style="scroll-behavior: smooth;">
                @foreach($statuses as $key => $status)
                    <div class="shrink-0 h-full w-[300px] md:w-[320px]">
                        <div class="bg-slate-50 dark:bg-slate-800/40 rounded-2xl p-4 h-full flex flex-col border border-slate-200 dark:border-slate-700/60 shadow-sm">
                            <div class="flex justify-between items-center mb-4 px-1 shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block rounded-full w-2.5 h-2.5 {{ $status['color'] }}"></span>
                                    <h6 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-100 mb-0">{{ $status['label'] }}</h6>
                                </div>
                                <span class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-350 text-[10px] font-bold rounded-full px-2.5 py-0.5 border border-slate-200 dark:border-slate-700 shadow-sm">{{ $tasks->get($key)?->count() ?? 0 }}</span>
                            </div>
        
                            <div class="flex flex-col gap-3 flex-grow overflow-y-auto custom-scrollbar pr-1" style="min-height: 0;">
                                @forelse($tasks->get($key) ?? [] as $task)
                                    @php
                                        $priorityStyles = $task->priority === 'high' 
                                            ? ['border' => 'border-l-red-500', 'badge' => 'bg-red-500/10 text-red-600 dark:text-red-400'] 
                                            : ($task->priority === 'medium' ? ['border' => 'border-l-amber-500', 'badge' => 'bg-amber-500/10 text-amber-600 dark:text-amber-450'] : ['border' => 'border-l-sky-500', 'badge' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400']);
                                    @endphp
                                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700/80 hover-lift transition-all border-l-4 {{ $priorityStyles['border'] }}">
                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2.5">
                                                <span class="rounded-full text-[10px] font-bold {{ $priorityStyles['badge'] }} px-2 py-0.5 uppercase">
                                                    {{ $task->priority }}
                                                </span>
                                                
                                                <!-- Dropdown menu (Alpine.js) -->
                                                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                                                    <button @click.stop="dropdownOpen = !dropdownOpen" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 bg-transparent border-0 p-0 outline-none cursor-pointer">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div x-show="dropdownOpen" 
                                                         x-transition
                                                         class="absolute right-0 mt-1 w-44 rounded-lg shadow-lg py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 z-[1050]" 
                                                         style="display: none;">
                                                        <h6 class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700/50 mb-1">Move To</h6>
                                                        @foreach($statuses as $sKey => $sVal)
                                                            @if($sKey !== $key)
                                                                <button class="w-full text-left px-4 py-1.5 text-xs text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-0 bg-transparent" 
                                                                        wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">
                                                                    {{ $sVal['label'] }}
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                        <div class="border-t border-slate-100 dark:border-slate-700/50 my-1"></div>
                                                        <button class="w-full text-left px-4 py-1.5 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 border-0 bg-transparent" 
                                                                wire:click="deleteTask({{ $task->id }})" wire:confirm="Are you sure you want to delete this task?">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <h6 class="font-bold text-sm text-slate-800 dark:text-slate-100 mb-1 truncate" title="{{ $task->title }}">{{ $task->title }}</h6>
                                            @if($task->description)
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 truncate">{{ $task->description }}</p>
                                            @else
                                                <div class="mb-3"></div>
                                            @endif
        
                                            <div class="flex justify-between items-center pt-2 border-t border-slate-100 dark:border-slate-700/50">
                                                <div class="flex items-center gap-2" title="Assignee: {{ $task->assignee->name ?? 'Unassigned' }}">
                                                    @if($task->assignee)
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=random" class="rounded-circle" width="24" height="24">
                                                    @else
                                                        <div class="rounded-circle bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs" style="width: 24px; height: 24px;">
                                                            <i class="bi bi-person"></i>
                                                        </div>
                                                    @endif
                                                    @if($task->due_date)
                                                        <span class="text-[10px] font-medium {{ $task->due_date->isPast() ? 'text-red-500 font-bold' : 'text-slate-400 dark:text-slate-500' }} ml-1">
                                                            <i class="bi bi-calendar"></i> {{ $task->due_date->format('M d') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <!-- Empty state -->
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="bg-white dark:bg-slate-800 border border-slate-205 border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden h-full flex flex-col">
                <div class="overflow-auto custom-scrollbar flex-grow">
                    <table class="w-full text-left border-collapse align-middle text-nowrap">
                        <thead class="bg-slate-50 dark:bg-slate-700/30 sticky top-0 z-1 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0" style="width: 40%">Task</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Status</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Priority</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Assignee</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Due Date</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 dark:text-slate-450 uppercase border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @php $allTasks = $tasks->flatten(); @endphp
                            @forelse($allTasks as $task)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-750/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-sm text-slate-850 dark:text-slate-100">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="text-xs text-slate-450 dark:text-slate-400 truncate max-w-[300px] mt-0.5">{{ $task->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="relative" x-data="{ openListDropdown: false }" @click.outside="openListDropdown = false">
                                             <button @click.stop="openListDropdown = !openListDropdown" class="rounded-full px-3 py-1 text-xs font-semibold border-0 {{ $statuses[$task->status]['badgeBg'] }} {{ $statuses[$task->status]['text'] }} flex items-center gap-1.5">
                                                {{ $statuses[$task->status]['label'] ?? ucfirst($task->status) }} <i class="bi bi-chevron-down text-[10px]"></i>
                                             </button>
                                             <div x-show="openListDropdown" 
                                                  x-transition
                                                  class="absolute left-0 mt-1 w-40 rounded-lg shadow-lg py-1 bg-white dark:bg-slate-800 border border-slate-205 dark:border-slate-700 z-[1050]" 
                                                  style="display: none;">
                                                @foreach($statuses as $sKey => $sVal)
                                                    <button class="w-full text-left px-4 py-1.5 text-xs text-slate-750 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-0 bg-transparent" 
                                                            wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">
                                                        {{ $sVal['label'] }}
                                                    </button>
                                                @endforeach
                                             </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $priorityBadge = $task->priority === 'high' 
                                                ? 'bg-red-500/10 text-red-600' 
                                                : ($task->priority === 'medium' ? 'bg-amber-500/10 text-amber-650' : 'bg-sky-500/10 text-sky-600');
                                        @endphp
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $priorityBadge }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=random" class="rounded-circle shrink-0" width="24" height="24">
                                                <span class="text-xs text-slate-800 dark:text-slate-200">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->due_date)
                                            <span class="text-xs {{ $task->due_date->isPast() ? 'text-red-505 text-red-500 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                                                {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-slate-400 hover:text-red-500 bg-transparent border-0 p-0 transition-colors cursor-pointer" wire:click="deleteTask({{ $task->id }})" wire:confirm="Delete this task?">
                                            <i class="bi bi-trash text-base"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 opacity-50">
                                        <i class="bi bi-clipboard text-4xl mb-2 block text-slate-400"></i>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-0">No tasks found</p>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-slate-900/60 backdrop-blur-sm" x-transition>
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden">
                <div class="p-5 flex justify-between items-center border-b border-slate-100 dark:border-slate-700/50">
                    <h5 class="font-bold text-slate-850 dark:text-slate-100 text-base">Create New Task</h5>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-655 dark:hover:text-slate-300 bg-transparent border-0 p-0 outline-none">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="p-5">
                    <form wire:submit.prevent="createTask">
                        <div class="mb-4">
                            <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Task Title</label>
                            <input type="text" wire:model="title" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-450 focus:outline-none focus:border-indigo-500" placeholder="What needs to be done?">
                            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Description</label>
                            <textarea wire:model="description" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-405 focus:outline-none focus:border-indigo-500" rows="3" placeholder="Add details..."></textarea>
                        </div>
 
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="col-span-1">
                                <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Assign To</label>
                                <select wire:model="assigned_to" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-2.5 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                    <option value="">Unassigned</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Priority</label>
                                <select wire:model="priority" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-2.5 py-2 text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block font-bold text-slate-500 dark:text-slate-400 text-xs mb-2 uppercase">Due Date</label>
                                <input type="date" wire:model="due_date" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 text-sm text-slate-850 dark:text-slate-100 focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
 
                        <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-700/50">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="bg-slate-105 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-250 font-bold py-2 px-5 rounded-full text-xs transition-colors border-0">Cancel</button>
                            <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded-full text-xs transition-colors border-0 shadow">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
