<div>
    <!-- Trigger Button (Hidden, triggered by event) -->
    <button type="button" class="d-none" id="openAddMemberModal" data-bs-toggle="modal"
        data-bs-target="#addMemberModal"></button>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg bg-sidebar" style="background-color: var(--bs-body-bg);">
                <div class="modal-header border-bottom border-white border-opacity-10">
                    <h5 class="modal-title fw-bold">Add Team Members</h5>
                    <button type="button"
                        class="btn-close {{ auth()->user()->theme == 'dark' ? 'btn-close-white' : '' }}"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-4">Search and add students to
                        <strong>{{ $project->title }}</strong>. They will get access to the chat and task board.</p>

                    <div class="position-relative mb-4">
                        <div class="input-group">
                            <span
                                class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-25 text-secondary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control border-start-0 border-secondary border-opacity-25 bg-transparent"
                                placeholder="Search by name or email..." autofocus>
                        </div>

                        <!-- Search Results Dropdown -->
                        @if(strlen($search) >= 2)
                            <div class="position-absolute w-100 mt-1 rounded-3 shadow-lg border border-white border-opacity-10 bg-sidebar overflow-hidden"
                                style="z-index: 1050; max-height: 300px; overflow-y: auto; background-color: var(--bs-card-bg);">
                                @if(count($searchResults) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($searchResults as $result)
                                            <button wire:click="addMember({{ $result->id }})"
                                                class="list-group-item list-group-item-action border-bottom border-white border-opacity-10 bg-transparent p-3 d-flex align-items-center gap-3">
                                                <img src="{{ $result->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($result->name) }}"
                                                    class="rounded-circle" width="40" height="40">
                                                <div class="flex-grow-1 text-start">
                                                    <div class="fw-bold text-body">{{ $result->name }}</div>
                                                    <div class="small text-secondary">{{ $result->email }}</div>
                                                </div>
                                                <span class="btn btn-sm btn-primary rounded-pill">Add</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-3 text-center text-secondary small">
                                        No users found matching "{{ $search }}"
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if (session()->has('message'))
                        <div class="alert alert-success d-flex align-items-center small py-2 rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>