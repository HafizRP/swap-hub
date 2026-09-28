<div>
    <!-- Modal (Alpine.js controlled via parent) -->
    <div x-show="showAddMemberModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full shadow-2xl p-6 space-y-4"
             @click.outside="showAddMemberModal = false">
            
            <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="font-black text-slate-900 dark:text-white text-base flex items-center gap-2">
                    <i class="bi bi-person-plus-fill text-brand-600"></i>
                    <span>Undang Anggota Tim</span>
                </h3>
                <button type="button" @click="showAddMemberModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <p class="text-slate-600 dark:text-slate-400 text-xs">
                    Cari mahasiswa dan tambahkan ke proyek <strong>{{ $project->title }}</strong> untuk berkolaborasi.
                </p>

                <div class="relative">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" wire:model.live.debounce.300ms="search"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                               placeholder="Cari berdasarkan nama atau email...">
                    </div>

                    <!-- Search Results Dropdown -->
                    @if(strlen($search) >= 2)
                        <div class="absolute w-full mt-1.5 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden z-50 max-h-60 overflow-y-auto custom-scrollbar">
                            @if(count($searchResults) > 0)
                                <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
                                    @foreach($searchResults as $result)
                                        <div class="p-3 flex items-center justify-between gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <img src="{{ $result->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($result->name) . '&background=6366f1&color=fff' }}"
                                                     alt="{{ $result->name }}"
                                                     class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700">
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ $result->name }}</h4>
                                                    <p class="text-[10px] text-slate-400 truncate">{{ $result->email }}</p>
                                                </div>
                                            </div>
                                            <button wire:click="addMember({{ $result->id }})"
                                                    type="button"
                                                    class="px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shrink-0 shadow-sm">
                                                Tambah
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-slate-400 text-xs">
                                    Tidak ada pengguna ditemukan untuk "{{ $search }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if (session()->has('message'))
                    <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 text-xs flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-600"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                <div class="flex justify-end pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showAddMemberModal = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
