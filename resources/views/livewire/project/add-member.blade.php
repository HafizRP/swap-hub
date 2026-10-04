<div>
    <!-- Modal (Alpine.js controlled via parent) -->
    <div x-show="showAddMemberModal" class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-stone-900/60 p-4" style="display: none;" x-transition>
        <div class="bg-white dark:bg-[#141414] border border-stone-200 dark:border-stone-800 rounded-xl max-w-md w-full shadow-2xl p-4" @click.outside="showAddMemberModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-stone-100 dark:border-stone-800 mb-4">
                <h5 class="font-bold text-stone-900 dark:text-stone-100 text-base">Tambah Anggota Tim</h5>
                <button type="button" @click="showAddMemberModal = false" class="text-stone-400 hover:text-stone-600 dark:hover:text-stone-300">
                    <i class="bi bi-x-lg text-sm"></i>
                </button>
            </div>
            
            <div class="modal-body p-0">
                <p class="text-stone-500 dark:text-stone-400 text-xs mb-4">Cari dan tambahkan rekan mahasiswa ke
                    <strong>{{ $project->title }}</strong>. Mereka akan mendapatkan akses ke diskusi dan kanban board.</p>
 
                <div class="relative mb-4">
                    <div class="flex items-center bg-stone-50 dark:bg-[#2e2c29]/50 border border-stone-200 dark:border-stone-700 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-teal-500/20 focus-within:border-teal-600">
                        <span class="pl-3 pr-2 text-stone-400">
                            <i class="bi bi-search text-xs"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-2.5 px-1 text-xs rounded-r-xl"
                            placeholder="Cari berdasarkan nama atau email..." autofocus>
                    </div>
 
                    <!-- Search Results Dropdown -->
                    @if(strlen($search) >= 2)
                        <div class="absolute w-full mt-1 rounded-xl shadow-lg border border-stone-200 dark:border-stone-800 bg-white dark:bg-[#1a1917] overflow-hidden z-[1050] max-h-[300px] overflow-y-auto">
                            @if(count($searchResults) > 0)
                                <div class="flex flex-col divide-y divide-stone-100 dark:divide-stone-800">
                                    @foreach($searchResults as $result)
                                        <button wire:click="addMember({{ $result->id }})"
                                            class="w-full text-left bg-transparent p-3 flex items-center justify-between gap-3 hover:bg-stone-50 dark:hover:bg-stone-800/60 transition-colors border-0 cursor-pointer">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $result->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($result->name) . '&background=0d9488&color=fff' }}"
                                                    class="w-9 h-9 rounded-full object-cover">
                                                <div class="min-w-0">
                                                    <div class="font-bold text-stone-900 dark:text-stone-100 text-xs truncate">{{ $result->name }}</div>
                                                    <div class="text-[11px] text-stone-400 dark:text-stone-500 truncate">{{ $result->email }}</div>
                                                </div>
                                            </div>
                                            <span class="bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs py-1.5 px-3.5 rounded-lg transition-colors border-0">Tambah</span>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-stone-400 dark:text-stone-500 text-xs">
                                    Tidak ada pengguna yang cocok dengan "{{ $search }}"
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
 
                <div class="flex justify-end pt-3 border-t border-stone-100 dark:border-stone-800">
                    <button type="button" @click="showAddMemberModal = false" class="bg-stone-100 hover:bg-stone-200 dark:bg-stone-800 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-250 font-medium py-2 px-5 rounded-lg text-xs transition-colors border-0 cursor-pointer">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>