<div class="h-full flex flex-col">
    <!-- Toolbar -->
    <div class="p-3.5 border-b border-stone-200/80 dark:border-stone-800 flex justify-between items-center bg-stone-50 dark:bg-[#141414]">
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white dark:bg-[#1a1917] border border-stone-200 dark:border-stone-700 rounded-lg overflow-hidden w-[240px] shadow-sm">
                <span class="pl-3 pr-2 text-stone-400">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="bg-transparent border-0 w-full outline-none text-stone-900 dark:text-stone-100 placeholder-stone-400 py-1.5 px-1 text-xs"
                    placeholder="Cari file...">
            </div>

            <div class="inline-flex rounded-lg overflow-hidden border border-stone-200 dark:border-stone-700 shadow-sm bg-white dark:bg-[#1a1917]">
                <button wire:click="$set('typeFilter', 'all')"
                    class="px-3 py-1.5 text-xs font-medium transition-colors {{ $typeFilter === 'all' ? 'bg-stone-100 dark:bg-stone-800 text-stone-900 dark:text-stone-100 font-bold' : 'bg-transparent text-stone-500 hover:bg-stone-50 dark:hover:bg-stone-800/50' }}">Semua</button>
                <button wire:click="$set('typeFilter', 'image')"
                    class="px-3 py-1.5 text-xs font-medium border-l border-stone-200 dark:border-stone-700 transition-colors {{ $typeFilter === 'image' ? 'bg-stone-100 dark:bg-stone-800 text-stone-900 dark:text-stone-100 font-bold' : 'bg-transparent text-stone-500 hover:bg-stone-50 dark:hover:bg-stone-800/50' }}">Gambar</button>
                <button wire:click="$set('typeFilter', 'document')"
                    class="px-3 py-1.5 text-xs font-medium border-l border-stone-200 dark:border-stone-700 transition-colors {{ $typeFilter === 'document' ? 'bg-stone-100 dark:bg-stone-800 text-stone-900 dark:text-stone-100 font-bold' : 'bg-transparent text-stone-500 hover:bg-stone-50 dark:hover:bg-stone-800/50' }}">Dokumen</button>
            </div>
        </div>

        <div class="flex gap-2">
            <button class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-1.5 px-3.5 rounded-lg text-xs transition-colors border-0 shadow-sm flex items-center gap-1.5 cursor-pointer" onclick="document.querySelector('#fileInput').click()">
                <i class="bi bi-upload text-xs"></i> Unggah
            </button>
        </div>
    </div>

    <!-- File Grid -->
    <div class="flex-grow overflow-auto p-4 custom-scrollbar">
        @if($files->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files as $file)
                    <div class="col-span-1" wire:key="file-{{ $file->id }}">
                        <div class="bg-white dark:bg-[#141414] border border-stone-200/80 dark:border-stone-800 rounded-xl shadow-card hover-lift group transition-all h-full">
                            <div class="p-3 flex flex-col h-full">
                                <div class="mb-3 relative rounded-lg overflow-hidden bg-stone-100 dark:bg-stone-800/50 flex items-center justify-center h-[120px]">
                                    @if(Str::startsWith($file->file_type, 'image/'))
                                        <img src="{{ asset('storage/' . $file->file_path) }}" class="w-full h-full object-cover" alt="{{ $file->file_name }}">
                                    @else
                                        <i class="bi bi-file-earmark-text text-4xl text-stone-400 dark:text-stone-500"></i>
                                    @endif

                                    <!-- Overlay Actions -->
                                    <div class="absolute inset-0 bg-stone-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="bg-white dark:bg-stone-800 hover:bg-stone-50 dark:hover:bg-stone-700 text-stone-700 dark:text-stone-200 rounded-full p-2.5 shadow-md flex items-center justify-center transition-transform hover:scale-105" title="Download/View">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <h6 class="text-stone-900 dark:text-stone-100 text-xs font-bold truncate mb-1" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </h6>
                                    <div class="flex justify-between items-center">
                                        <small class="text-stone-400 dark:text-stone-500 text-[10px]">
                                            {{ $file->created_at->format('d M Y') }}
                                        </small>
                                        <span class="bg-stone-100 dark:bg-stone-800 text-stone-600 dark:text-stone-400 rounded text-[9px] px-1.5 py-0.5 border border-stone-200 dark:border-stone-700 font-bold shrink-0">
                                            {{ strtoupper(pathinfo($file->file_name, PATHINFO_EXTENSION)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-full flex flex-col items-center justify-center text-center opacity-70 py-12">
                <i class="bi bi-folder2-open text-5xl mb-3 text-stone-300 dark:text-stone-600"></i>
                <p class="text-stone-600 dark:text-stone-400 text-sm font-semibold mb-1">Belum ada berkas dibagikan.</p>
                <small class="text-stone-400 dark:text-stone-500 text-xs">File yang dikirim melalui diskusi proyek akan muncul di sini.</small>
            </div>
        @endif
    </div>
</div>
