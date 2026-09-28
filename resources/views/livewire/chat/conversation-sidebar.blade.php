<div class="h-full flex flex-col">
    <!-- Search Bar -->
    <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
        <div class="relative">
            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                   class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 border-none text-slate-900 dark:text-white text-xs placeholder-slate-400 focus:ring-2 focus:ring-brand-500 outline-none"
                   placeholder="Cari obrolan / tim...">
        </div>
    </div>

    <!-- Conversations List -->
    <div class="flex-1 overflow-y-auto custom-scrollbar bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
        @forelse($this->filteredConversations as $conv)
            <a href="{{ route('chat', $conv['id']) }}" wire:navigate wire:key="conv-{{ $conv['id'] }}"
               class="p-3.5 flex items-center gap-3 transition-colors {{ $conv['is_active'] ? 'bg-brand-50/70 dark:bg-brand-950/40 border-l-4 border-brand-600' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60 border-l-4 border-transparent' }}">
                
                <div class="relative shrink-0">
                    <img src="{{ $conv['avatar'] }}" alt="{{ $conv['name'] }}"
                         class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-100 dark:ring-slate-800">
                    @if($conv['is_active'])
                        <span class="absolute -top-1 -left-1 w-3.5 h-3.5 rounded-full bg-brand-600 ring-2 ring-white dark:ring-slate-900 flex items-center justify-center text-white text-[8px]">
                            <i class="bi bi-check"></i>
                        </span>
                    @else
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900"></span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <h4 class="text-xs font-bold truncate {{ $conv['is_active'] ? 'text-brand-600 dark:text-brand-400' : 'text-slate-900 dark:text-white' }}">
                            {{ $conv['name'] }}
                        </h4>
                        @if($conv['unread_count'] > 0)
                            <span class="px-1.5 py-0.5 rounded-full bg-rose-500 text-white text-[9px] font-black shrink-0 ml-1">
                                {{ $conv['unread_count'] }}
                            </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                        {{ $conv['latest_message'] ?: 'Mulai obrolan baru' }}
                    </p>
                </div>
            </a>
        @empty
            <div class="py-12 text-center text-slate-400 text-xs space-y-1">
                <i class="bi bi-chat-square-dots text-2xl"></i>
                <p class="font-bold">Tidak ada obrolan ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
