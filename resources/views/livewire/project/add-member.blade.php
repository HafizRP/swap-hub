<div>
    <!-- Modal (Alpine.js controlled via parent) -->
    <div x-show="showAddMemberModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-slate-900/60 p-4" style="display: none;" x-transition>
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl max-w-md w-full shadow-2xl p-6" @click.outside="showAddMemberModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-700/50 mb-4">
                <h5 class="font-bold text-slate-800 dark:text-slate-100 text-lg">Add Team Members</h5>
                <button type="button" @click="showAddMemberModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
            
            <div class="modal-body p-0">
                <p class="text-slate-500 dark:text-slate-400 text-xs mb-4">Search and add students to
                    <strong>{{ $project->title }}</strong>. They will get access to the chat and task board.</p>
 
                <div class="relative mb-4">
                    <div class="flex items-center bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg overflow-hidden">
                        <span class="pl-3 pr-2 text-slate-455 text-slate-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-2.5 px-1 text-sm rounded-r-lg"
                            placeholder="Search by name or email..." autofocus>
                    </div>
 
                    <!-- Search Results Dropdown -->
                    @if(strlen($search) >= 2)
                        <div class="absolute w-full mt-1 rounded-lg shadow-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden z-[1050] max-h-[300px] overflow-y-auto">
                            @if(count($searchResults) > 0)
                                <div class="flex flex-col divide-y divide-slate-100 dark:divide-slate-700/50">
                                    @foreach($searchResults as $result)
                                        <button wire:click="addMember({{ $result->id }})"
                                            class="w-full text-left bg-transparent p-3 flex items-center justify-between gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-0">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $result->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($result->name) }}"
                                                    class="rounded-circle" width="40" height="40">
                                                <div class="min-w-0">
                                                    <div class="font-bold text-slate-800 dark:text-slate-100 text-sm truncate">{{ $result->name }}</div>
                                                    <div class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $result->email }}</div>
                                                </div>
                                            </div>
                                            <span class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs py-1.5 px-3.5 rounded-full transition-colors border-0">Add</span>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-slate-400 dark:text-slate-500 text-xs">
                                    No users found matching "{{ $search }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
 
                @if (session()->has('message'))
                    <div class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300 border border-emerald-500/25 p-3 rounded-lg flex items-center gap-2 text-xs mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('message') }}
                    </div>
                @endif
 
                <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-slate-700/50">
                    <button type="button" @click="showAddMemberModal = false" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-250 font-bold py-2 px-5 rounded-full text-xs transition-colors border-0">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>