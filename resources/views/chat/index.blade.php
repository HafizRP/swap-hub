@section('title', 'Collaborator Lounge')
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Collaborator Lounge') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4" style="height: calc(100vh - 160px);">
        <div class="container h-100">
            <div class="card border-0 shadow-lg overflow-hidden h-100 bg-transparent">
                <div class="row h-100 g-0 flex-row">

                    <!-- Sidebar -->
                    <div class="col-md-4 col-lg-3 border-end border-white border-opacity-10 d-flex flex-column h-100 bg-dark bg-opacity-25">
                        <div class="p-4 border-bottom border-white border-opacity-10">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-0 text-secondary"
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                                    <svg style="width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" id="chatSearch"
                                    class="form-control form-control-sm bg-dark border-0 text-white placeholder-secondary"
                                    placeholder="Search..."
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                            </div>
                            <div class="d-flex gap-2 mt-3" id="filterButtons">
                                <button type="button" data-filter="all"
                                    class="btn btn-sm rounded-pill px-3 fw-black text-uppercase border bg-primary text-white border-primary filter-btn" 
                                    style="font-size: 8px;">All</button>
                                <button type="button" data-filter="project"
                                    class="btn btn-sm rounded-pill px-3 fw-black text-uppercase border bg-transparent text-secondary border-white border-opacity-10 filter-btn" 
                                    style="font-size: 8px;">Squads</button>
                                <button type="button" data-filter="direct"
                                    class="btn btn-sm rounded-pill px-3 fw-black text-uppercase border bg-transparent text-secondary border-white border-opacity-10 filter-btn" 
                                    style="font-size: 8px;">Direct</button>
                            </div>
                        </div>

                        <div class="flex-grow-1 overflow-auto">
                            <div class="list-group list-group-flush" id="conversationList">
                                @forelse($conversations as $conversation)
                                    @php
                                        $otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
                                        $convName = $conversation->name ?? ($otherParticipant->name ?? 'Unknown');
                                        $convType = $conversation->type;
                                    @endphp
                                    <a href="{{ route('chat.show', $conversation) }}"
                                        data-type="{{ $convType }}"
                                        data-name="{{ strtolower($convName) }}"
                                        class="conversation-item list-group-item list-group-item-action bg-transparent border-bottom border-white border-opacity-5 p-4 d-flex align-items-center gap-3 transition hover-bg-light">
                                        <div class="position-relative">
                                            <img src="{{ $conversation->type === 'project' ? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->name) . '&background=4f46e5&color=fff' : ($otherParticipant->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name) . '&background=10b981&color=fff') }}"
                                                class="rounded-3 shadow-sm" width="48" height="48">
                                            <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                                style="width: 12px; height: 12px; transform: translate(25%, 25%);"></div>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                                <h6 class="fw-bold text-white mb-0 text-truncate">
                                                    {{ $convName }}</h6>
                                                <span class="small text-secondary opacity-50"
                                                    style="font-size: 9px;">{{ $conversation->latestMessage ? $conversation->latestMessage->created_at->format('H:i') : '' }}</span>
                                            </div>
                                            <p class="small text-secondary text-truncate mb-0 opacity-75">
                                                {{ $conversation->latestMessage->content ?? 'No messages yet...' }}</p>
                                        </div>
                                        @if($conversation->pivot->last_read_at < ($conversation->latestMessage->created_at ?? now()->subYear()))
                                            <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                                        @endif
                                    </a>
                                @empty
                                    <div class="p-5 text-center">
                                        <p class="text-secondary small fw-bold mb-0">No active threads.</p>
                                        <p class="text-secondary opacity-50 text-uppercase tracking-widest mt-1"
                                            style="font-size: 8px;">Accept a swap to start chatting</p>
                                    </div>
                                @endforelse
                                <div id="noMatches" class="p-5 text-center opacity-50 d-none">
                                    <p class="small fw-bold text-secondary mb-0">No matches found.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Chat Area (Empty State) -->
                    <div class="col-md-8 col-lg-9 d-none d-md-flex align-items-center justify-content-center bg-dark bg-opacity-10 h-100">
                        <div class="text-center px-4" style="max-width: 400px;">
                            <div class="bg-primary bg-opacity-10 rounded-5 p-5 d-inline-flex align-items-center justify-content-center mb-4 shadow-sm">
                                <svg style="width: 48px;" class="text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="fw-black text-white mb-3">Select a Conversation</h4>
                            <p class="text-secondary small lh-lg">Collaborate with fellow students to build amazing
                                projects and exchange knowledge. Real-time synergy starts here.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('chatSearch');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const conversations = document.querySelectorAll('.conversation-item');
            const noMatchesMsg = document.getElementById('noMatches');
            
            let currentFilter = 'all';
            let currentSearch = '';

            function updateList() {
                let hasVisible = false;

                conversations.forEach(item => {
                    const type = item.getAttribute('data-type');
                    const name = item.getAttribute('data-name');
                    
                    const matchesFilter = currentFilter === 'all' || currentFilter === type;
                    const matchesSearch = name.includes(currentSearch.toLowerCase());

                    if (matchesFilter && matchesSearch) {
                        item.classList.remove('d-none');
                        hasVisible = true;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (hasVisible) {
                    noMatchesMsg.classList.add('d-none');
                } else {
                    noMatchesMsg.classList.remove('d-none');
                }
            }

            // Filter Button Click
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Update active state
                    filterButtons.forEach(b => {
                        b.classList.remove('bg-primary', 'text-white', 'border-primary');
                        b.classList.add('bg-transparent', 'text-secondary', 'border-white', 'border-opacity-10');
                    });
                    
                    this.classList.remove('bg-transparent', 'text-secondary', 'border-white', 'border-opacity-10');
                    this.classList.add('bg-primary', 'text-white', 'border-primary');

                    currentFilter = this.getAttribute('data-filter');
                    updateList();
                });
            });

            // Search Input
            searchInput.addEventListener('input', function(e) {
                currentSearch = e.target.value.trim();
                updateList();
            });
        });
    </script>
    
    <style>
        .hover-bg-light:hover { background-color: rgba(255, 255, 255, 0.05) !important; }
    </style>
</x-app-layout>