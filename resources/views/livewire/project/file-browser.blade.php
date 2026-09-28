<div class="space-y-4">
    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative w-full sm:w-64">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" wire:model.live.debounce.300ms="search"
                       class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs outline-none focus:ring-2 focus:ring-brand-500"
                       placeholder="Cari berkas...">
            </div>

            <div class="p-1 rounded-xl bg-slate-100 dark:bg-slate-800 inline-flex border border-slate-200/80 dark:border-slate-700/80">
                <button wire:click="$set('typeFilter', 'all')"
                        class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $typeFilter === 'all' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500' }}">Semua</button>
                <button wire:click="$set('typeFilter', 'image')"
                        class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $typeFilter === 'image' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500' }}">Gambar</button>
                <button wire:click="$set('typeFilter', 'document')"
                        class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $typeFilter === 'document' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500' }}">Dokumen</button>
            </div>
        </div>
    </div>

    <!-- File Grid -->
    <div>
        @if($files->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($files as $file)
                    <div class="p-3 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                        <div class="relative rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center h-28 mb-2.5">
                            @if(Str::startsWith($file->file_type, 'image/'))
                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->file_name }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-file-earmark-text text-3xl text-slate-400"></i>
                            @endif

                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" download
                                   class="p-2 rounded-xl bg-white dark:bg-slate-800 text-brand-600 shadow-lg hover:scale-110 transition-transform">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate" title="{{ $file->file_name }}">
                                {{ $file->file_name }}
                            </h4>
                            <div class="flex items-center justify-between mt-1 text-[10px] text-slate-400">
                                <span>{{ $file->created_at->format('d M Y') }}</span>
                                <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 font-extrabold uppercase">
                                    {{ strtoupper(pathinfo($file->file_name, PATHINFO_EXTENSION)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center text-slate-400 space-y-2">
                <i class="bi bi-folder2-open text-3xl"></i>
                <p class="text-xs font-bold">Belum ada berkas yang dibagikan.</p>
                <p class="text-[11px]">Lampiran file yang dikirimkan dalam chat proyek akan otomatis muncul di sini.</p>
            </div>
        @endif
    </div>
</div>
