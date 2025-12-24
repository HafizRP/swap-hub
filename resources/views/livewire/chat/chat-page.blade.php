@section('title', $this->getTitle())

<div>
    <style>
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
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

        /* Dark Mode - Form inputs in chat */
        #chat-page-component .form-control {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: white !important;
            border-color: transparent !important;
        }

        #chat-page-component .form-control:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25) !important;
        }

        #chat-page-component .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        #chat-page-component .form-control:disabled {
            background-color: rgba(255, 255, 255, 0.02) !important;
            opacity: 0.6;
        }

        /* Active Chat Tab Styling */
        .active-chat-tab {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.15) 0%, rgba(79, 70, 229, 0.05) 100%) !important;
            border-left: 3px solid #4f46e5 !important;
            position: relative;
        }

        .active-chat-tab::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
        }

        .hover-bg-light:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            transition: background-color 0.2s ease;
        }

        /* Light Mode - Chat Page Overrides */
        [data-bs-theme="light"] .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="light"] #chat-page-component .bg-dark {
            background-color: #f8fafc !important;
        }

        [data-bs-theme="light"] #chat-page-component .form-control {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: 1px solid #e2e8f0 !important;
        }

        [data-bs-theme="light"] #chat-page-component .form-control:focus {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15) !important;
        }

        [data-bs-theme="light"] #chat-page-component .form-control::placeholder {
            color: #94a3b8 !important;
        }

        [data-bs-theme="light"] #chat-page-component .text-white {
            color: #1e293b !important;
        }

        [data-bs-theme="light"] #chat-page-component .text-secondary {
            color: #64748b !important;
        }

        [data-bs-theme="light"] #chat-page-component .border-white {
            border-color: #e2e8f0 !important;
        }

        /* Light Mode - Message Bubbles */
        [data-bs-theme="light"] #chat-page-component .bg-primary {
            background-color: #4f46e5 !important;
            color: white !important;
        }

        /* Keep white text in primary bubbles */
        [data-bs-theme="light"] #chat-page-component .bg-primary .text-white,
        [data-bs-theme="light"] #chat-page-component .bg-primary * {
            color: white !important;
        }

        [data-bs-theme="light"] .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="light"] .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
        }

        /* Light Mode - Active Chat Tab */
        [data-bs-theme="light"] .active-chat-tab {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.03) 100%) !important;
            border-left: 3px solid #4f46e5 !important;
        }

        [data-bs-theme="light"] .active-chat-tab::before {
            background: linear-gradient(180deg, #4f46e5 0%, #6366f1 100%);
            box-shadow: 0 0 8px rgba(79, 70, 229, 0.3);
        }

        [data-bs-theme="light"] .hover-bg-light:hover {
            background-color: rgba(0, 0, 0, 0.03) !important;
        }
    </style>

    <div id="chat-page-component" class="container-fluid py-4" style="height: calc(100vh - 160px);">
        <div class="container h-100">
            <div class="card border-0 shadow-lg overflow-hidden h-100 bg-transparent glass-panel">
                <div class="row h-100 g-0 flex-row">

                    <!-- Sidebar - Conversation List -->
                    <div
                        class="col-12 col-lg-4 col-xl-3 border-end border-white border-opacity-10 flex-column h-100 bg-dark bg-opacity-40 backdrop-blur-md {{ $conversation ? 'd-none d-lg-flex' : 'd-flex' }}">
                        @livewire('chat.conversation-sidebar', ['currentConversationId' => $conversationId])
                    </div>

                    <!-- Main Chat Area -->
                    <div
                        class="col-12 col-lg-8 col-xl-9 flex-column h-100 bg-dark bg-opacity-10 {{ $conversation ? 'd-flex' : 'd-none d-lg-flex' }}">
                        @if($conversation)
                            <!-- Chat Header -->
                            <div class="p-4 border-bottom border-white border-opacity-10 bg-dark bg-opacity-25">
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $otherParticipant = $conversation->participants->where('id', '!=', auth()->id())->first();
                                        $displayName = $conversation->name ?? ($otherParticipant->name ?? 'Unknown');
                                        $displayAvatar = $conversation->type === 'project'
                                            ? 'https://ui-avatars.com/api/?name=' . urlencode($conversation->name) . '&background=4f46e5&color=fff'
                                            : ($otherParticipant->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherParticipant->name ?? 'U') . '&background=10b981&color=fff');
                                    @endphp

                                    <!-- Mobile Back Button -->
                                    <a href="/chat" wire:navigate
                                        class="btn btn-icon btn-text-secondary d-lg-none me-2 p-0 text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-chevron-left">
                                            <polyline points="15 18 9 12 15 6"></polyline>
                                        </svg>
                                    </a>

                                    <img src="{{ $displayAvatar }}" class="rounded-circle shadow-sm" width="48" height="48">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-white mb-0">{{ $displayName }}</h5>
                                        <p class="small text-secondary mb-0 opacity-75">
                                            @if($conversation->type === 'project')
                                                <span
                                                    class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 rounded-pill px-3 py-1"
                                                    style="font-size: 10px; letter-spacing: 0.5px; font-weight: 700;">
                                                    PROJECT SQUAD
                                                </span>
                                            @else
                                                <span class="text-success">● Online</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div class="flex-grow-1 overflow-auto p-4 custom-scrollbar" id="messagesContainer"
                                x-init="$el.scrollTop = $el.scrollHeight"
                                @scroll-to-bottom.window="document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight">

                                @forelse($messages as $msg)
                                    @php
                                        $isOwn = $msg['user_id'] == auth()->id();
                                        $isSystem = !$msg['user_id'];
                                    @endphp

                                    @if($isSystem)
                                        <!-- System Message -->
                                        <div class="text-center my-3">
                                            <div
                                                class="d-inline-block bg-primary bg-opacity-10 border border-primary border-opacity-20 rounded-pill px-4 py-2">
                                                <small class="text-white opacity-75">{!! nl2br(e($msg['content'])) !!}</small>
                                            </div>
                                        </div>
                                    @else
                                        <!-- User Message -->
                                        <div class="d-flex gap-3 mb-4 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                                            <img src="{{ $msg['user_avatar'] }}" class="rounded-circle shadow-sm" width="40"
                                                height="40">
                                            <div class="{{ $isOwn ? 'text-end' : '' }}" style="max-width: 70%;">
                                                <div
                                                    class="d-flex align-items-center gap-2 mb-1 {{ $isOwn ? 'flex-row-reverse' : '' }}">
                                                    <span class="fw-bold text-white small">{{ $msg['user_name'] }}</span>
                                                    <span class="text-secondary opacity-50"
                                                        style="font-size: 10px;">{{ $msg['created_at_human'] }}</span>
                                                </div>
                                                <div
                                                    class="p-3 rounded-3 {{ $isOwn ? 'bg-primary text-white' : 'bg-dark bg-opacity-50 text-white border border-white border-opacity-10' }}">
                                                    <div class="mb-1">
                                                        @if(isset($msg['attachments']) && count($msg['attachments']) > 0)
                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                @foreach($msg['attachments'] as $att)
                                                                    @if(Str::startsWith($att['file_type'], 'image/'))
                                                                        <div role="button" class="d-block cursor-pointer"
                                                                            onclick="openGallery(@js($msg['attachments']), '{{ $att['id'] }}')">
                                                                            <img src="{{ $att['file_path'] }}"
                                                                                class="img-fluid rounded border border-white border-opacity-10"
                                                                                style="width: 150px; height: 150px; object-fit: cover;">
                                                                        </div>
                                                                    @else
                                                                        <a href="{{ $att['file_path'] }}" target="_blank"
                                                                            class="d-flex align-items-center gap-2 p-2 bg-black bg-opacity-25 rounded text-decoration-none text-white border border-white border-opacity-10"
                                                                            style="width: 150px;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                                class="feather feather-file">
                                                                                <path
                                                                                    d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z">
                                                                                </path>
                                                                                <polyline points="13 2 13 9 20 9"></polyline>
                                                                            </svg>
                                                                            <span
                                                                                class="small text-truncate w-100">{{ $att['file_name'] }}</span>
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        <p class="mb-0 small">{!! nl2br(e($msg['content'])) !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-secondary">No messages yet. Start the conversation!</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Message Input -->
                            <div class="p-4 border-top border-white border-opacity-10 bg-dark bg-opacity-25">
                                <form wire:submit.prevent="sendMessage">
                                    <!-- Attachment Preview -->
                                    @if(count($attachments) > 0)
                                        <div class="mb-2 p-2 bg-dark bg-opacity-50 rounded d-flex flex-wrap gap-2">
                                            @foreach($attachments as $index => $att)
                                                <div class="position-relative d-inline-block" style="width: 60px; height: 60px;"
                                                    wire:key="{{ $att->getFilename() }}">
                                                    @if(Str::startsWith($att->getMimeType(), 'image/'))
                                                        <img src="{{ $att->temporaryUrl() }}"
                                                            class="w-100 h-100 rounded object-fit-cover border border-white border-opacity-10">
                                                    @else
                                                        <div
                                                            class="w-100 h-100 bg-white bg-opacity-10 rounded d-flex align-items-center justify-content-center border border-white border-opacity-10">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-file text-white">
                                                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z">
                                                                </path>
                                                                <polyline points="13 2 13 9 20 9"></polyline>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <button type="button" wire:click="removeAttachment({{ $index }})"
                                                        class="position-absolute top-0 end-0 btn btn-sm btn-danger p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                                        style="width: 18px; height: 18px; transform: translate(30%, -30%);">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif



                                    <!-- Upload Loading State -->
                                    <div wire:loading wire:target="attachments" class="text-info small mb-2">
                                        <span class="spinner-border spinner-border-sm me-1"></span> Uploading attachments...
                                    </div>

                                    <!-- Validation Errors -->
                                    @error('attachments') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                                    @error('attachments.*') <div class="text-danger small mb-2">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group">
                                        <input type="file" wire:model="attachments" id="fileInput" class="d-none" multiple>

                                        <button type="button"
                                            class="btn btn-outline-secondary border-0 text-white bg-white bg-opacity-5"
                                            onclick="document.getElementById('fileInput').click()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-paperclip">
                                                <path
                                                    d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a5 5 0 0 1 7.07 7.07l-9.19 9.19a4 4 0 0 1-5.66-5.66l9.19-9.19">
                                                </path>
                                            </svg>
                                        </button>

                                        <input type="text" wire:model.live.debounce.250ms="newMessage"
                                            class="form-control bg-white bg-opacity-5 border-0 text-white placeholder-secondary"
                                            placeholder="Type a message..." {{ $loading ? 'disabled' : '' }}>

                                        <button class="btn btn-primary" type="submit" 
                                            {{ (empty($newMessage) && empty($attachments)) ? 'disabled' : '' }}
                                            wire:loading.attr="disabled" 
                                            wire:target="sendMessage">
                                            
                                            <span wire:loading.remove wire:target="sendMessage">
                                                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                </svg>
                                            </span>
                                            
                                            <span wire:loading wire:target="sendMessage">
                                                <span class="spinner-border spinner-border-sm"></span>
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-4">
                                <div
                                    class="mb-4 p-4 rounded-circle bg-dark bg-opacity-25 border border-white border-opacity-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-message-square text-secondary opacity-75">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="fw-bold text-white mb-2">Select a Conversation</h4>
                                <p class="text-secondary opacity-75 mb-0" style="max-width: 400px;">
                                    Choose a conversation from the sidebar to start chatting or checking project updates.
                                </p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap Image Modal -->
    <!-- Bootstrap Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0 position-relative">
                    <button type="button"
                        class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3 bg-dark rounded-circle p-2"
                        data-bs-dismiss="modal" aria-label="Close"></button>

                    <div id="galleryCarousel" class="carousel slide">
                        <div class="carousel-inner" id="gallerySlides">
                            <!-- Items injected via JS -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-4"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-4"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($conversation)

        @script
        <script>
            let currentChannel = null;
            let conversationId = {{ $conversationId ?? 'null' }};
            let userId = {{ auth()->id() }};
            
            // Sync Global Variable
            window.currentConversationId = conversationId;

            // Subscribe GLOBAL to User Channel
            // Only handle "Appending Message" logic here. Notifications are handled by Global Script.
            const userChannelName = `App.Models.User.${userId}`;
            window.Echo.private(userChannelName)
                .listen('.message.sent', (e) => {
                    // Note: 'refresh-conversation-list' is dispatched by global script in app.blade.php

                    // If message belongs to current conversation
                    if (e.conversation_id == conversationId) {
                        // If not from me (or handle multi-tab later)
                        if (e.user_id !== userId) {
                            $wire.call('loadMessages');
                            // Scroll to bottom
                            setTimeout(() => {
                                window.dispatchEvent(new CustomEvent('scroll-to-bottom'));
                            }, 100);
                        }
                    }
                });

            // Handle switching conversations
            Livewire.on('conversation-switched', (data) => {
                const id = data.conversationId !== undefined ? data.conversationId : data;
                conversationId = id;
                window.currentConversationId = id; // Sync Global Variable
                
                // Update URL
                const newUrl = `/chat/${id}`;
                window.history.pushState({ conversationId: id }, '', newUrl);
            });

            // Global Gallery Function
            window.openGallery = function (attachments, startId) {
                const container = document.getElementById('gallerySlides');
                container.innerHTML = '';

                let activeSet = false;

                // Filter only images
                const images = attachments.filter(att => att.file_type.startsWith('image/'));

                images.forEach((att, index) => {
                    const div = document.createElement('div');
                    const isActive = att.id == startId;
                    if (isActive) activeSet = true;

                    div.className = 'carousel-item' + (isActive ? ' active' : '');
                    div.innerHTML = `<div class="d-flex justify-content-center align-items-center" style="height: 90vh;">
                                                    <img src="${att.file_path}" class="d-block" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                 </div>`;
                    container.appendChild(div);
                });

                // Fallback if ID mismatch
                if (!activeSet && images.length > 0) {
                    container.firstElementChild.classList.add('active');
                }

                bootstrap.Modal.getOrCreateInstance(document.getElementById('galleryModal')).show();
            }



            // Handle browser back/forward
            window.addEventListener('popstate', (event) => {
                if (event.state && event.state.conversationId) {
                    $wire.call('selectConversation', event.state.conversationId);
                } else {
                    // If no state, go to chat list
                    window.location.href = '/chat';
                }
            });

            // Set initial state
            if (conversationId) {
                window.history.replaceState({ conversationId: conversationId }, '', window.location.href);
            }
        </script>
        @endscript
    @endif

</div>