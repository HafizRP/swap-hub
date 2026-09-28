<div class="h-full flex flex-col relative" x-data="{ openDropdownId: null }">
    
    <!-- Top Bar Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
            <h2 class="text-base font-black text-slate-900 dark:text-white tracking-tight">
                Papan Tugas Proyek
            </h2>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- View Toggle Switch -->
            <div class="p-1 rounded-xl bg-slate-100 dark:bg-slate-800 inline-flex border border-slate-200/80 dark:border-slate-700/80">
                <button wire:click="$set('viewType', 'board')" 
                        type="button"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewType === 'board' ? 'bg-white dark:bg-slate-700 shadow-sm text-brand-600 dark:text-brand-400' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                    <i class="bi bi-kanban"></i> <span>Board</span>
                </button>
                <button wire:click="$set('viewType', 'list')" 
                        type="button"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewType === 'list' ? 'bg-white dark:bg-slate-700 shadow-sm text-brand-600 dark:text-brand-400' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400' }}">
                    <i class="bi bi-list-task"></i> <span>List</span>
                </button>
            </div>

            <button wire:click="$set('showCreateModal', true)" 
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Tugas</span>
            </button>
        </div>
    </div>

    @php
        $statuses = [
            'todo' => ['label' => 'To Do', 'color' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'],
            'in_progress' => ['label' => 'In Progress', 'color' => 'bg-blue-500', 'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300'],
            'review' => ['label' => 'Review', 'color' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300'],
            'done' => ['label' => 'Done', 'color' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300']
        ];
    @endphp

    @if($viewType === 'board')
        <!-- Kanban Grid View -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 overflow-x-auto pb-4 custom-scrollbar">
            @foreach($statuses as $key => $status)
                <div class="rounded-2xl bg-slate-50/80 dark:bg-slate-900/50 p-4 border border-slate-200/80 dark:border-slate-800 flex flex-col min-h-[480px]">
                    <!-- Column Header -->
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200/60 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $status['color'] }}"></span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-slate-200">
                                {{ $status['label'] }}
                            </h3>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200/80 dark:border-slate-700 shadow-sm">
                            {{ $tasks->get($key)?->count() ?? 0 }}
                        </span>
                    </div>

                    <!-- Tasks List -->
                    <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar pr-1">
                        @forelse($tasks->get($key) ?? [] as $task)
                            @php
                                $priorityStyle = match($task->priority) {
                                    'high' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border-rose-200/60',
                                    'medium' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border-amber-200/60',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200/60',
                                };
                            @endphp
                            <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover:shadow-md transition-all space-y-2.5 group">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase border {{ $priorityStyle }}">
                                        {{ $task->priority }}
                                    </span>

                                    <!-- Dropdown Action -->
                                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                        <button @click.stop="open = !open" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <div x-show="open" x-cloak x-transition
                                             class="absolute right-0 mt-1 w-40 rounded-xl shadow-lg py-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 z-50 text-xs">
                                            <div class="px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pindahkan Status</div>
                                            @foreach($statuses as $sKey => $sVal)
                                                @if($sKey !== $key)
                                                    <button type="button"
                                                            class="w-full text-left px-3 py-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/60"
                                                            wire:click="updateStatus({{ $task->id }}, '{{ $sKey }}')">
                                                        {{ $sVal['label'] }}
                                                    </button>
                                                @endif
                                            @endforeach
                                            <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                                            <button type="button"
                                                    class="w-full text-left px-3 py-1.5 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20"
                                                    wire:click="deleteTask({{ $task->id }})" wire:confirm="Hapus tugas ini?">
                                                Hapus Tugas
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">
                                        {{ $task->title }}
                                    </h4>
                                    @if($task->description)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-700/60 text-[11px]">
                                    <div class="flex items-center gap-1.5">
                                        @if($task->assignee)
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&size=40&background=6366f1&color=fff" 
                                                 alt="{{ $task->assignee->name }}"
                                                 class="w-5 h-5 rounded-full object-cover"
                                                 title="Ditugaskan: {{ $task->assignee->name }}">
                                            <span class="text-slate-600 dark:text-slate-400 font-semibold truncate max-w-[80px]">
                                                {{ $task->assignee->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Unassigned</span>
                                        @endif
                                    </div>

                                    @if($task->due_date)
                                        <span class="font-bold {{ $task->due_date->isPast() ? 'text-rose-500' : 'text-slate-400' }} flex items-center gap-1">
                                            <i class="bi bi-clock"></i>
                                            {{ $task->due_date->format('d M') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="h-32 flex flex-col items-center justify-center text-slate-400 text-xs">
                                <i class="bi bi-inbox text-xl mb-1"></i>
                                <span>Tidak ada tugas</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- List View -->
        <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 uppercase font-extrabold text-[10px] bg-slate-50/50 dark:bg-slate-800/50">
                            <th class="py-3 px-4">Tugas</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Prioritas</th>
                            <th class="py-3 px-4">Ditugaskan</th>
                            <th class="py-3 px-4">Tenggat</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @php $allTasks = $tasks->flatten(); @endphp
                        @forelse($allTasks as $task)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                                    <span>{{ $task->title }}</span>
                                    @if($task->description)
                                        <p class="text-[11px] font-normal text-slate-400 truncate max-w-sm">{{ $task->description }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statuses[$task->status]['badge'] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $statuses[$task->status]['label'] ?? ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase border">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $task->assignee->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 {{ $task->due_date && $task->due_date->isPast() ? 'text-rose-500 font-bold' : 'text-slate-500' }}">
                                    {{ $task->due_date ? $task->due_date->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Hapus tugas ini?" class="text-slate-400 hover:text-rose-500">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada tugas dalam proyek ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Create Task Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl overflow-hidden p-6 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="font-black text-slate-900 dark:text-white text-base">
                        Tambah Tugas Baru
                    </h3>
                    <button wire:click="$set('showCreateModal', false)" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="createTask" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Judul Tugas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="title" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none"
                               placeholder="Apa yang perlu diselesaikan?">
                        @error('title') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none"
                                  placeholder="Rincian detail tugas..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tugaskan Ke</label>
                            <select wire:model="assigned_to"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="">Belum Ditugaskan</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Prioritas</label>
                            <select wire:model="priority"
                                    class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                                <option value="low">Rendah</option>
                                <option value="medium">Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tenggat Waktu</label>
                        <input type="date" wire:model="due_date"
                               class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-brand-500 outline-none">
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                            Buat Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
