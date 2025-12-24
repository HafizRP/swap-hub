<div class="h-100 d-flex flex-column">
    <!-- Search -->
    <div class="p-4 border-bottom border-white border-opacity-10">
        <div class="input-group">
            <span class="input-group-text bg-transparent border-0 text-secondary ps-3">
                <svg style="width: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                class="form-control form-control-sm bg-white bg-opacity-5 border-0 text-white placeholder-secondary rounded-pill py-2"
                placeholder="Search conversations..." style="box-shadow: none;">
        </div>
    </div>

    <!-- Conversations List -->
    <div class="flex-grow-1 overflow-auto custom-scrollbar">
        <div class="list-group list-group-flush">
            @forelse($this->filteredConversations as $conv)
                <a href="#" wire:click.prevent="switchConversation({{ $conv['id'] }})" wire:key="conv-{{ $conv['id'] }}"
                    class="list-group-item list-group-item-action bg-transparent border-bottom border-white border-opacity-5 p-3 px-4 d-flex align-items-center gap-3 transition {{ $conv['is_active'] ? 'active-chat-tab' : 'hover-bg-light' }}">
                    <div class="position-relative">
                        <img src="{{ $conv['avatar'] }}" class="rounded-circle shadow-sm" width="44" height="44">
                        @if($conv['is_active'])
                            <!-- Active indicator badge -->
                            <div class="position-absolute top-0 start-0 bg-primary border border-2 border-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 18px; height: 18px; transform: translate(-25%, -25%);">
                                <svg style="width: 10px; height: 10px;" fill="white" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @else
                            <!-- Online indicator -->
                            <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                style="width: 10px; height: 10px; transform: translate(15%, 15%);"></div>
                        @endif
                    </div>
                    <div class="flex-grow-1" style="overflow: hidden;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="fw-bold text-white mb-0 {{ $conv['is_active'] ? 'text-primary' : '' }}"
                                style="font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $conv['name'] }}
                            </h6>
                            @if($conv['unread_count'] > 0)
                                <span class="badge rounded-pill bg-danger shadow-sm" style="font-size: 0.7rem;">
                                    {{ $conv['unread_count'] }}
                                </span>
                            @endif
                        </div>
                        <p class="small text-secondary text-truncate mb-0 opacity-75" style="font-size: 0.8rem;">
                            {{ $conv['latest_message'] }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <p class="text-secondary">No conversations found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>