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
            <div class="card border-0 shadow-lg overflow-hidden h-100 bg-transparent">
                <div class="row h-100 g-0 flex-row">

                    <!-- Sidebar -->
                    <div x-ignore
                        class="col-lg-4 col-xl-3 border-end border-white border-opacity-10 d-none d-lg-flex flex-column h-100 bg-dark bg-opacity-25">
                        <div class="p-4 border-bottom border-white border-opacity-10">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-0 text-secondary"
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                                    <svg style="width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" id="sidebarSearch"
                                    class="form-control form-control-sm bg-dark border-0 text-white placeholder-secondary"
                                    placeholder="Search..."
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                            </div>

                            <div class="d-flex gap-2 mt-3" id="sidebarFilterButtons">
                                <button type="button" data-filter="all"
                                    class="bg-primary text-white border-primary btn btn-sm rounded-pill px-3 fw-black text-uppercase border sidebar-filter-btn"
                                    style="font-size: 8px;">All</button>
                                <button type="button" data-filter="project"
                                    class="bg-transparent text-secondary border-white border-opacity-10 btn btn-sm rounded-pill px-3 fw-black text-uppercase border sidebar-filter-btn"
                                    style="font-size: 8px;">Squads</button>
                                <button type="button" data-filter="direct"
                                    class="bg-transparent text-secondary border-white border-opacity-10 btn btn-sm rounded-pill px-3 fw-black text-uppercase border sidebar-filter-btn"
                                    style="font-size: 8px;">Direct</button>
                            </div>
                        </div>

                        <div class="flex-grow-1 overflow-auto">
                            <div class="list-group list-group-flush" id="sidebarConversationList">
                                @foreach(auth()->user()->conversations()->with(['latestMessage', 'participants'])->get() as $conv)
                                    @php
                                        $otherParticipant = $conv->participants->where('id', '!=', auth()->id())->first();
                                        $displayName = $conv->name ?? ($otherParticipant->name ?? 'Unknown');
                                        $convType = $conv->type;
                                    @endphp
                                    <a href="{{ route('chat.show', $conv) }}" data-type="{{ $convType }}"
                                        data-name="{{ strtolower($displayName) }}"
                                        class="sidebar-conversation-item list-group-item list-group-item-action bg-transparent border-bottom border-white border-opacity-5 p-4 d-flex align-items-center gap-3 transition {{ $conv->id === $conversation->id ? 'active-chat-tab' : 'hover-bg-light' }}">
                                        <div class="position-relative">
                                            <img src="{{ $conv->type === 'project' ? 'https://ui-avatars.com/api/?name=' . urlencode($conv->name) . '&background=4f46e5&color=fff' : (($otherParticipant->avatar ?? null) ? $otherParticipant->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name ?? 'U') . '&background=10b981&color=fff') }}"
                                                class="rounded-3 shadow-sm" width="48" height="48">
                                            <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                                style="width: 12px; height: 12px; transform: translate(25%, 25%);"></div>
                                        </div>
                                        <div class="flex-grow-1" style="overflow: hidden;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="fw-bold text-white mb-0 {{ $conv->id === $conversation->id ? 'text-primary' : '' }}"
                                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-wrap: break-word; word-break: break-word; line-height: 1.4; flex: 1;">
                                                    {{ $displayName }}
                                                </h6>
                                            </div>
                                            <p class="small text-secondary text-truncate mb-0 opacity-75">
                                                {{ $conv->latestMessage->content ?? '...' }}
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
                    <div class="col-lg-8 col-xl-9 d-flex flex-column h-100 bg-dark bg-opacity-10">

                        <!-- Chat Header -->
                        <div
                            class="p-3 p-md-4 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between bg-dark bg-opacity-25">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $currentOther = $conversation->participants->where('id', '!=', auth()->id())->first();
                                @endphp
                                <div class="position-relative">
                                    <img src="{{ $conversation->type === 'project' ? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->name) . '&background=4f46e5&color=fff' : ($currentOther->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($currentOther->name) . '&background=10b981&color=fff') }}"
                                        class="rounded-3 shadow-sm" width="40" height="40">
                                    <div class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle"
                                        style="width: 10px; height: 10px; transform: translate(25%, 25%);"></div>
                                </div>
                                <div class="min-w-0">
                                    <h6 class="fw-black text-white mb-0 text-truncate">
                                        {{ $conversation->name ?? $currentOther->name }}
                                    </h6>
                                    <p class="small text-primary fw-black text-uppercase tracking-widest mb-0"
                                        style="font-size: 8px;">
                                        {{ $conversation->type === 'project' ? 'Project Squad' : 'Direct Sync' }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <!-- Message search removed -->
                            </div>
                            <a href="{{ route('chat.index') }}"
                                class="btn btn-outline-secondary btn-sm d-lg-none rounded-pill px-3 fw-bold border-opacity-25">Exit</a>
                        </div>

                        <!-- Messages List -->
                        <div class="flex-grow-1 overflow-auto p-4 d-flex flex-column" x-ref="messageContainer"
                            style="background-image: radial-gradient(circle at center, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 20px 20px;">

                            <!-- Loading Skeleton -->
                            <template x-if="initialLoading">
                                <div class="d-flex flex-column gap-4">
                                    <div class="d-flex justify-content-start">
                                        <div class="skeleton" style="width: 60%; height: 60px; border-radius: 1rem;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="skeleton bg-primary opacity-25"
                                            style="width: 40%; height: 50px; border-radius: 1rem;"></div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        <div class="skeleton" style="width: 70%; height: 45px; border-radius: 1rem;">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="skeleton bg-primary opacity-25"
                                            style="width: 30%; height: 80px; border-radius: 1rem;"></div>
                                    </div>
                                </div>
                            </template>

                            <div class="d-flex flex-column gap-4" x-show="!initialLoading" x-cloak>
                                <template x-for="msg in messages" :key="msg.id">
                                    <div class="d-flex w-100"
                                        x-show="!messageSearch || msg.content.toLowerCase().includes(messageSearch.toLowerCase())"
                                        :class="msg.user_id === userId ? 'justify-content-end' : 'justify-content-start'">
                                        <div class="d-flex gap-3"
                                            :class="msg.user_id === userId ? 'flex-row-reverse text-end' : ''"
                                            style="max-width: 80%;">
                                            <template x-if="msg.user_id !== userId">
                                                <img :src="msg.user_avatar" class="rounded-circle shadow-sm mt-auto"
                                                    width="28" height="28">
                                            </template>
                                            <div>
                                                <div class="p-3 rounded-4 shadow-sm"
                                                    :class="msg.user_id === userId ? 'bg-primary text-white border-0 rounded-bottom-end-0 shadow-primary' : 'bg-dark bg-opacity-50 text-white border border-white border-opacity-10 rounded-bottom-start-0'">
                                                    <p class="small mb-0 lh-base" x-text="msg.content"></p>
                                                </div>
                                                <span class="small text-secondary opacity-50 mt-1 d-block fw-bold"
                                                    style="font-size: 8px; text-transform: uppercase;"
                                                    x-text="msg.created_at"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 border-top border-white border-opacity-10 bg-dark bg-opacity-25">
                            <form @submit.prevent="sendMessage" class="d-flex gap-3">
                                <input type="text" x-model="newMessage" required placeholder="Type your message..."
                                    class="form-control form-control-lg bg-dark border-0 rounded-pill px-4 text-white placeholder-secondary transition shadow-inner"
                                    style="background-color: rgba(255,255,255,0.05) !important;">
                                <button type="submit"
                                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg transition hover-scale-110"
                                    :disabled="loading" style="width: 48px; height: 48px;">
                                    <template x-if="!loading">
                                        <svg style="width: 20px;" class="rotate-90" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="loading">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
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
        .hover-bg-light:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .shadow-primary {
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2), 0 4px 6px -2px rgba(79, 70, 229, 0.1);
        }

        .hover-scale-110:hover {
            transform: scale(1.05);
        }

        .rounded-bottom-end-0 {
            border-bottom-right-radius: 0 !important;
        }

        .rounded-bottom-start-0 {
            border-bottom-left-radius: 0 !important;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        .skeleton {
            background: rgba(255, 255, 255, 0.05);
            animation: skeleton-pulse 1.5s infinite ease-in-out;
        }

        @keyframes skeleton-pulse {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        .active-chat-tab {
            background-color: rgba(79, 70, 229, 0.1) !important;
            border-left: 4px solid #4f46e5 !important;
            animation: tab-enter 0.3s ease-out;
        }

        @keyframes tab-enter {
            from {
                transform: translateX(-5px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        </script><script>document.addEventListener('DOMContentLoaded', function() {
                const searchInput=document.getElementById('sidebarSearch');
                const filterButtons=document.querySelectorAll('.sidebar-filter-btn');
                const conversations=document.querySelectorAll('.sidebar-conversation-item');

                // Only proceed if sidebar elements exist (desktop view)
                if ( !searchInput || filterButtons.length===0) return;

                let currentFilter='all';
                let currentSearch='';

                function updateSidebarList() {
                    conversations.forEach(item=> {
                            const type=item.getAttribute('data-type');
                            const name=item.getAttribute('data-name');

                            const matchesFilter=currentFilter==='all' || currentFilter===type;
                            const matchesSearch=name.includes(currentSearch.toLowerCase());

                            if (matchesFilter && matchesSearch) {
                                item.classList.remove('d-none');
                                item.classList.add('d-flex'); // Restore d-flex for flex layout
                            }

                            else {
                                item.classList.add('d-none');
                                item.classList.remove('d-flex');
                            }
                        });
                }

                // Filter Button Click
                filterButtons.forEach(btn=> {
                        btn.addEventListener('click', function() {

                                // Update active state
                                filterButtons.forEach(b=> {
                                        b.classList.remove('bg-primary', 'text-white', 'border-primary');
                                        b.classList.add('bg-transparent', 'text-secondary', 'border-white', 'border-opacity-10');
                                    });

                                this.classList.remove('bg-transparent', 'text-secondary', 'border-white', 'border-opacity-10');
                                this.classList.add('bg-primary', 'text-white', 'border-primary');

                                currentFilter=this.getAttribute('data-filter');
                                updateSidebarList();
                            });
                    });

                // Search Input
                searchInput.addEventListener('input', function(e) {
                        currentSearch=e.target.value.trim();
                        updateSidebarList();
                    });
            });
        </script><style></x-app-layout>