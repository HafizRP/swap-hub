<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4" x-data="{ showAddMilestone: false }">
    <!-- Header -->
    <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('projects.show', $project->id) }}" class="text-xs text-teal-600 dark:text-teal-400 hover:underline no-underline">&larr; Kembali ke Proyek</a>
            </div>
            <h1 class="text-xl font-bold text-stone-900 dark:text-stone-100 mt-1 mb-0">Milestone & Roadmap: {{ $project->title }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-stone-100 dark:bg-stone-800 p-1 rounded-lg flex items-center gap-1 text-xs">
                <button wire:click="switchView('kanban')" class="px-3 py-1.5 rounded-lg font-semibold transition-all border-0 cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white dark:bg-[#141414] text-teal-600 dark:text-teal-400 shadow-sm' : 'text-stone-500 bg-transparent' }}">
                    <i class="bi bi-kanban"></i> Kanban
                </button>
                <button wire:click="switchView('timeline')" class="px-3 py-1.5 rounded-lg font-semibold transition-all border-0 cursor-pointer {{ $viewMode === 'timeline' ? 'bg-white dark:bg-[#141414] text-teal-600 dark:text-teal-400 shadow-sm' : 'text-stone-500 bg-transparent' }}">
                    <i class="bi bi-timeline"></i> Timeline
                </button>
            </div>
            <button wire:click="showAddMilestone = true" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold border-0 cursor-pointer shadow-sm">
                + Milestone Baru
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <!-- Kanban View Mode -->
    @if($viewMode === 'kanban')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach(['pending' => 'Belum Dimulai', 'in_progress' => 'Sedang Berjalan', 'completed' => 'Selesai'] as $statusKey => $statusLabel)
                <div class="bg-stone-100/70 dark:bg-[#141414] p-4 rounded-xl border border-stone-200/80 dark:border-stone-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-stone-800 dark:text-stone-200 mb-0">{{ $statusLabel }}</h3>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-stone-200 dark:bg-stone-800 text-stone-600 dark:text-stone-400 font-bold">
                            {{ $milestones->where('status', $statusKey)->count() }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($milestones->where('status', $statusKey) as $milestone)
                            <div wire:key="milestone-kanban-{{ $milestone->id }}" class="bg-white dark:bg-[#1a1917] p-3.5 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-3">
                                <div>
                                    <h4 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-1">{{ $milestone->title }}</h4>
                                    @if($milestone->description)
                                        <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">{{ $milestone->description }}</p>
                                    @endif
                                    @if($milestone->due_date)
                                        <span class="inline-block mt-2 text-[10px] text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded">
                                            <i class="bi bi-calendar"></i> Tenggat: {{ $milestone->due_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Tasks under this milestone -->
                                <div class="space-y-1.5 pt-2 border-t border-stone-100 dark:border-stone-800">
                                    <span class="text-[11px] font-semibold text-stone-400">Tugas Terkait ({{ $milestone->tasks->count() }}):</span>
                                    @forelse($milestone->tasks as $task)
                                        <div wire:key="task-{{ $task->id }}" class="text-xs p-2 rounded bg-stone-50 dark:bg-[#2e2c29]/40 flex items-center justify-between text-stone-700 dark:text-stone-300">
                                            <span class="truncate">{{ $task->title }}</span>
                                            <button wire:click="linkTaskToMilestone({{ $task->id }}, null)" class="text-stone-400 hover:text-red-500 text-[10px] border-0 bg-transparent cursor-pointer" title="Unlink task">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-[11px] text-stone-400 italic mb-0">Belum ada tugas terhubung.</p>
                                    @endforelse
                                </div>

                                <!-- Move Status Buttons -->
                                <div class="flex items-center gap-1 pt-2">
                                    @if($statusKey !== 'pending')
                                        <button wire:click="updateMilestoneStatus({{ $milestone->id }}, 'pending')" class="text-[10px] px-2 py-1 bg-stone-100 dark:bg-stone-800 hover:bg-stone-200 text-stone-700 dark:text-stone-300 rounded font-medium border-0 cursor-pointer">&larr; Pend</button>
                                    @endif
                                    @if($statusKey !== 'in_progress')
                                        <button wire:click="updateMilestoneStatus({{ $milestone->id }}, 'in_progress')" class="text-[10px] px-2 py-1 bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-300 rounded font-medium border-0 cursor-pointer">Proses</button>
                                    @endif
                                    @if($statusKey !== 'completed')
                                        <button wire:click="updateMilestoneStatus({{ $milestone->id }}, 'completed')" class="text-[10px] px-2 py-1 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded font-medium border-0 cursor-pointer">Selesai &rarr;</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-stone-400 text-center py-4 mb-0">Kosong</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Timeline View Mode -->
        <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-4">
            <h2 class="text-sm font-bold text-stone-900 dark:text-stone-100 mb-0">Roadmap Timeline</h2>
            
            <div class="relative border-l-2 border-teal-200 dark:border-teal-900 ml-4 space-y-4">
                @forelse($milestones as $m)
                    <div wire:key="milestone-{{ $m->id }}" class="relative pl-6">
                        <span class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-2 border-white dark:border-[#141414] {{ $m->status === 'completed' ? 'bg-emerald-500' : ($m->status === 'in_progress' ? 'bg-teal-500' : 'bg-stone-300 dark:bg-stone-600') }}"></span>
                        <div class="bg-stone-50 dark:bg-[#1a1917] p-3.5 rounded-xl border border-stone-200 dark:border-stone-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0">{{ $m->title }}</h3>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold uppercase {{ $m->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-200 text-stone-700' }}">{{ $m->status }}</span>
                            </div>
                            <p class="text-xs text-stone-500 dark:text-stone-400 mb-0">{{ $m->description ?? 'Tidak ada deskripsi' }}</p>
                            <p class="text-xs text-teal-600 dark:text-teal-400 font-medium mb-0"><i class="bi bi-calendar-event"></i> {{ $m->due_date ? $m->due_date->format('d M Y') : 'Tanpa Tenggat' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 pl-6 mb-0">Belum ada milestone pada roadmap ini.</p>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Unlinked Tasks Section -->
    @if($unlinkedTasks->count() > 0)
        <div class="bg-white dark:bg-[#141414] p-4 rounded-xl shadow-card border border-stone-200/80 dark:border-stone-800 space-y-4">
            <h3 class="font-bold text-stone-900 dark:text-stone-100 text-xs mb-0">Tugas Tanpa Milestone ({{ $unlinkedTasks->count() }})</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($unlinkedTasks as $uTask)
                    <div wire:key="unlinked-task-{{ $uTask->id }}" class="p-3 rounded-xl border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-[#1a1917] flex items-center justify-between text-xs">
                        <span class="font-medium text-stone-800 dark:text-stone-200 truncate mr-2">{{ $uTask->title }}</span>
                        <select wire:change="linkTaskToMilestone({{ $uTask->id }}, $event.target.value)" class="text-[11px] px-2 py-1 rounded bg-white dark:bg-stone-800 border border-stone-200 dark:border-stone-700 text-stone-800 dark:text-stone-100">
                            <option value="">Hubungkan ke...</option>
                            @foreach($milestones as $ms)
                                <option value="{{ $ms->id }}">{{ $ms->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modal Form add milestone -->
    <div x-show="showAddMilestone" x-cloak class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#141414] w-full max-w-md p-4 rounded-xl shadow-xl space-y-4 border border-stone-200 dark:border-stone-800">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-stone-900 dark:text-stone-100 mb-0">Tambah Milestone</h3>
                <button wire:click="showAddMilestone = false" class="text-stone-400 hover:text-stone-600 border-0 bg-transparent cursor-pointer"><i class="bi bi-x-lg"></i></button>
            </div>
            <form wire:submit.prevent="addMilestone" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Judul Milestone</label>
                    <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Deskripsi</label>
                    <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 dark:text-stone-300 mb-1">Tenggat Waktu</label>
                    <input type="date" wire:model="dueDate" class="w-full px-3 py-2 border rounded-xl border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-[#1a1917] text-stone-900 dark:text-stone-100 text-xs focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" wire:click="showAddMilestone = false" class="px-4 py-2 text-xs text-stone-500 hover:text-stone-700 border-0 bg-transparent cursor-pointer">Batal</button>
                    <button type="submit" wire:key="milestone-form-submit" class="px-4 py-2 text-xs bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium border-0 cursor-pointer shadow-sm">Simpan Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>
