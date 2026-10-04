<div class="h-full flex flex-col relative" x-data="{ openDropdownId: null }">
    
    <!-- Top Bar Controls -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-5 px-1 pt-1 shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-sm shadow-sm">
                <i class="bi bi-kanban"></i>
            </div>
            <div>
                <h2 class="text-sm font-black text-stone-900 dark:text-stone-100 mb-0">Kanban Board</h2>
                <span class="text-[10px] text-stone-400 dark:text-stone-500 font-medium">Manajemen tugas & jadwal proyek</span>
            </div>
        </div>
        
        <div class="flex items-center gap-2.5">
            <!-- View Toggle Segmented Control -->
            <div class="bg-stone-100 dark:bg-stone-800 p-1 rounded-xl inline-flex border border-stone-200/80 dark:border-stone-700">
                <button wire:click="$set('viewType', 'board')" 
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-all duration-150 flex items-center gap-1.5 border-0 outline-none cursor-pointer {{ $viewType === 'board' ? 'bg-white dark:bg-[#141414] shadow-sm text-teal-600 dark:text-teal-400 font-bold' : 'text-stone-500 hover:text-stone-700 dark:text-stone-400' }}">
                    <i class="bi bi-grid-fill text-xs"></i>
                    <span>Board</span>
                </button>
                <button wire:click="$set('viewType', 'list')" 
                    class="px-3.5 py-1.5 rounded-lg text-xs transition-all duration-150 flex items-center gap-1.5 border-0 outline-none cursor-pointer {{ $viewType === 'list' ? 'bg-white dark:bg-[#141414] shadow-sm text-teal-600 dark:text-teal-400 font-bold' : 'text-stone-500 hover:text-stone-700 dark:text-stone-400' }}">
                    <i class="bi bi-list-ul text-xs"></i>
                    <span>List</span>
                </button>
            </div>

            <button wire:click="$set('showCreateModal', true)" 
                class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-1.5 px-4 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] flex items-center gap-1.5 shadow-sm border-0 cursor-pointer">
                <i class="bi bi-plus-lg text-xs"></i>
                <span>Tugas Baru</span>
            </button>
        </div>
    </div>

    <div class="flex-grow overflow-hidden pb-1" style="min-height: 0;">
        @php
            $statuses = [
                'todo' => ['label' => 'To Do', 'color' => 'bg-stone-400 dark:bg-stone-500', 'text' => 'text-stone-600 dark:text-stone-400', 'badgeBg' => 'bg-stone-100 dark:bg-stone-800'],
                'in_progress' => ['label' => 'In Progress', 'color' => 'bg-teal-500', 'text' => 'text-teal-700 dark:text-teal-300', 'badgeBg' => 'bg-teal-50 dark:bg-teal-950/40'],
                'review' => ['label' => 'Review', 'color' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-300', 'badgeBg' => 'bg-amber-50 dark:bg-amber-950/40'],
                'done' => ['label' => 'Done', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-700 dark:text-emerald-300', 'badgeBg' => 'bg-emerald-50 dark:bg-emerald-950/40']
            ];
        @endphp

        @if($viewType === 'board')
            <!-- Kanban Columns -->
            <div class="flex gap-4 h-full flex-nowrap overflow-x-auto pb-4 custom-scrollbar" style="scroll-behavior: smooth;">
                @foreach($statuses as $key => $status)
                    <div class="shrink-0 h-full w-[290px] md:w-[310px]">
                        <div class="bg-stone-100/70 dark:bg-[#141414] rounded-xl p-4 h-full flex flex-col border border-stone-200/80 dark:border-stone-800">
                            
                            <!-- Column Header -->
                            <div class="flex justify-between items-center mb-3 px-1 shrink-0">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block rounded-full w-2.5 h-2.5 {{ $status['color'] }}"></span>
                                    <h3 class="font-extrabold text-xs uppercase tracking-wider text-stone-800 dark:text-stone-100 mb-0">{{ $status['label'] }}</h3>
                                </div>
                                <span class="bg-white dark:bg-stone-800 text-stone-700 dark:text-stone-300 text-[10px] font-bold rounded-full px-2 py-0.5 border border-stone-200 dark:border-stone-700 shadow-xs">
                                    {{ $tasks->get($key)?->count() ?? 0 }}
                                </span>
                            </div>
        
                            <!-- Cards Stream -->
                            <div class="flex flex-col gap-3 flex-grow overflow-y-auto custom-scrollbar pr-1" style="min-height: 0;">
                                @forelse($tasks->get($key) ?? [] as $task)
                                    @php
                                        $priorityStyles = $task->priority === 'high' 
                                            ? ['border' => 'border-l-red-500', 'badge' => 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border-red-200/60 dark:border-red-800/50'] 
                                            : ($task->priority === 'medium' ? ['border' => 'border-l-amber-500', 'badge' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/60 dark:border-amber-800/50'] : ['border' => 'border-l-teal-500', 'badge' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200/60 dark:border-teal-800/50']);
                                    @endphp
                                    <div wire:key="task-{{ $task->id }}" class="bg-white dark:bg-[#1a1917] rounded-lg shadow-card border border-stone-200/80 dark:border-stone-800 hover-lift transition-all border-l-4 {{ $priorityStyles['border'] }} p-3.5">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="rounded-md text-[9px] font-bold border px-1.5 py-0.5 uppercase tracking-wider {{ $priorityStyles['badge'] }}">
                                                {{ $task->priority }}
                                            </span>
                                            
                                            <!-- Move / Delete Dropdown -->
                                            <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                                                <button @click.stop="dropdownOpen = !dropdownOpen" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 bg-transparent border-0 p-0 outline-none cursor-pointer">
                                                    <i class="bi bi-three-dots-vertical text-xs"></i>
                                                </button>
                                                <div x-show="dropdownOpen" 
                                                     x-transition
                                                     class="absolute right-0 mt-1 w-40 rounded-xl shadow-lg py-1 bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 z-[1050]" 
                                                     style="display: none;">
                                                    <span class="block px-3 py-1 text-[9px] font-bold text-stone-400 uppercase tracking-wider border-b border-stone-100 dark:border-stone-700/50">Pindahkan</span>
                                                    @foreach($statuses as $sKey => $sVal)
                                                        @if($sKey !== $key)
                                                            <button class="w-full text-left px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-700/50 border-0 bg-transparent cursor-pointer" 
                                                                    wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">
                                                                {{ $sVal['label'] }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                    <div class="border-t border-stone-100 dark:border-stone-700/50 my-1"></div>
                                                    <button class="w-full text-left px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 border-0 bg-transparent cursor-pointer" 
                                                            wire:click="deleteTask({{ $task->id }})" wire:confirm="Hapus tugas ini?">
                                                        Hapus Tugas
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
    
                                        <h4 class="font-bold text-xs text-stone-900 dark:text-stone-100 mb-1 leading-snug truncate" title="{{ $task->title }}">
                                            {{ $task->title }}
                                        </h4>
                                        @if($task->description)
                                            <p class="text-[11px] text-stone-500 dark:text-stone-400 mb-3 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                                        @else
                                            <div class="mb-2"></div>
                                        @endif
    
                                        <div class="flex justify-between items-center pt-2 border-t border-stone-100 dark:border-stone-800">
                                            <div class="flex items-center gap-1.5">
                                                @if($task->assignee)
                                                    <img src="{{ $task->assignee->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($task->assignee->name) . '&background=0d9488&color=fff' }}" 
                                                         class="w-5 h-5 rounded-full object-cover border border-stone-200 dark:border-stone-700" 
                                                         title="{{ $task->assignee->name }}">
                                                    <span class="text-[11px] text-stone-600 dark:text-stone-400 font-medium truncate max-w-[80px]">{{ $task->assignee->name }}</span>
                                                @else
                                                    <span class="text-[10px] text-stone-400 italic">Unassigned</span>
                                                @endif
                                            </div>

                                            @if($task->due_date)
                                                <span class="text-[10px] font-semibold {{ $task->due_date->isPast() ? 'text-red-500 font-bold' : 'text-stone-400 dark:text-stone-500' }} flex items-center gap-1">
                                                    <i class="bi bi-calendar-event text-[9px]"></i>
                                                    {{ $task->due_date->format('d M') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <!-- Empty Column Placeholder -->
                                    <div wire:key="empty-{{ $key }}" class="h-28 rounded-lg border border-dashed border-stone-200 dark:border-stone-800 flex flex-col items-center justify-center text-center p-3">
                                        <p class="text-[11px] text-stone-400 dark:text-stone-500 mb-0 font-medium">Belum ada tugas</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- List View -->
            <div class="bg-white dark:bg-[#141414] border border-stone-200/80 dark:border-stone-800 rounded-xl shadow-card overflow-hidden h-full flex flex-col">
                <div class="overflow-auto custom-scrollbar flex-grow">
                    <table class="w-full text-left border-collapse text-nowrap">
                        <thead class="bg-stone-50 dark:bg-stone-900/60 sticky top-0 z-1 border-b border-stone-200 dark:border-stone-800">
                            <tr>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase" style="width: 40%">Tugas</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Status</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Prioritas</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Penanggung Jawab</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Tenggat</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-stone-500 dark:text-stone-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                            @php $allTasks = $tasks->flatten(); @endphp
                            @forelse($allTasks as $task)
                                <tr wire:key="task-row-{{ $task->id }}" class="hover:bg-stone-50/50 dark:hover:bg-stone-800/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-xs text-stone-900 dark:text-stone-100">{{ $task->title }}</div>
                                        @if($task->description)
                                            <div class="text-[11px] text-stone-400 dark:text-stone-500 truncate max-w-[280px] mt-0.5">{{ $task->description }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="relative" x-data="{ openListDropdown: false }" @click.outside="openListDropdown = false">
                                             <button @click.stop="openListDropdown = !openListDropdown" 
                                                class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border-0 cursor-pointer {{ $statuses[$task->status]['badgeBg'] }} {{ $statuses[$task->status]['text'] }} flex items-center gap-1">
                                                {{ $statuses[$task->status]['label'] ?? ucfirst($task->status) }}
                                                <i class="bi bi-chevron-down text-[8px]"></i>
                                             </button>
                                             <div x-show="openListDropdown" 
                                                  x-transition
                                                  class="absolute left-0 mt-1 w-36 rounded-xl shadow-lg py-1 bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 z-[1050]" 
                                                  style="display: none;">
                                                @foreach($statuses as $sKey => $sVal)
                                                    <button class="w-full text-left px-3 py-1.5 text-xs text-stone-700 dark:text-stone-300 hover:bg-stone-50 dark:hover:bg-stone-700/50 border-0 bg-transparent cursor-pointer" 
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
                                                ? 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border-red-200/60' 
                                                : ($task->priority === 'medium' ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200/60' : 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border-teal-200/60');
                                        @endphp
                                        <span class="rounded-md px-2 py-0.5 text-[9px] font-bold uppercase border {{ $priorityBadge }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $task->assignee->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($task->assignee->name) . '&background=0d9488&color=fff' }}" class="w-6 h-6 rounded-full object-cover" alt="{{ $task->assignee->name }}">
                                                <span class="text-xs text-stone-800 dark:text-stone-200">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-stone-400 italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($task->due_date)
                                            <span class="text-xs font-semibold {{ $task->due_date->isPast() ? 'text-red-500 font-bold' : 'text-stone-500 dark:text-stone-400' }}">
                                                {{ $task->due_date->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-xs text-stone-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="text-stone-400 hover:text-red-500 bg-transparent border-0 p-1 transition-colors cursor-pointer" wire:click="deleteTask({{ $task->id }})" wire:confirm="Hapus tugas ini?">
                                            <i class="bi bi-trash text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10">
                                        <i class="bi bi-clipboard text-3xl mb-1 text-stone-300 dark:text-stone-600 block"></i>
                                        <p class="text-xs text-stone-400 dark:text-stone-500 font-medium mb-0">Belum ada tugas yang dibuat</p>
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 bg-stone-900/60 backdrop-blur-sm" x-transition>
            <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl max-w-md w-full shadow-2xl overflow-hidden">
                <div class="p-4 flex justify-between items-center border-b border-stone-100 dark:border-stone-800">
                    <h3 class="font-extrabold text-stone-900 dark:text-stone-100 text-base mb-0">Buat Tugas Baru</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 bg-transparent border-0 p-0 outline-none cursor-pointer">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
                <div class="p-4">
                    <form wire:submit.prevent="createTask" class="space-y-4">
                        <div>
                            <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-1.5 uppercase tracking-wider">Judul Tugas <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" placeholder="Apa yang perlu diselesaikan?">
                            @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-1.5 uppercase tracking-wider">Deskripsi Singkat</label>
                            <textarea wire:model="description" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2 text-xs text-stone-900 dark:text-stone-100 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" rows="3" placeholder="Tambahkan rincian instruksi tugas..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-1.5 uppercase tracking-wider">Penanggung Jawab</label>
                                <select wire:model="assigned_to" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                                    <option value="">Belum Ditugaskan</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-1.5 uppercase tracking-wider">Prioritas</label>
                                <select wire:model="priority" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                                    <option value="low">Low (Rendah)</option>
                                    <option value="medium">Medium (Sedang)</option>
                                    <option value="high">High (Tinggi)</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block font-bold text-stone-700 dark:text-stone-300 text-xs mb-1.5 uppercase tracking-wider">Tenggat Waktu</label>
                                <input type="date" wire:model="due_date" class="w-full bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl px-3.5 py-2 text-xs text-stone-900 dark:text-stone-100 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                            </div>
                        </div>

                        <div class="flex justify-end gap-2.5 pt-3 border-t border-stone-100 dark:border-stone-800">
                            <button type="button" wire:click="$set('showCreateModal', false)" class="bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-200 font-medium py-2 px-4 rounded-xl text-xs transition-colors border-0 cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-5 rounded-xl text-xs transition-all duration-150 active:scale-[0.98] border-0 cursor-pointer shadow-sm">
                                Simpan Tugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
