<div class="h-full flex flex-col">
    <!-- Toolbar -->
    <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/40">
        <div class="flex items-center gap-4">
            <div class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-full overflow-hidden w-[250px] shadow-sm">
                <span class="pl-4 pr-2 text-slate-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="bg-transparent border-0 w-full outline-none text-slate-800 dark:text-slate-100 placeholder-slate-400 py-1.5 px-1 text-xs"
                    placeholder="Search files...">
            </div>
 
            <div class="inline-flex rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm bg-white dark:bg-slate-800">
                <button wire:click="$set('typeFilter', 'all')"
                    class="px-3 py-1.5 text-xs font-semibold transition-colors {{ $typeFilter === 'all' ? 'bg-slate-200 dark:bg-slate-700 text-slate-850 dark:text-slate-100' : 'bg-transparent text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">All</button>
                <button wire:click="$set('typeFilter', 'image')"
                    class="px-3 py-1.5 text-xs font-semibold border-l border-slate-200 dark:border-slate-700 transition-colors {{ $typeFilter === 'image' ? 'bg-slate-200 dark:bg-slate-700 text-slate-850 dark:text-slate-100' : 'bg-transparent text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">Images</button>
                <button wire:click="$set('typeFilter', 'document')"
                    class="px-3 py-1.5 text-xs font-semibold border-l border-slate-200 dark:border-slate-700 transition-colors {{ $typeFilter === 'document' ? 'bg-slate-200 dark:bg-slate-700 text-slate-850 dark:text-slate-100' : 'bg-transparent text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">Docs</button>
            </div>
        </div>
 
        <div class="flex gap-2">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-full text-xs transition-colors border-0 shadow" onclick="document.querySelector('#fileInput').click()">
                <i class="bi bi-upload mr-1"></i> Upload
            </button>
        </div>
    </div>
 
    <!-- File Grid -->
    <div class="flex-grow overflow-auto p-4 custom-scrollbar">
        @if($files->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files as $file)
                    <div class="col-span-1">
                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm hover-lift group transition-all h-full">
                            <div class="p-3 flex flex-col h-full">
                                <div class="mb-3 relative rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center h-[120px]">
                                    @if(Str::startsWith($file->file_type, 'image/'))
                                        <img src="{{ asset('storage/' . $file->file_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="bi bi-file-earmark-text text-4xl text-slate-450 dark:text-slate-500"></i>
                                    @endif
 
                                    <!-- Overlay Actions -->
                                    <div class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-full p-2.5 shadow-md flex items-center justify-center transition-transform hover:scale-105" title="Download/View">
                                            <i class="bi bi-download text-sm"></i>
                                        </a>
                                    </div>
                                </div>
 
                                <div class="mt-auto">
                                    <h6 class="text-slate-850 dark:text-slate-100 text-xs font-bold truncate mb-1" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </h6>
                                    <div class="flex justify-between items-center">
                                        <small class="text-slate-400 dark:text-slate-500 text-[10px]">
                                            {{ $file->created_at->format('M d, Y') }}
                                        </small>
                                        <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-350 rounded text-[9px] px-1.5 py-0.5 border border-slate-205 dark:border-slate-600 font-bold shrink-0">
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
            <div class="h-full flex flex-col items-center justify-content-center text-center opacity-50 py-12">
                <i class="bi bi-folder2-open text-5xl mb-3 text-slate-450 dark:text-slate-500"></i>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-bold mb-1">No files shared yet.</p>
                <small class="text-slate-400 dark:text-slate-650 text-xs">Files sent in chat will appear here.</small>
            </div>
        @endif
    </div>
</div>