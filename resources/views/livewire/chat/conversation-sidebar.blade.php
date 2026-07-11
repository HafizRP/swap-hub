<div class="h-full flex flex-col">
    <!-- Search -->
    <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <div class="flex items-center bg-slate-100 dark:bg-slate-700/50 rounded-full border border-transparent focus-within:border-indigo-500 overflow-hidden">
            <span class="text-slate-400 pl-4 pr-2 flex items-center justify-content-center shrink-0">
                <svg style="width: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-2 px-1 text-sm rounded-full"
                placeholder="Search conversations...">
        </div>
    </div>
 
    <!-- Conversations List -->
    <div class="flex-grow overflow-auto custom-scrollbar bg-white dark:bg-slate-800">
        <div class="flex flex-col divide-y divide-slate-100 dark:divide-slate-700/50">
            @forelse($this->filteredConversations as $conv)
                <a href="{{ route('chat', $conv['id']) }}" wire:navigate wire:key="conv-{{ $conv['id'] }}"
                    class="bg-transparent p-3.5 px-4 flex items-center gap-3.5 transition-all duration-150 no-underline {{ $conv['is_active'] ? 'bg-indigo-500/10 border-l-[3px] border-indigo-600' : 'border-l-[3px] border-transparent hover:bg-slate-50 dark:hover:bg-slate-700/20' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $conv['avatar'] }}" class="rounded-circle shadow-sm" width="44" height="44">
                        @if($conv['is_active'])
                            <!-- Active indicator badge -->
                            <div class="absolute top-0 left-0 bg-indigo-650 bg-indigo-650 text-white rounded-full border-2 border-white dark:border-slate-800 flex items-center justify-center"
                                style="width: 18px; height: 18px; transform: translate(-25%, -25%);">
                                <svg style="width: 10px; height: 10px;" fill="white" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <!-- Online indicator -->
                            <div class="absolute bottom-0 right-0 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"
                                style="width: 10px; height: 10px; transform: translate(15%, 15%);"></div>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h6 class="font-bold text-slate-800 dark:text-slate-100 mb-0 truncate flex-1 text-sm {{ $conv['is_active'] ? 'text-indigo-650 dark:text-indigo-400' : '' }}">
                                {{ $conv['name'] }}
                            </h6>
                            @if($conv['unread_count'] > 0)
                                <span class="bg-red-550 bg-red-500 text-white rounded-full text-[10px] px-2 py-0.5 font-bold shadow-sm shrink-0 ml-1">
                                    {{ $conv['unread_count'] }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mb-0 opacity-75">
                            {{ $conv['latest_message'] }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="text-center py-8">
                    <p class="text-slate-400 dark:text-slate-500 text-sm mb-0">No conversations found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>