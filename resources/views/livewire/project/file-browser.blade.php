<div class="h-100 d-flex flex-column">
    <!-- Toolbar -->
    <div
        class="p-4 border-bottom border-white border-opacity-10 d-flex justify-content-between align-items-center bg-subtle">
        <div class="d-flex align-items-center gap-3">
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-dark border-0 text-secondary ps-3">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="form-control bg-dark border-0 text-white placeholder-secondary"
                    placeholder="Search files...">
            </div>

            <div class="btn-group">
                <button wire:click="$set('typeFilter', 'all')"
                    class="btn btn-sm {{ $typeFilter === 'all' ? 'btn-secondary' : 'btn-outline-secondary' }}">All</button>
                <button wire:click="$set('typeFilter', 'image')"
                    class="btn btn-sm {{ $typeFilter === 'image' ? 'btn-secondary' : 'btn-outline-secondary' }}">Images</button>
                <button wire:click="$set('typeFilter', 'document')"
                    class="btn btn-sm {{ $typeFilter === 'document' ? 'btn-secondary' : 'btn-outline-secondary' }}">Docs</button>
            </div>
        </div>

        <div class="d-flex gap-2">
            <!-- Upload functionality could go here, integrated with chat input basically -->
            <button class="btn btn-primary btn-sm rounded-pill" onclick="document.querySelector('#fileInput').click()">
                <i class="bi bi-upload me-1"></i> Upload
            </button>
        </div>
    </div>

    <!-- File Grid -->
    <div class="flex-grow-1 overflow-auto p-4 custom-scrollbar">
        @if($files->count() > 0)
            <div class="row g-3">
                @foreach($files as $file)
                    <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                        <div class="card h-100 border-0 shadow-sm bg-dark bg-opacity-25 hover-lift group">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="mb-3 position-relative rounded-3 overflow-hidden bg-dark d-flex align-items-center justify-content-center"
                                    style="height: 120px;">
                                    @if(Str::startsWith($file->file_type, 'image/'))
                                        <img src="{{ asset('storage/' . $file->file_path) }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <i class="bi bi-file-earmark-text display-4 text-secondary"></i>
                                    @endif

                                    <!-- Overlay Actions -->
                                    <div
                                        class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 transition-opacity">
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                            class="btn btn-light btn-sm rounded-circle shadow-sm m-1" title="Download/View">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <h6 class="text-white small fw-bold text-truncate mb-1" title="{{ $file->file_name }}">
                                        {{ $file->file_name }}
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-secondary" style="font-size: 10px;">
                                            {{ $file->created_at->format('M d, Y') }}
                                        </small>
                                        <span
                                            class="badge bg-secondary bg-opacity-25 text-secondary border border-white border-opacity-10 rounded-1"
                                            style="font-size: 9px;">
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
            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center opacity-50">
                <i class="bi bi-folder2-open display-1 mb-3 text-secondary"></i>
                <p class="text-secondary">No files shared yet.</p>
                <small class="text-secondary">Files sent in chat will appear here.</small>
            </div>
        @endif
    </div>
</div>