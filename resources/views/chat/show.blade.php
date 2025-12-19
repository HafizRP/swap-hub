@php
    $otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
    $displayName = $conversation->name ?? ($otherParticipant->name ?? 'Chat');
@endphp
@section('title', $displayName)
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-black text-white mb-0">
            {{ __('Collaborator Lounge') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4" style="height: calc(100vh - 160px);" x-data="{ 
            messages: [], 
            newMessage: '', 
            userId: {{ auth()->id() }},
            loading: false,
            initialLoading: true,
            searchQuery: '',
            messageSearch: '',
            filterType: 'all',
            conversationsList: {{ json_encode(
                auth()->user()->conversations()->with(['latestMessage', 'participants'])->get()->map(function ($conv) {
                    $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
                    return [
                        'id' => $conv->id,
                        'type' => $conv->type,
                        'name' => $conv->name ?? ($otherParticipant->name ?? 'Unknown')
                    ];
                })
            ) }},
            get hasVisibleConversations() {
                return this.conversationsList.some(conv => {
                    const matchFilter = (this.filterType === 'all' || this.filterType === conv.type);
                    const matchSearch = (!this.searchQuery || conv.name.toLowerCase().includes(this.searchQuery.toLowerCase().trim()));
                    return matchFilter && matchSearch;
                });
            },
            formatTime(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                return new Intl.DateTimeFormat('default', { hour: 'numeric', minute: 'numeric' }).format(date);
            },
            fetchMessages() {
                this.initialLoading = true;
                fetch('{{ route('chat.messages.get', $conversation, false) }}')
                    .then(response => response.json())
                    .then(data => {
                        this.messages = Array.isArray(data) ? data : [];
                        this.scrollToBottom();
                        this.initialLoading = false;
                    })
                    .catch(() => {
                        this.messages = [];
                        this.initialLoading = false;
                    });
            },
            addMessage(msg) {
                if (!Array.isArray(this.messages)) {
                    this.messages = [];
                }
                if (!this.messages.find(m => m.id === msg.id)) {
                    this.messages.push(msg);
                    this.scrollToBottom();
                }
            },
            async sendMessage() {
                if (!this.newMessage.trim() || this.loading) return;
                this.loading = true;
                try {
                    const response = await fetch('{{ route('chat.messages.send', $conversation, false) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: this.newMessage })
                    });
                    if (response.ok) {
                        const sentMsg = await response.json();
                        this.addMessage(sentMsg);
                        this.newMessage = '';
                    }
                } catch (e) {
                    console.error('Failed to send message');
                } finally {
                    this.loading = false;
                }
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.messageContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            },
            init() {
                this.fetchMessages(); 
                if (window.Echo) {
                    window.Echo.private('chat.{{ $conversation->id }}')
                        .listen('.message.sent', (e) => this.addMessage(e));
                }
            }
         }" x-init="init()">
        <div class="container h-100">
            <div class="card border-0 shadow-lg overflow-hidden h-100 bg-transparent glass-panel">
                <div class="row h-100 g-0 flex-row">

                    <!-- Sidebar -->
                    <div x-ignore
                        class="col-lg-4 col-xl-3 border-end border-white border-opacity-10 d-none d-lg-flex flex-column h-100 bg-dark bg-opacity-40 backdrop-blur-md">
                        <div class="p-4 border-bottom border-white border-opacity-10">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-secondary ps-3">
                                    <svg style="width: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" id="sidebarSearch"
                                    class="form-control form-control-sm bg-white bg-opacity-5 border-0 text-white placeholder-secondary rounded-pill py-2"
                                    placeholder="Search conversations..."
                                    style="box-shadow: none;">
                            </div>

                            <div class="d-flex gap-2 mt-4" id="sidebarFilterButtons">
                                <button type="button" data-filter="all"
                                    class="bg-primary text-white border-0 btn btn-xs rounded-pill px-3 fw-bold text-uppercase sidebar-filter-btn shadow-sm transition hover-scale"
                                    style="font-size: 10px; letter-spacing: 0.5px;">All</button>
                                <button type="button" data-filter="project"
                                    class="bg-white bg-opacity-5 text-secondary border-0 btn btn-xs rounded-pill px-3 fw-bold text-uppercase sidebar-filter-btn transition hover-bg-light"
                                    style="font-size: 10px; letter-spacing: 0.5px;">Squads</button>
                                <button type="button" data-filter="direct"
                                    class="bg-white bg-opacity-5 text-secondary border-0 btn btn-xs rounded-pill px-3 fw-bold text-uppercase sidebar-filter-btn transition hover-bg-light"
                                    style="font-size: 10px; letter-spacing: 0.5px;">Direct</button>
                            </div>
                        </div>

                        <div class="flex-grow-1 overflow-auto custom-scrollbar">
                            <div class="list-group list-group-flush" id="sidebarConversationList">
                                @foreach(auth()->user()->conversations()->with(['latestMessage', 'participants'])->get() as $conv)
                                    @php
                                        $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
                                        $displayName = $conv->name ?? ($otherParticipant->name ?? 'Unknown');
                                        $convType = $conv->type;
                                    @endphp
                                    <a href="{{ route('chat.show', $conv) }}" data-type="{{ $convType }}"
                                        data-name="{{ strtolower($displayName) }}"
                                        class="sidebar-conversation-item list-group-item list-group-item-action bg-transparent border-bottom border-white border-opacity-5 p-3 px-4 d-flex align-items-center gap-3 transition {{ $conv->id === $conversation->id ? 'active-chat-tab' : 'hover-bg-light' }}">
                                        <div class="position-relative">
                                            <img src="{{ $conv->type === 'project' ? 'https://ui-avatars.com/api/?name=' . urlencode($conv->name) . '&background=4f46e5&color=fff' : (($otherParticipant->avatar ?? null) ? $otherParticipant->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name ?? 'U') . '&background=10b981&color=fff') }}"
                                                class="rounded-circle shadow-sm" width="44" height="44">
                                            <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                                style="width: 10px; height: 10px; transform: translate(15%, 15%);"></div>
                                        </div>
                                        <div class="flex-grow-1" style="overflow: hidden;">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="fw-bold text-white mb-0 {{ $conv->id === $conversation->id ? 'text-primary' : '' }}"
                                                    style="font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $displayName }}
                                                </h6>
                                            </div>
                                            <p class="small text-secondary text-truncate mb-0 opacity-75" style="font-size: 0.8rem;">
                                                {{ $conv->latestMessage->content ?? 'Start a conversation...' }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                                <div x-show="(searchQuery.trim() !== '' || filterType !== 'all') && !hasVisibleConversations"
                                    class="p-5 text-center opacity-50" x-cloak>
                                    <p class="small fw-bold text-secondary mb-0">No matches found.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Chat Area -->
                    <div class="col-lg-8 col-xl-9 d-flex flex-column h-100 bg-dark bg-opacity-20 backdrop-blur-sm">

                        <!-- Chat Header -->
                        <div
                            class="p-3 px-md-4 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between bg-dark bg-opacity-40">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $currentOther = $conversation->participants->where('id', '!=', auth()->id())->first();
                                @endphp
                                <div class="position-relative">
                                    <img src="{{ $conversation->type === 'project' ? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->name) . '&background=4f46e5&color=fff' : ($currentOther->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($currentOther->name) . '&background=10b981&color=fff') }}"
                                        class="rounded-circle shadow-lg" width="42" height="42">
                                    <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                        style="width: 10px; height: 10px; transform: translate(15%, 15%); box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);"></div>
                                </div>
                                <div>
                                    <h6 class="fw-black text-white mb-0 text-truncate" style="font-size: 1rem; letter-spacing: -0.5px;">
                                        {{ $conversation->name ?? $currentOther->name }}
                                    </h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-2 py-0 border border-white border-opacity-10" 
                                              style="font-size: 9px; letter-spacing: 0.5px; text-transform: uppercase;">
                                            {{ $conversation->type === 'project' ? 'Project Squad' : 'Direct Sync' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('chat.index') }}"
                                class="btn btn-white btn-sm d-lg-none rounded-pill px-3 fw-bold bg-opacity-10 text-white border-0 hover-bg-light">
                                <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- Messages List -->
                        <div class="flex-grow-1 overflow-auto p-4 d-flex flex-column custom-scrollbar" x-ref="messageContainer"
                            style="background-image: radial-gradient(circle at center, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 30px 30px;">

                            <!-- Loading Skeleton -->
                            <template x-if="initialLoading">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-start">
                                        <div class="skeleton" style="width: 45%; height: 50px; border-radius: 1rem; border-bottom-left-radius: 0;"></div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="skeleton bg-primary opacity-20" style="width: 35%; height: 40px; border-radius: 1rem; border-bottom-right-radius: 0;"></div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <div class="skeleton" style="width: 55%; height: 60px; border-radius: 1rem; border-bottom-left-radius: 0;"></div>
                                    </div>
                                </div>
                            </template>

                            <div class="d-flex flex-column gap-3" x-show="!initialLoading" x-cloak>
                                <template x-for="msg in messages" :key="msg.id">
                                    <div class="d-flex w-100"
                                        x-show="!messageSearch || msg.content.toLowerCase().includes(messageSearch.toLowerCase())"
                                        :class="msg.user_id === userId ? 'justify-content-end' : 'justify-content-start'">
                                        <div class="d-flex gap-2"
                                            :class="msg.user_id === userId ? 'flex-row-reverse text-end' : ''"
                                            style="max-width: 75%;">
                                            <template x-if="msg.user_id !== userId">
                                                <img :src="msg.user_avatar" class="rounded-circle shadow-sm mt-auto"
                                                    width="32" height="32">
                                            </template>
                                            <div class="d-flex flex-column" :class="msg.user_id === userId ? 'align-items-end' : 'align-items-start'">
                                                <div class="p-3 px-4 shadow-sm"
                                                    :class="msg.user_id === userId ? 'bg-primary text-white border-0 rounded-4 rounded-bottom-end-0 bg-gradient-primary' : 'bg-dark bg-opacity-75 text-white border border-white border-opacity-10 rounded-4 rounded-bottom-start-0 backdrop-blur-md'">
                                                    <p class="small mb-0 lh-base" x-text="msg.content" style="font-size: 0.95rem;"></p>
                                                </div>
                                                <span class="small text-secondary opacity-50 mt-1 d-block fw-bold tracking-wide"
                                                    style="font-size: 10px;"
                                                    x-text="formatTime(msg.created_at)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="p-3 p-md-4 border-top border-white border-opacity-10 bg-dark bg-opacity-40 backdrop-blur-md">
                            <form @submit.prevent="sendMessage" class="d-flex gap-2 align-items-center">
                                <div class="position-relative flex-grow-1">
                                    <input type="text" x-model="newMessage" required placeholder="Type your message..."
                                        class="form-control form-control-lg bg-white bg-opacity-5 border-0 rounded-pill ps-4 pe-5 text-white placeholder-secondary transition shadow-inner focus-ring-primary"
                                        style="font-size: 0.95rem; height: 50px;">
                                    <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-secondary opacity-50 hover-opacity-100 pe-3">
                                        <svg style="width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg transition hover-scale-110 bg-gradient-primary border-0"
                                    :disabled="loading" style="width: 50px; height: 50px;">
                                    <template x-if="!loading">
                                        <svg style="width: 20px; transform: translateX(2px);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </template>
                                    <template x-if="loading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </template>
                                </button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
        }

        .backdrop-blur-md {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .hover-bg-light:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .hover-scale-110:hover {
            transform: scale(1.1);
        }
        
        .hover-opacity-100:hover {
            opacity: 1 !important;
        }

        .active-chat-tab {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.15) 0%, rgba(79, 70, 229, 0.05) 100%) !important;
            border-left: 3px solid #6366f1 !important;
        }

        .shadow-inner {
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }

        .focus-ring-primary:focus {
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2), inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        .skeleton {
            background: rgba(255, 255, 255, 0.05);
            animation: skeleton-pulse 1.5s infinite ease-in-out;
        }

        @keyframes skeleton-pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('sidebarSearch');
            const filterButtons = document.querySelectorAll('.sidebar-filter-btn');
            const conversations = document.querySelectorAll('.sidebar-conversation-item');

            if (!searchInput || filterButtons.length === 0) return;

            let currentFilter = 'all';
            let currentSearch = '';

            function updateSidebarList() {
                conversations.forEach(item => {
                    const type = item.getAttribute('data-type');
                    const name = item.getAttribute('data-name');
                    const matchesFilter = currentFilter === 'all' || currentFilter === type;
                    const matchesSearch = name.includes(currentSearch.toLowerCase());

                    if (matchesFilter && matchesSearch) {
                        item.classList.remove('d-none');
                        item.classList.add('d-flex');
                    } else {
                        item.classList.add('d-none');
                        item.classList.remove('d-flex');
                    }
                });
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => {
                        b.classList.remove('bg-primary', 'text-white');
                        b.classList.add('bg-white', 'bg-opacity-5', 'text-secondary');
                    });
                    this.classList.remove('bg-white', 'bg-opacity-5', 'text-secondary');
                    this.classList.add('bg-primary', 'text-white');
                    currentFilter = this.getAttribute('data-filter');
                    updateSidebarList();
                });
            });

            searchInput.addEventListener('input', function(e) {
                currentSearch = e.target.value.trim();
                updateSidebarList();
            });
        });
    </script>
</x-app-layout>