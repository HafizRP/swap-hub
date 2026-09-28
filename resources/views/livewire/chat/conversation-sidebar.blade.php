<div class="h-full flex flex-col">
    <!-- Search -->
    <div class="p-3.5 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <div class="flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-600 overflow-hidden transition-all">
            <span class="text-slate-400 pl-3 pr-1.5 flex items-center justify-center shrink-0">
                <i class="bi bi-search text-xs"></i>
            </span>
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-2 px-1 text-xs"
                placeholder="Cari percakapan...">
        </div>
    </div>
 
    <!-- Conversations List -->
    <div class="flex-grow overflow-y-auto custom-scrollbar bg-white dark:bg-slate-900">
        <div class="flex flex-col divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($this->filteredConversations as $conv)
                <a href="{{ route('chat', $conv['id']) }}" wire:navigate wire:key="conv-{{ $conv['id'] }}"
                    class="p-3.5 px-4 flex items-center gap-3 transition-colors no-underline {{ $conv['is_active'] ? 'bg-indigo-50/70 dark:bg-indigo-950/30 border-l-4 border-indigo-600' : 'border-l-4 border-transparent hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $conv['avatar'] }}" class="w-10 h-10 rounded-2xl object-cover border border-slate-200 dark:border-slate-700 shadow-xs" alt="{{ $conv['name'] }}">
                        @if($conv['is_active'])
                            <div class="absolute -top-1 -left-1 bg-indigo-600 text-white rounded-full w-4 h-4 border-2 border-white dark:border-slate-900 flex items-center justify-center text-[8px]">
                                <i class="bi bi-check"></i>
                            </div>
                        @else
                            <div class="absolute -bottom-0.5 -right-0.5 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full w-3 h-3"></div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="font-extrabold text-xs text-slate-900 dark:text-slate-100 mb-0 truncate flex-1 {{ $conv['is_active'] ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                {{ $conv['name'] }}
                            </h4>
                            @if($conv['unread_count'] > 0)
                                <span class="bg-red-500 text-white rounded-full text-[9px] px-1.5 py-0.5 font-bold shadow-xs shrink-0 ml-1">
                                    {{ $conv['unread_count'] }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mb-0">
                            {{ $conv['latest_message'] ?: 'Mulai percakapan baru' }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 px-4">
                    <i class="bi bi-chat-left-dots text-2xl text-slate-300 dark:text-slate-600 mb-1 block"></i>
                    <p class="text-slate-400 dark:text-slate-500 text-xs mb-0">Tidak ada percakapan ditemukan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>